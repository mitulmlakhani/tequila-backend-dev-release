<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\ModelHasPermission;
use App\Services\UserPermissionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RolePermissionTest extends TestCase
{
    use DatabaseTransactions;

    protected $restaurantId;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        if (\App\Models\Permission::where('guard_name', 'web')->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'PermissionsSeeder']);
        }
        if (\App\Models\Permission::where('guard_name', 'api')->count() === 0) {
            $this->artisan('db:seed', ['--class' => 'NewApiPermissionsSeeder']);
        }

        $restaurant = \App\Models\Restaurant::find(101) ?: \App\Models\Restaurant::first();
        if (!$restaurant) {
            $restaurant = \App\Models\Restaurant::create([
                'id' => 101,
                'name' => 'Tequila QA Restaurant',
                'status' => 'Active',
            ]);
        }
        $this->restaurantId = $restaurant->id;

        $this->adminUser = User::where('restaurant_id', $this->restaurantId)->first();
        if (!$this->adminUser) {
            $this->adminUser = User::create([
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
                'restaurant_id' => $this->restaurantId,
                'user_type' => 2,
            ]);
        }

        $roles = ['Admin', 'Manager', 'Cashier', 'Waiter'];
        foreach ($roles as $rName) {
            $role = Role::firstOrCreate([
                'name' => $rName,
                'guard_name' => 'web',
                'restaurant_id' => $this->restaurantId,
            ], [
                'role_type' => 2,
                'status' => true,
            ]);
            $perms = Permission::where('permission_type', 2)->where('guard_name', 'web')->take(5)->pluck('id');
            $role->permissions()->sync($perms);
            if ($rName === 'Admin') {
                $role->syncPermissions(Permission::where('permission_type', 2)->where('guard_name', 'web')->get());
                if (!$this->adminUser->hasRole($role->id)) {
                    $this->adminUser->assignRole($role->id);
                }
            }
        }

        $this->adminUser->syncPermissions(Permission::where('permission_type', 2)->where('guard_name', 'web')->get());
    }

    public function test_role_permission_route_renders_successfully()
    {
        $response = $this->actingAs($this->adminUser)->get(route('role-permission'));

        $response->assertStatus(200);
        $response->assertSee('Employees & Permissions');
        $response->assertSee('Reset to Role Defaults');
        $response->assertSee('Save Changes');
        $response->assertSee('(POS)');
        $response->assertSee('(Back-office)');
        $response->assertSee('perm-guard-badge');

        // Verify RestaurantSuperAdmin users are excluded from the employees matrix
        $superAdminUser = User::where('restaurant_id', $this->restaurantId)
            ->whereHas('roles', fn($q) => $q->where('name', 'RestaurantSuperAdmin'))
            ->first();

        if ($superAdminUser) {
            $response->assertDontSee('data-user-id="' . $superAdminUser->id . '"');
        }
    }

    public function test_role_assignment_synchronizes_direct_permissions()
    {
        $role = Role::where('restaurant_id', $this->restaurantId)
            ->where('name', 'Cashier')
            ->first();

        $this->assertNotNull($role);
        $rolePermCount = $role->permissions->count();
        $this->assertGreaterThan(0, $rolePermCount);

        $testUser = User::create([
            'name'          => 'Test Role Assign User',
            'email'         => 'test_assign_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        $testUser->assignRole($role->id);

        $directPermsCount = $testUser->fresh()->getDirectPermissions()->count();
        $this->assertEquals($rolePermCount, $directPermsCount);
    }

    public function test_multi_role_unassignment_preserves_shared_permissions()
    {
        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();
        $waiterRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Waiter')->first();

        $this->assertNotNull($cashierRole);
        $this->assertNotNull($waiterRole);

        $testUser = User::create([
            'name'          => 'Test Multi Role User',
            'email'         => 'test_multi_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        // Assign both Cashier and Waiter
        $testUser->syncRoles([$cashierRole->id, $waiterRole->id]);

        $waiterPermIds = $waiterRole->permissions->pluck('id')->toArray();
        $this->assertGreaterThan(0, count($waiterPermIds));

        // Now remove Cashier, keeping Waiter only
        $testUser->syncRoles([$waiterRole->id]);

        $userDirectPerms = $testUser->fresh()->getDirectPermissions()->pluck('id')->toArray();

        // All waiter permissions should still be present
        foreach ($waiterPermIds as $permId) {
            $this->assertContains($permId, $userDirectPerms, "Permission {$permId} should be preserved from Waiter role");
        }
    }

    public function test_independent_direct_permissions_are_preserved_on_role_changes()
    {
        $waiterRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Waiter')->first();
        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();

        $testUser = User::create([
            'name'          => 'Test Independent Perm User',
            'email'         => 'test_indep_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        $testUser->syncRoles([$waiterRole->id]);

        // Find a permission not in Waiter
        $independentPerm = Permission::whereNotIn('id', $waiterRole->permissions->pluck('id'))->first();
        $this->assertNotNull($independentPerm);

        // Give permission directly
        $testUser->givePermissionTo($independentPerm->id);
        $this->assertTrue($testUser->fresh()->hasDirectPermission($independentPerm->id));

        // Now assign Cashier as well
        $testUser->syncRoles([$waiterRole->id, $cashierRole->id]);
        $this->assertTrue($testUser->fresh()->hasDirectPermission($independentPerm->id));

        // Now remove Cashier
        $testUser->syncRoles([$waiterRole->id]);
        $this->assertTrue($testUser->fresh()->hasDirectPermission($independentPerm->id), 'Independent direct permission must be preserved');
    }

    public function test_direct_permissions_take_precedence_in_authorization()
    {
        $adminRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Admin')->first();

        $testUser = User::create([
            'name'          => 'Test Auth Precedence User',
            'email'         => 'test_auth_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        $testUser->assignRole($adminRole->id);

        $perm = Permission::where('name', 'role-list')->first();
        $this->assertNotNull($perm);
        $this->assertTrue($testUser->hasPermissionTo($perm->name));

        // Remove role-list directly from user
        $service = app(UserPermissionService::class);
        $currentPerms = $testUser->getDirectPermissions()->pluck('id')->toArray();
        $withoutRoleList = array_diff($currentPerms, [$perm->id]);
        $service->updateUserDirectPermissions($testUser, $withoutRoleList);

        // Authorization check must recognize direct removal
        $this->assertFalse($testUser->fresh()->hasPermissionTo($perm->name));

        // Reset to role defaults restores it
        $service->resetUserToRoleDefaults($testUser);
        $this->assertTrue($testUser->fresh()->hasPermissionTo($perm->name));
    }

    public function test_reset_user_permissions_endpoint()
    {
        $testUser = User::where('restaurant_id', $this->restaurantId)
            ->whereHas('roles')
            ->first();

        $response = $this->actingAs($this->adminUser)->postJson(route('role-permission.reset-user'), [
            'user_id' => $testUser->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['success', 'msg', 'user_id', 'permissions']);
    }

    public function test_save_user_permissions_endpoint()
    {
        $testUser = User::where('restaurant_id', $this->restaurantId)
            ->whereHas('roles')
            ->first();

        $samplePerms = Permission::take(3)->pluck('id')->toArray();

        $response = $this->actingAs($this->adminUser)->postJson(route('role-permission.save-users'), [
            'user_permissions' => [
                $testUser->id => $samplePerms
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $userPerms = $testUser->fresh()->getDirectPermissions()->pluck('id')->toArray();
        $this->assertEquals(sort($samplePerms), sort($userPerms));
    }

    public function test_multiple_roles_displayed_and_handled_in_matrix()
    {
        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();
        $waiterRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Waiter')->first();

        $multiUser = User::create([
            'name'          => 'Multi Role Testing User',
            'email'         => 'multi_role_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        $multiUser->syncRoles([$cashierRole->id, $waiterRole->id]);

        $response = $this->actingAs($this->adminUser)->get(route('role-permission'));
        $response->assertStatus(200);

        // Verify the HTML contains classes for both roles on the employee row
        $response->assertSee('r-cashier');
        $response->assertSee('r-waiter');
        $response->assertSee('rolepills-wrap');
        $response->assertSee('Multi Role Testing User');

        // Test resetting this multi-role user restores combined permissions from both roles
        $combinedDefaults = array_values(array_unique(array_merge(
            $cashierRole->permissions->pluck('id')->toArray(),
            $waiterRole->permissions->pluck('id')->toArray()
        )));

        $resetResponse = $this->actingAs($this->adminUser)->postJson(route('role-permission.reset-user'), [
            'user_id' => $multiUser->id
        ]);

        $resetResponse->assertStatus(200);
        $resetPerms = $resetResponse->json('permissions');
        sort($combinedDefaults);
        sort($resetPerms);
        $this->assertEquals($combinedDefaults, $resetPerms);
    }

    public function test_sync_changes_logs_are_recorded_for_all_role_and_permission_tables()
    {
        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();
        $waiterRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Waiter')->first();
        $testPerm = Permission::where('permission_type', 2)->first();

        // 1. Roles table sync changes (insert, update, delete)
        $role = Role::create([
            'name'          => 'SyncLogTestRole_' . uniqid(),
            'restaurant_id' => $this->restaurantId,
            'guard_name'    => 'web',
        ]);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'roles',
            'operation'     => 'insert',
            'restaurant_id' => $this->restaurantId,
        ]);

        $role->update(['name' => 'SyncLogTestRole_Updated']);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'roles',
            'operation'     => 'update',
            'restaurant_id' => $this->restaurantId,
        ]);

        $role->delete();
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'roles',
            'operation'     => 'delete',
            'restaurant_id' => $this->restaurantId,
        ]);

        // 2. Role Has Permissions table sync changes (insert, delete)
        $permRole = Role::create([
            'name'          => 'SyncPermRole_' . uniqid(),
            'guard_name'    => 'web',
            'restaurant_id' => $this->restaurantId,
        ]);
        $permRole->givePermissionTo($testPerm->id);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'role_has_permissions',
            'operation'     => 'insert',
            'restaurant_id' => $this->restaurantId,
        ]);

        $permRole->revokePermissionTo($testPerm->id);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'role_has_permissions',
            'operation'     => 'delete',
            'restaurant_id' => $this->restaurantId,
        ]);

        // 3. User Role and Permission assignments
        $testUser = User::create([
            'name'          => 'Sync Test User',
            'email'         => 'sync_user_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        // Model Has Roles: assign both roles
        $testUser->syncRoles([$cashierRole->id, $waiterRole->id]);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'model_has_roles',
            'operation'     => 'insert',
            'restaurant_id' => $this->restaurantId,
        ]);

        // Model Has Permissions: direct permissions synchronized
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'model_has_permissions',
            'operation'     => 'insert',
            'restaurant_id' => $this->restaurantId,
        ]);

        // Model Has Roles: remove one role (triggers delete sync log)
        $testUser->syncRoles([$waiterRole->id]);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'model_has_roles',
            'operation'     => 'delete',
            'restaurant_id' => $this->restaurantId,
        ]);

        // Model Has Permissions: revoked direct permission (triggers delete sync log)
        $testUser->givePermissionTo($testPerm->id);
        $testUser->revokePermissionTo($testPerm->id);
        $this->assertDatabaseHas('sync_changes', [
            'table_name'    => 'model_has_permissions',
            'operation'     => 'delete',
            'restaurant_id' => $this->restaurantId,
        ]);
    }

    public function test_role_permission_template_store_and_endpoints()
    {
        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();

        // 1. loadRolePermission endpoint
        $loadResp = $this->actingAs($this->adminUser)->getJson(route('load-role-permission', ['role' => $cashierRole->name]));
        $loadResp->assertStatus(200);
        $loadResp->assertJson(['error' => false]);
        $loadResp->assertJsonStructure(['error', 'resp', 'msg']);

        // 2. loadDefaultPermissions endpoint
        $defaultResp = $this->actingAs($this->adminUser)->postJson(route('load-default-permissions'), ['role' => $cashierRole->name]);
        $defaultResp->assertStatus(200);
        $defaultResp->assertJson(['error' => false]);

        // 3. store role template permissions with sync_existing_users = false
        $allPerms = Permission::where('permission_type', 2)->take(4)->pluck('id')->toArray();
        $initialPerms = array_slice($allPerms, 0, 2); // [perms 0, 1]

        // Set initial role permissions
        $cashierRole->syncPermissions($initialPerms);

        // Create an employee with Cashier role having the initial role permissions
        $cashierEmployee = User::create([
            'restaurant_id' => $this->restaurantId,
            'name'          => 'Cashier Employee',
            'email'         => 'cashier_emp_' . uniqid() . '@example.com',
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);
        $cashierEmployee->assignRole($cashierRole);
        $service = app(UserPermissionService::class);
        $service->updateUserDirectPermissions($cashierEmployee, $initialPerms);

        // Update template only (sync_existing_users = 0 / false) to [perms 0, 1, 3]
        $templateOnlyPerms = [$allPerms[0], $allPerms[1], $allPerms[3]];
        $storeResp = $this->actingAs($this->adminUser)->postJson(route('role-permission.store'), [
            'roles'               => $cashierRole->name,
            'permission'          => $templateOnlyPerms,
            'sync_existing_users' => 0,
        ]);
        $storeResp->assertStatus(200);
        $storeResp->assertJson(['success' => true]);

        // Template should be updated
        $updatedPerms = $cashierRole->fresh()->permissions->pluck('id')->toArray();
        sort($templateOnlyPerms);
        sort($updatedPerms);
        $this->assertEquals($templateOnlyPerms, $updatedPerms);

        // Existing employee's direct permissions must be UNTOUCHED (still [perms 0, 1])
        $empPermsUntouched = $cashierEmployee->fresh()->getDirectPermissions()->pluck('id')->toArray();
        sort($initialPerms);
        sort($empPermsUntouched);
        $this->assertEquals($initialPerms, $empPermsUntouched);

        // 4. store role template permissions with sync_existing_users = true to [perms 0, 2]
        // This should remove perm 1 (was in template, now removed) and add perm 2
        $syncAllPerms = [$allPerms[0], $allPerms[2]];
        $storeSyncResp = $this->actingAs($this->adminUser)->postJson(route('role-permission.store'), [
            'roles'               => $cashierRole->name,
            'permission'          => $syncAllPerms,
            'sync_existing_users' => 1,
        ]);
        $storeSyncResp->assertStatus(200);
        $storeSyncResp->assertJson(['success' => true]);

        // Existing employee's permissions must now be synchronized to [perms 0, 2]
        $empPermsAfterSync = $cashierEmployee->fresh()->getDirectPermissions()->pluck('id')->toArray();
        sort($syncAllPerms);
        sort($empPermsAfterSync);
        $this->assertEquals($syncAllPerms, $empPermsAfterSync);
    }

    public function test_check_api_permission_middleware_enforces_direct_permissions()
    {
        $middleware = new \App\Http\Middleware\CheckApiPermission();
        $role = Role::firstOrCreate(['name' => 'Cashier', 'restaurant_id' => $this->restaurantId, 'guard_name' => 'api']);
        $perm = Permission::firstOrCreate(['name' => 'floors.manage', 'guard_name' => 'api']);
        $role->givePermissionTo($perm);

        $user = User::create([
            'name'          => 'POS Cashier API Test',
            'email'         => 'pos_api_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);
        $user->assignRole($role);

        // Initial direct permission grant
        $user->givePermissionTo($perm);

        \Illuminate\Support\Facades\Auth::guard('sanctum')->setUser($user);

        $request = \Illuminate\Http\Request::create('/api/floors', 'GET');

        $response = $middleware->handle($request, function ($req) {
            return response()->json(['success' => true]);
        });
        $this->assertEquals(200, $response->getStatusCode());

        // Revoke direct permission from user (role still has it in role_has_permissions)
        $user->revokePermissionTo($perm);

        $forbiddenResponse = $middleware->handle($request, function ($req) {
            return response()->json(['success' => true]);
        });
        $this->assertEquals(403, $forbiddenResponse->getStatusCode());
    }

    public function test_superadmin_uses_native_spatie_role_permissions()
    {
        $superAdminRole = Role::firstOrCreate(['name' => 'RestaurantSuperAdmin', 'guard_name' => 'web']);
        $perm = Permission::firstOrCreate(['name' => 'superadmin-test-perm', 'guard_name' => 'web'], ['permission_type' => 2]);
        $perm->permission_type = 2;
        $perm->save();
        $superAdminRole->givePermissionTo($perm);

        $superAdmin = User::create([
            'name'          => 'Test Super Admin',
            'email'         => 'superadmin_' . uniqid() . '@example.com',
            'restaurant_id' => $this->restaurantId,
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);
        $superAdmin->assignRole($superAdminRole);

        // SuperAdmin has 0 direct permissions in model_has_permissions
        $this->assertTrue($superAdmin->getDirectPermissions()->isEmpty());

        // RestaurantSuperAdmin is a restaurant-level user, but not an employee
        $this->assertTrue($superAdmin->isRestaurantUser());
        $this->assertFalse($superAdmin->isRestaurantEmployee());
        $this->assertTrue($superAdmin->hasPermissionTo($perm->name));
        $this->assertTrue($superAdmin->getAllPermissions()->pluck('name')->contains($perm->name));

        // API endpoint returns all permissions for RestaurantSuperAdmin (permission_type = 2 only)
        $apiResp = $this->actingAs($superAdmin, 'sanctum')->getJson('/api/user/permissions');
        $apiResp->assertStatus(200);
        $this->assertCount(Permission::where('permission_type', 2)->count(), $apiResp->json('data.permissions'));

        // TequilaSuperAdmin is a system-level super admin
        $tequilaRole = Role::firstOrCreate(['name' => 'TequilaSuperAdmin', 'guard_name' => 'web']);
        $tequilaAdmin = User::create([
            'name'          => 'Test Tequila Super Admin',
            'email'         => 'tequila_admin_' . uniqid() . '@example.com',
            'restaurant_id' => null,
            'password'      => bcrypt('secret'),
            'user_type'     => 1,
        ]);
        $tequilaAdmin->assignRole($tequilaRole);
        $this->assertTrue($tequilaAdmin->isSystemSuperAdmin());
        $this->assertFalse($tequilaAdmin->isRestaurantUser());
        $this->assertFalse($tequilaAdmin->isRestaurantEmployee());
    }

    public function test_strict_isolation_between_superadmin_type_1_and_restaurant_type_2()
    {
        // 1. RolePermission index page only includes permission_type = 2
        $response = $this->actingAs($this->adminUser)->get(route('role-permission'));
        $response->assertStatus(200);
        $formattedPerms = $response->viewData('formattedPermissions');
        $this->assertNotEmpty($formattedPerms);
        $permIds = $formattedPerms->pluck('id')->toArray();
        $nonRestaurantPermsCount = Permission::whereIn('id', $permIds)->where('permission_type', '!=', 2)->count();
        $this->assertEquals(0, $nonRestaurantPermsCount, "All loaded permissions must have permission_type = 2");

        // 2. Setting permission_type = 1 permission on restaurant role (role_type = 2) via store is ignored
        $superAdminPerm = Permission::where('permission_type', 1)->first();
        $restaurantPerm = Permission::where('permission_type', 2)->first();

        $cashierRole = Role::where('restaurant_id', $this->restaurantId)->where('name', 'Cashier')->first();
        $this->assertNotNull($cashierRole);

        $storeResp = $this->actingAs($this->adminUser)->postJson(route('role-permission.store'), [
            'roles'               => $cashierRole->name,
            'permission'          => [$superAdminPerm->id, $restaurantPerm->id],
            'sync_existing_users' => 1,
        ]);
        $storeResp->assertStatus(200);

        $cashierRolePermIds = $cashierRole->fresh()->permissions->pluck('id')->toArray();
        $this->assertNotContains($superAdminPerm->id, $cashierRolePermIds, "SuperAdmin permission must not be saved on restaurant role");
        $this->assertContains($restaurantPerm->id, $cashierRolePermIds, "Restaurant permission should be saved on restaurant role");

        // 3. Direct user permission update cannot assign permission_type = 1 to restaurant user
        $employee = User::create([
            'restaurant_id' => $this->restaurantId,
            'name'          => 'Isolation Test Employee',
            'email'         => 'isolation_' . uniqid() . '@example.com',
            'password'      => bcrypt('secret'),
            'user_type'     => 2,
        ]);

        app(UserPermissionService::class)->updateUserDirectPermissions($employee, [$superAdminPerm->id, $restaurantPerm->id]);
        $directPermIds = $employee->fresh()->getDirectPermissions()->pluck('id')->toArray();
        $this->assertNotContains($superAdminPerm->id, $directPermIds, "SuperAdmin permission must not be assigned to restaurant employee");
        $this->assertContains($restaurantPerm->id, $directPermIds, "Restaurant permission must be assigned to restaurant employee");

        // 4. API permissions endpoint never contains permission_type = 1
        $apiResp = $this->actingAs($employee, 'sanctum')->getJson('/api/user/permissions');
        $apiResp->assertStatus(200);
        $apiPermIds = collect($apiResp->json('data.permissions'))->pluck('id')->toArray();
        $this->assertNotContains($superAdminPerm->id, $apiPermIds);
    }
}


