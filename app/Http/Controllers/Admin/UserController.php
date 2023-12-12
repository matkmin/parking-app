<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(User $user, Role $role)
    {
        $this->authorize('admin.viewAny');

        $list = $user::query()
            ->with('roles')
            ->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'admin');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $roles = $role::all();

        return Inertia::render('Admin/ManageUser/Index', [
            'users' => $list,
            'roles' => $roles,
        ]);
    }
    public function updateUserName(Request $request, $userID)
    {
        User::findOrFail($userID)->update([
            'name' => $request->get('name', false)
        ]);
    }
    public function updateUserEmail(Request $request, $userID)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $userID,
        ]);
        User::findOrFail($userID)->update([
            'email' => $request->get('email', false)
        ]);
    }

    public function deleteUser($userID)
    {
        $user = User::findOrFail($userID);

        $user->uploadedDocuments()->delete();

        $user->roles()->detach();

        $user->delete();
    }
}
