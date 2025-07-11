<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\Title;
use App\Models\Shift;
use App\Models\Status;
use App\Models\Permission;
use App\Models\Department;

use App\Http\Resources\UserResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\TitleResource;
use App\Http\Resources\ShiftResource;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\PermissionResource;

use App\Services\ActionsLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // check if the user has the permission to view users
        if (!auth()->user()->hasPermission('users_menu')) {
            abort(403, 'Unauthorized action.');
        }

        if($request->wantsJson()) {
            $users = User::query()
                ->orderBy('active', 'desc')
                // ->orderBy('shift_id')
                ->orderBy('department_id')
                ->orderBy('title_id')
                ->with('title')
                ->with('department')
                ->with('roles')
                ->with('directPermissions')
                ->when($request->name, function ($query) use ($request) {
                    $query->where('name_ar', 'like', '%' . $request->name . '%')
                        ->orWhere('name_en', 'like', '%' . $request->name . '%');
                })
                ->when($request->username, function ($query) use ($request) {
                    $query->where('username', $request->username);
                })
                ->when($request->permission_ids, function ($query) use ($request) {
                    $query->whereHas('directPermissions', function ($query) use ($request) {
                        $query->whereIn('id', $request->permission_ids);
                    })->orWhereHas('roles.permissions', function ($query) use ($request) {
                        $query->whereIn('id', $request->permission_ids);
                    });
                })
                ->when($request->status != '', function ($q) use ($request) {
                    $q->where('active', $request->status == '1' ? true : false);
                })
                ->when($request->title_ids, function ($query) use ($request) {
                    $query->whereIn('title_id', $request->title_ids);
                })
                ->when($request->department_ids, function ($query) use ($request) {
                    $query->whereIn('department_id', $request->department_ids);
                })
                ->paginate(1500);
            return UserResource::collection($users);
        }

        $permissions = PermissionResource::collection(Permission::all());
        $roles = RoleResource::collection(Role::all());
        $titles = TitleResource::collection(Title::all());
        $departments = DepartmentResource::collection(Department::all());
        $shifts = ShiftResource::collection(Shift::all());

        return view('pages.users.index', compact('permissions', 'roles', 'titles', 'departments', 'shifts'));
    }

    public function changeStatus(User $user, Request $request)
    {
        if (!auth()->user()->hasPermission('users_change_status')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_change_user_status')], 403);
        }

        // check if user has in progress orders
        $inProgressOrders = Order::where('technician_id', $user->id)->whereNotIn('status_id', [Status::COMPLETED])->count();
        if ($inProgressOrders > 0) {
            return response()->json(['error' => __('messages.user_has_in_progress_orders')], 403);
        }

        $old_user = $user->toArray();
        $user->update(['active' => $request->status]);
        ActionsLog::logAction('User', 'Change Status', $user->id, 'User status changed successfully', $user->toArray(), $old_user);
        return response()->json(['message' => 'User status changed successfully']);
    }

    public function store(Request $request)
    {
        // check if the user has the permission to create users
        if (!auth()->user()->hasPermission('users_create')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }


        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|max:255',
            'title_id' => 'required|exists:titles,id',
            'department_id' => 'required|exists:departments,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'active' => 'required|boolean',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $validatedRoles = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
        ]);

        $validatedDirectPermissions = $request->validate([
            'directPermissions' => 'nullable|array',
            'directPermissions.*' => 'nullable|exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create($validatedData);
            $user->roles()->sync($validatedRoles['roles']);
            $user->directPermissions()->sync($validatedDirectPermissions['directPermissions']);
            DB::commit();
            $user->clearPermissionCache();
            ActionsLog::logAction('User', 'Create', $user->id, 'User created successfully', $user->load('roles')->load('directPermissions')->toArray(), []);
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(User $user, Request $request){

        // check if the user has the permission to update users
        if (!auth()->user()->hasPermission('users_edit')) {
            return response()->json(['error' => __('messages.you_dont_have_permission_to_edit_user')], 403);
        }

        // check if department is not the same as the user's department
        if($request->department_id != $user->department_id) {
            // check if user has in progress orders
            $inProgressOrders = Order::where('technician_id', $user->id)->whereNotIn('status_id', [Status::COMPLETED])->count();
            if ($inProgressOrders > 0) {
                return response()->json(['error' => __('messages.user_has_in_progress_orders')], 403);
            }
        }

        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'title_id' => 'required|exists:titles,id',
            'department_id' => 'required|exists:departments,id',
            'shift_id' => 'nullable|exists:shifts,id',
            'active' => 'required|boolean',
        ]);

        if($request->password) {
            $validatedData['password'] = bcrypt($request->password);
        }

        $validatedRoles = $request->validate([
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
        ]);

        $validatedDirectPermissions = $request->validate([
            'directPermissions' => 'nullable|array',
            'directPermissions.*' => 'nullable|exists:permissions,id',
        ]);

        $old_user = $user->load('roles')->load('directPermissions')->toArray();
        DB::beginTransaction();
        try {
            $user->update($validatedData);
            $user->roles()->sync($validatedRoles['roles']);
            $user->directPermissions()->sync($validatedDirectPermissions['directPermissions']);
            DB::commit();
            $user->clearPermissionCache();
            ActionsLog::logAction('User', 'Update', $user->id, 'User updated successfully', $user->load('roles')->load('directPermissions')->toArray(), $old_user);
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $user){
        // check if the user has the permission to delete users
        if (!auth()->user()->hasPermission('users_delete')) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $old_user = $user->load('roles')->load('directPermissions')->toArray();
        DB::beginTransaction();
        try {
            $user->roles()->detach();
            $user->directPermissions()->detach();
            $user->delete();
            DB::commit();
            ActionsLog::logAction('User', 'Delete', $user->id, 'User deleted successfully',[], $old_user);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(User $user){
        $user->load('roles');
        $user->load('directPermissions');
        $user->load('title');
        $user->load('department');
        return new UserResource($user);
    }

    public function generateUsername(){
        $existingUsernames = User::pluck('username')->toArray();
        $nextAvailableUsername = collect(range(25, 10000))
            ->first(function($username) use ($existingUsernames) {
                return !in_array($username, $existingUsernames);
            });
        // return the next available username as string
        return response()->json(['username' => (string) $nextAvailableUsername]);
    }
}
