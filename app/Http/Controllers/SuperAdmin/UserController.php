<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::with('roles')->orderBy('name')->paginate(20);
        return view('super-admin.users.index', compact('users'));
    }
    public function edit(User $user) {
        $user->load('roles');
        $roles = Role::orderBy('name')->get();
        return view('super-admin.users.edit', compact('user','roles'));
    }
    public function updateRoles(Request $r, User $user) {
        $r->validate(['roles' => 'array', 'roles.*' => 'exists:roles,id']);
        $roleIds = $r->input('roles', []);
        $sync = [];
        foreach ($roleIds as $id) {
            $sync[$id] = ['valid_from' => now()];
        }
        $user->roles()->sync($sync);
        return redirect()->route('super-admin.users.index')->with('success', 'Role diperbarui untuk '.$user->name);
    }
}
