<?php
 
 /**
  * UserController Class File Doc Comment  
  * 
  * PHP version 8.1+
  * 
  * @category UserController
  * @package  UserController
  * @author   Mukhpal Singh
  * @license  https://opensource.org/licenses/MIT MIT License
  * @link     http://budzee.com
  */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{EmployeePayroll, EmployeeW4Form, User,Restaurant, Shift};
use App\Models\Role;
use DB;
use Hash;
use Session;
use Auth,Lang;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Constructor
     */
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a list of user.
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $where['restaurant_id'] = $user->restaurant_id;

            // Set default pagination parameters
            $limit = $request->input('limit', 10); // Default 10 records per page
            $page = $request->input('page', 1);    // Default to page 1
            $search = $request->input('search');

            // Fetch users with optional search filter
            $query = User::where($where);

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('mobile', 'LIKE', "%{$search}%");
                });
            }

            // Fetch paginated users
            $users = $query->paginate($limit, ['*'], 'page', $page);
            $roles= Role::where(['restaurant_id'=>$user->restaurant->id,'status'=>true])->where('id', '!=', 1)->get();
            // Fetch shifts
            // $shifts = Shift::where($where)->get();

            return view('users', compact('users', 'roles'));
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request manually
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'role' => 'required|array',
            'role.*' => 'string|exists:roles,id',
            'email' => [
                'nullable',
                'email',
                Rule::requiredIf(function () use ($request) {
                    return !empty($request->password);
                }),
                Rule::unique('users')->where(fn ($query) => $query->whereNotNull('email')),
            ],
            'password' => [
                'nullable',
                'min:8',
                Rule::requiredIf(function () use ($request) {
                    return !empty($request->email);
                }),
            ],
            'passcode' => [
                'required',
                'digits_between:1,30',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->where(fn ($query) => $query->where('restaurant_id', Auth::user()->restaurant_id)),
            ],
            'ssn_number' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'mobile' => 'nullable|string',
            'card_id' => ['nullable', 'string', 'max:255',
                Rule::unique('users')->where(fn ($query) => $query->where('restaurant_id', Auth::user()->restaurant_id)),
            ],
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $loginUser = Auth::user();
            $user = new User();
            $user->name = $request->name;
            $user->mobile = $request->mobile ?? null;
            $user->email = $request->email ?? null;
            $user->password = $request->password ? bcrypt($request->password) : null;
            $user->status = $request->status ?? 1;
            $user->user_type = 2;
            $user->restaurant_id = $loginUser->restaurant->id;
            $user->created_by = $loginUser->id;
            $user->passcode = $request->passcode;
            $user->card_id = $request->card_id ?? null;
            $user->ssn_number = $request->ssn_number ?? null;

            $roles = Role::whereIn('id', $request->role)->where('restaurant_id', $loginUser->restaurant->id)->get();

            if (!empty($request->image)) {
                $imageName = str_replace(' ', '-', strtolower($request->name)) . '-' . time() . '.' . $request->image->extension();
                $request->image->move(config('global.profile_image_folder'), $imageName);
                $user->image = $imageName;
            }

            $user->application_type = $roles->first()->role_category ?? null;
            $user->save();
            $user->assignRole($roles->pluck('id')->toArray());

            return response()->json(['success' => true, 'userId' => $user->id, 'message' => 'User created successfully.']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Something went wrong! Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $loginUser = Auth::user();
            $user = User::with('roles')->find($id);

            $applicationType = $user->application_type;
            $roles = Role::where('restaurant_id',$loginUser->restaurant_id)->where(['status'=>true])->get();
            $userRoleIds = $user->roles->pluck('id')->toArray();
            return response()->json(['status'=>'success', 'msg'=>Lang::get('message.data'),'data'=>$user,'roles'=>$roles,'userRoles'=>$userRoleIds]);
            
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string',
            'passcode' => [
                'required',
                'digits_between:1,30',
                'regex:/^[0-9]+$/',
                Rule::unique('users')->ignore($id)->where(fn ($query) => $query->where('restaurant_id', Auth::user()->restaurant_id)),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::requiredIf(function () use ($request) {
                    return !empty($request->password);
                }),
                Rule::unique('users')->ignore($id)->where(fn ($query) => $query->whereNotNull('email')),
            ],
            'password' => [
                'nullable',
                'min:8',
            ],
            'ssn_number' => 'nullable|string|max:255',
            'role' => 'required|array',
            'role.*' => 'string|exists:roles,id',
            'card_id' => [
                'nullable', 'string', 'max:255',
                Rule::unique('users')->ignore($id)->where(fn ($query) => $query->where('restaurant_id', Auth::user()->restaurant_id)),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $loginUser = Auth::user();

            // Update fields
            $user->name = $request->name;
            $user->mobile = $request->mobile ?? null;
            $user->email = $request->email ?? null;
            $user->status = $request->status ?? 1;
            $user->passcode = $request->passcode;
            $user->updated_by = $loginUser->id;
            $user->card_id = $request->card_id ?? null;
            $user->ssn_number = $request->ssn_number ?? null;

            if (!empty($request->password)) {
                $user->password = bcrypt($request->password);
            }

            $roles = Role::whereIn('id', $request->role)->where('restaurant_id', $loginUser->restaurant->id)->get();

            if (count($roles)) {
                $user->application_type = $roles->first()->role_category ?? null;
                $user->syncRoles($roles->pluck('id')->toArray());
            }
            // $role = Role::where(['id' => $request->role, 'restaurant_id' => $loginUser->restaurant->id])->first();


            if (!empty($request->image)) {
                $imageName = str_replace(' ', '-', strtolower($request->name)) . '-' . time() . '.' . $request->image->extension();
                $request->image->move(config('global.profile_image_folder'), $imageName);
                $user->image = $imageName;
            }

            $user->save();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'userId' => $user->id, 'message' => 'User updated successfully.']);
            }

            return redirect()->route('user-list')->with('success', 'User updated successfully.');
        } catch (\Throwable $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Something went wrong! Please try again.'], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Something went wrong! Please try again.']);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $user=  User::find($id)->delete();
            return redirect()->route('user-list')->with('success', Lang::get('message.delete'));
        } catch (Throwable $e) {
            report($e);
            return redirect()->back()->withErrors($e->getMessage());
        }
        
    }

    public function checkPasscode(Request $request)
    {
        $restaurantId   =   Auth::user()->restaurant_id;
        $userId = $request->user_id;
        $passcode = $request->passcode;

        $query = User::where(['passcode' => $passcode, 'restaurant_id'  => $restaurantId]);

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $user = $query->first();

        if ($user) {
            return response()->json(['isUnique' => false, 'user' => ['name' => $user->name]]);
        } else {
            return response()->json(['isUnique' => true]);
        }
    }



    public function checkCardId(Request $request)
    {
        $exists = User::where('card_id', $request->cardId)->exists();
        return response()->json(['isUnique' => !$exists]);
    }

    public function payrolls($userId)
    {
        $user = User::find($userId);
        $roles = $user->roles()->get(['id', 'name']);
        $payroles = [];

        EmployeePayroll::where('user_id', $userId)->get(['role_id', 'payroll_amount', 'overtime_amount', 'overtime_hours_after'])
        ->map(function ($payroll) use (&$payroles) {
            $payroles[$payroll->role_id] = [
                'payroll_amount' => $payroll->payroll_amount,
                'overtime_amount' => $payroll->overtime_amount,
                'overtime_hours_after' => $payroll->overtime_hours_after,
            ];
        })->toArray();

        return response()->json(['status' => 'success', 'data' => ['user' => $user, 'roles' => $roles, 'payrolls' => $payroles]]);
    }

    public function savePayroll($userId, Request $request) {
        foreach ($request->roles as $roleId => $amounts) {
            EmployeePayroll::updateOrCreate([
                'user_id' => $userId,
                'role_id' => $roleId
            ], [
                'payroll_amount' => $amounts['payroll_amount'] ?? 0,
                'overtime_amount' => $amounts['overtime_amount'] ?? 0,
                'overtime_hours_after' => $amounts['overtime_hours_after'] ?? 0
            ]);
        }

        return redirect()->route('user-list')->with('success', 'Payroll updated successfully!');
    }

    public function listW4Forms($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $w4Forms = EmployeeW4Form::where('user_id', $userId)
                ->orderBy('effective_from', 'desc')
                ->paginate(10);

            return view('restaurant.w4Forms.index', compact('user', 'w4Forms'));
        } catch (\Throwable $e) {
            return redirect()->route('user-list')->withErrors(['error' => 'Something went wrong!']);
        }
    }

    public function showW4Form($userId, $formId)
    {
        try {
            $user = User::findOrFail($userId);
            $w4Form = EmployeeW4Form::where('user_id', $userId)
                ->where('id', $formId)
                ->firstOrFail();

            return view('restaurant.w4Forms.show', compact('user', 'w4Form'));
        } catch (\Throwable $e) {
            return redirect()->route('user-w4-forms', ['id' => $userId])
                ->withErrors(['error' => 'W4 form not found!']);
        }
    }

    public function downloadW4Document($userId, $formId)
    {
        try {
            $user = User::findOrFail($userId);
            $w4Form = EmployeeW4Form::where('user_id', $userId)
                ->where('id', $formId)
                ->firstOrFail();

            if (!$w4Form->w4_payload || !isset($w4Form->w4_payload['document_path'])) {
                return redirect()->back()->withErrors(['error' => 'No document found for this W4 form.']);
            }

            $filePath = public_path($w4Form->w4_payload['document_path']);

            if (!file_exists($filePath)) {
                return redirect()->back()->withErrors(['error' => 'Document file not found.']);
            }

            $fileName = $w4Form->w4_payload['document_name'] ?? 'w4-form-' . $user->name . '-' . $w4Form->effective_from->format('Y-m-d') . '.' . pathinfo($filePath, PATHINFO_EXTENSION);

            return response()->download($filePath, $fileName);
        } catch (\Throwable $e) {
            return redirect()->route('user-w4-forms', ['id' => $userId])
                ->withErrors(['error' => 'Failed to download document.']);
        }
    }

    public function toggleW4FormStatus($userId, $formId)
    {
        try {
            $user = User::findOrFail($userId);
            $w4Form = EmployeeW4Form::where('user_id', $userId)
                ->where('id', $formId)
                ->firstOrFail();

            // If activating this form, deactivate all other forms for this user
            if (!$w4Form->status) {
                EmployeeW4Form::where('user_id', $userId)
                    ->where('id', '!=', $formId)
                    ->update(['status' => false]);
            }

            $w4Form->status = !$w4Form->status;
            $w4Form->save();

            $statusText = $w4Form->status ? 'activated' : 'deactivated';
            $message = $w4Form->status 
                ? "W-4 form has been {$statusText} successfully! All other forms have been deactivated."
                : "W-4 form has been {$statusText} successfully!";
            
            return redirect()->back()->with('success', $message);
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update form status.']);
        }
    }

    public function createW4Form($userId)
    {
        $user = User::findOrFail($userId);
        $restaurantPayoutConfig = $user->restaurant->payoutConfig ?? [];

        $w4Config = [
            'claim_amount_under_17' => $restaurantPayoutConfig['claim_amount_under_17'] ?? 0,
            'claim_amount_other' => $restaurantPayoutConfig['claim_amount_other'] ?? 0
        ];

        return view('restaurant.w4Forms.create', compact('user', 'w4Config'));
    }

    public function saveW4Form($userId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'filing_status' => 'required|in:single,married_joint,head_of_household',
            'multiple_jobs' => 'nullable|boolean',
            'dependents_under_17' => 'nullable|integer|min:0',
            'dependents_other' => 'nullable|integer|min:0',
            'dependents_credit_amount' => 'nullable|numeric|min:0',
            'other_income' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'extra_withholding' => 'nullable|numeric|min:0',
            'signed_at' => 'required|date',
            'effective_from' => 'required|date',
            'w4_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::findOrFail($userId);

            // Handle file upload
            $w4Payload = [];
            if ($request->hasFile('w4_document')) {
                $file = $request->file('w4_document');
                $fileName = 'w4-' . $user->id . '-' . time() . '.' . $file->extension();
                $w4Payload['document_path'] = 'w4_documents/' . $fileName;
                $w4Payload['document_name'] = $file->getClientOriginalName();
                $w4Payload['uploaded_at'] = now();
            }

            // Create W4 form record
            $w4Form = new EmployeeW4Form();
            $w4Form->restaurant_id = $user->restaurant_id;
            $w4Form->user_id = $userId;
            $w4Form->filing_status = $request->filing_status;
            $w4Form->multiple_jobs = $request->has('multiple_jobs') ? true : false;
            $w4Form->dependents_under_17 = $request->dependents_under_17 ?? 0;
            $w4Form->dependents_other = $request->dependents_other ?? 0;
            $w4Form->dependents_credit_amount = $request->dependents_credit_amount ?? 0;
            $w4Form->other_income = $request->other_income ?? 0;
            $w4Form->deductions = $request->deductions ?? 0;
            $w4Form->extra_withholding = $request->extra_withholding ?? 0;
            $w4Form->signed_at = $request->signed_at;
            $w4Form->effective_from = $request->effective_from;
            $w4Form->w4_payload = !empty($w4Payload) ? $w4Payload : null;
            $w4Form->save();

            return redirect()->route('user-w4-forms', ['id' => $userId])->with('success', 'W-4 Form saved successfully!');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong! ' . $e->getMessage()])->withInput();
        }
    }

}
