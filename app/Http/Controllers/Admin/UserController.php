<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(User $user)
    {
        $this->authorize('admin.viewAny');

        $list = $user::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->get();

        return Inertia::render('Admin/ManageUser/Index', [
            "users" => $list,
        ]);
    }

    public function permission()
    {
        $this->authorize('admin.viewAny');

        return Inertia::render('Admin/ManagePermission/Index', [
            'permissions' => Permission::all(),
        ]);
    }

    public function role()
    {
        $this->authorize('admin.viewAny');

        $roles = Role::with('permissions')->get();

        $permission = Permission::all();

        return Inertia::render('Admin/ManageRole/Index', [
            'roles' =>  $roles,
            'permissions' => $permission,
        ]);
    }

    public function changePermission ($id, Request $request){
        $user = User::findOrFail($id);

        $changePermission = $request->get('permission', []);

        foreach ($user->roles as $role){
            $role->permissions()->sync($changePermission[$role->id] ?? []);
            dd($role);
        }
    }

    public function editPermission($id, Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
        ]);

        Permission::findOrFail($id)->update([
            'name' => $request->get('permission_name')
        ]);
    }
}
