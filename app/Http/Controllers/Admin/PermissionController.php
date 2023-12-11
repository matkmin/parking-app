<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function permission()
    {
        $this->authorize('admin.viewAny');

        return Inertia::render('Admin/ManagePermission/Index', [
            'permissions' => Permission::latest()->paginate(10)->withQueryString(),
        ]);
    }
    public function changePermission($roleID, Request $request)
    {
        $permissions = $request->get('permissions', []);

        Role::findOrFail($roleID)->permissions()->sync($permissions);
    }
    public function addNewPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $request->get('name')
        ]);
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

    public function deletePermissionName($id)
    {
        $permission = Permission::findOrFail($id);

        $permission->roles()->detach();

        $permission->delete();
    }
}
