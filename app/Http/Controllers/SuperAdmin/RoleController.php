<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::withCount('users')->orderBy('name')->get();
        return view('super-admin.roles.index', compact('roles'));
    }
    public function create() { return view('super-admin.roles.create'); }
    public function store(Request $r) {
        $d = $r->validate([
            'code' => 'required|string|max:60|unique:roles,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);
        $d['is_system'] = false;
        $d['is_active'] = true;
        Role::create($d);
        return redirect()->route('super-admin.roles.index')->with('success', 'Role ditambahkan.');
    }
}
