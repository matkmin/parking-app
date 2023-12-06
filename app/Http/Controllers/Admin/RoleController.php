<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
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
    public function addRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255'
        ]);

        Role::create([
            'name' => $request->get('name')
        ]);
    }

    public function changeRole()
    {

    }
    public function deleteRole($roleID)
    {
        $role = Role::findOrFail($roleID);

        $role->users()->detach();

        $role->permissions()->detach();

        $role->delete();
    }
}
