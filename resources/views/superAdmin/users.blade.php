@extends('layouts.master')
@section('title', 'Super Admin • Users')
@section('content')
<style>
    .swipe-dotted{
        background-image: linear-gradient(to right, black 50%, rgba(255,255,255,0) 0%);
        background-position: bottom; background-size: 5px 1px; background-repeat: repeat-x;
        border:1px dashed #ccc; color: transparent; text-shadow:0 0 0 #000; caret-color:transparent; user-select:none;
    }
    .passcode { cursor: pointer; }
</style>

<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <!-- Add/Edit User Form -->
            <div class="col-md-3">
                <div class="main-heading mb-3"><h4 id="formTitle">Add User (Super Admin)</h4></div>

                @if (session('success'))
                  <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif
                @if (session('error'))
                  <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                  </div>
                @endif

                <form id="user-form" action="{{ route('sa.user-create') }}" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" value="POST">
                    <input type="hidden" name="_method" id="method-override" value="POST">
                    <input type="hidden" name="user_id" id="user_id" value="">

                    <div class="mb-3">
                        <label class="form-label" for="restaurant_id">Restaurant</label>
                        <select class="form-select" id="restaurant_id" name="restaurant_id" required>
                            <option value="">Select Restaurant</option>
                            @foreach($restaurants as $r)
                                <option value="{{ $r->id }}">{{ ucwords($r->parentRestaurant ? ($r->parentRestaurant->name . ': ' . $r->branch_name) : $r->name) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="role">Select Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <!-- will be filled via AJAX when restaurant changes -->
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="passcode">Login Number</label>
                        <input type="text" class="form-control" id="passcode" name="passcode" required>
                        <div id="passcodeError" class="invalid-feedback"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Swipe Card</label>
                        <input type="text" id="card-swipe-input" class="form-control swipe-dotted" autocomplete="off" readonly>
                        <input type="hidden" name="card_id" id="card_id" value="">
                        <div id="card-display" class="mt-2 text-success fw-bold"></div>
                    </div>

                    <div class="mb-3 email-password-section">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>

                    <div class="mb-3 email-password-section password-div">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="mobile">Phone</label>
                        <input type="text" class="form-control" id="mobile" name="mobile">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="1">Active</option>
                            <option value="0">In-active</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="formSubmitBtn">Add</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" id="resetUserForm">Reset</button>
                </form>
            </div>

            <!-- List & Filters -->
            <div class="col-md-9">
                <div class="main-heading mb-3 d-flex justify-content-between align-items-center">
                    <h4>Super Admin • User Management</h4>
                    <form method="GET" action="{{ route('sa.user-list') }}" class="d-flex gap-2">
                        <select name="restaurant_id" class="form-select">
                            <option value="">All Restaurants</option>
                            @foreach($restaurants as $r)
                                <option value="{{ $r->id }}" {{ (string)$restaurantId === (string)$r->id ? 'selected' : '' }}>
                                    {{ ucwords($r->parentRestaurant ? ($r->parentRestaurant->name . ': ' . $r->branch_name) : $r->name) }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="search" class="form-control" placeholder="Search name/email/phone/passcode"
                               value="{{ $search }}">
                        <select name="limit" class="form-select">
                            @foreach([10,15,25,50] as $l)
                                <option value="{{ $l }}" {{ (int)$limit===$l?'selected':'' }}>{{ $l }}/page</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                </div>

                <div class="main-content p-3">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Contacts</th>
                                <th>Login #</th>
                                <th>Card</th>
                                <th>Restaurant</th>
                                <th>Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $u)
                            <tr>
                                <td>
                                    {{ $u->name }}<br>
                                    @foreach($u->getRoleNames() as $rn)
                                        <b>{{ $rn }}</b>
                                    @endforeach
                                </td>
                                <td>{{ $u->email }}<br>{{ $u->mobile }}</td>
                                <td class="passcode" data-passcode="{{ $u->passcode }}">****</td>
                                <td class="text-center">{{ $u->card_id ? '****' : '' }}</td>
                                <td><b>{{ $u->restaurant?->name ?? '-' }}</b></td>
                                <td>
                                    <div class="{{ $u->status ? 'reserved' : 'pending' }}">
                                        <img src="{{ $u->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}" alt="">
                                        <span>{{ $u->status ? 'Active' : 'In-active' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="me-2">
                                        @can('sa-user-edit')
                                        <a href="#" class="sa-user-edit" data-id="{{ $u->id }}">
                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                        </a>
                                        @endcan
                                    </span>
                                    <span>
                                        @can('sa-user-delete')
                                        <a href="#" class="sa-user-delete" data-url="{{ route('sa.user-delete', ['id'=>$u->id]) }}">
                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="delete">
                                        </a>
                                        @endcan
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3 d-flex justify-content-end">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('users.delete-modal')
@include('users.payroll-modal')

@endsection

@section('js')
<script>
    var isValidationError = {{ Session::has('errors') ? 'true' : 'false' }};

    // Routes for JS
    var saUserCreateUrl  = '{{ route('sa.user-create') }}';
    var saUserUpdateUrl  = '{{ route('sa.user-edit', ':id') }}';
    var saUserDetailUrl  = '{{ route('sa.user', ':id') }}';
    var saRolesByRestUrl = '{{ route('sa.roles-by-restaurant') }}';
    var saCheckPasscode  = '{{ route('sa.check-passcode') }}';
    var saGetPayrollUrl  = '{{ route('sa.user-get-payroll', ':id') }}';
    var saSavePayrollUrl = '{{ route('sa.user-save-payroll', ':id') }}';

    // Restaurant → load roles
    document.getElementById('restaurant_id').addEventListener('change', async function(){
        const rid = this.value;
        const roleSel = document.getElementById('role');
        roleSel.innerHTML = '<option value="">Loading...</option>';
        if(!rid){ roleSel.innerHTML = '<option value="">Select Role</option>'; return; }

        const res = await fetch(saRolesByRestUrl + '?restaurant_id=' + encodeURIComponent(rid));
        const json = await res.json();
        roleSel.innerHTML = '<option value="">Select Role</option>';
        (json.roles || []).forEach(r => {
            const opt = document.createElement('option');
            const label = (r.restaurant_id === null || r.restaurant_id === undefined) ? `${r.name} (Global)` : r.name;
            opt.value = r.id; opt.textContent = label;
            roleSel.appendChild(opt);
        });

    });

    // Passcode uniqueness check per restaurant (on blur)
    document.getElementById('passcode').addEventListener('blur', async function(){
        const passcode = this.value.trim();
        const rid = document.getElementById('restaurant_id').value;
        const userId = document.getElementById('user_id').value || '';
        if(!passcode || !rid) return;
        const res = await fetch(saCheckPasscode, {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type':'application/json'},
            body: JSON.stringify({ passcode, restaurant_id: rid, user_id: userId })
        });
        const json = await res.json();
        const err = document.getElementById('passcodeError');
        if(json.isUnique === false){
            err.textContent = 'This login number is already used by ' + (json.user?.name || 'another user') + ' in this restaurant.';
            document.getElementById('passcode').classList.add('is-invalid');
        } else {
            err.textContent = '';
            document.getElementById('passcode').classList.remove('is-invalid');
        }
    });

    // Edit flow
    document.querySelectorAll('.sa-user-edit').forEach(el=>{
        el.addEventListener('click', async (e)=>{
            e.preventDefault();
            const id = el.dataset.id;
            const res = await fetch(saUserDetailUrl.replace(':id', id));
            const json = await res.json();
            const u = json.data;

            // switch to EDIT mode
            document.getElementById('formTitle').textContent = 'Edit User';
            document.getElementById('formMethod').value = 'PATCH';
            document.getElementById('method-override').value = 'PATCH';
            document.getElementById('formSubmitBtn').textContent = 'Update';
            document.getElementById('user_id').value = u.id;

            // Lock restaurant
            const ridSel = document.getElementById('restaurant_id');
            ridSel.value = u.restaurant_id;
            ridSel.disabled = true;

            // trigger roles load
            const roleSel = document.getElementById('role');
            roleSel.innerHTML = '<option value="">Loading...</option>';
            const rolesRes = await fetch(saRolesByRestUrl + '?restaurant_id=' + encodeURIComponent(u.restaurant_id));
            const rolesJson = await rolesRes.json();
            roleSel.innerHTML = '<option value="">Select Role</option>';
            (rolesJson.roles || []).forEach(r=>{
            const opt = document.createElement('option');
            const label = (r.restaurant_id === null || r.restaurant_id === undefined) ? `${r.name} (Global)` : r.name;
            opt.value = r.id; opt.textContent = label;
            if (String(r.id) === String(json.userRole)) opt.selected = true;
            roleSel.appendChild(opt);
            });


            // fields
            document.getElementById('name').value = u.name || '';
            document.getElementById('email').value = u.email || '';
            document.getElementById('password').value = ''; // keep blank
            document.getElementById('mobile').value = u.mobile || '';
            document.getElementById('passcode').value = u.passcode || '';
            document.getElementById('card_id').value = u.card_id || '';
            document.getElementById('status').value = String(u.status ?? 1);

            // point form action to UPDATE
            const form = document.getElementById('user-form');
            form.action = saUserUpdateUrl.replace(':id', u.id);
        });
    });

    // Reset to ADD mode
    document.getElementById('resetUserForm').addEventListener('click', ()=>{
        const form = document.getElementById('user-form');
        form.reset();
        document.getElementById('formTitle').textContent = 'Add User (Super Admin)';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('method-override').value = 'POST';
        document.getElementById('formSubmitBtn').textContent = 'Add';
        document.getElementById('user_id').value = '';
        form.action = saUserCreateUrl;

        const ridSel = document.getElementById('restaurant_id');
        ridSel.disabled = false; 

        // clear roles dropdown
        const roleSel = document.getElementById('role');
        roleSel.innerHTML = '<option value="">Select Role</option>';
    });

    // Delete handler (simple confirm)
    document.querySelectorAll('.sa-user-delete').forEach(el=>{
        el.addEventListener('click', (e)=>{
            e.preventDefault();
            if(confirm('Delete this user?')){
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = el.dataset.url;
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Toggle passcode visibility on click
    document.querySelectorAll('td.passcode').forEach(td => {
        let masked = true;
        const original = td.dataset.passcode || '';
        td.textContent = '****';
        td.title = 'Click to show/hide';

        td.addEventListener('click', () => {
            if (!original) return;
            if (masked) { td.textContent = original; }
            else        { td.textContent = '****'; }
            masked = !masked;
        });
    });

</script>

<script src="{{ asset('assets/js/partial/card_swipe.js') }}"></script>
@endsection
