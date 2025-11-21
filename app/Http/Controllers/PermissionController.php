<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function users(Request $request)
    {
        $users = User::query()->orderBy('name')->paginate(20);
        $roles = Role::orderBy('name')->get();
        return view('admin.permissions.users', compact('users', 'roles'));
    }

    public function syncUserRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ]);
        $user->syncRoles($data['roles'] ?? []);
        return back()->with('success', 'Papéis atualizados para ' . $user->name);
    }

    public function roles(Request $request)
    {
        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('admin.permissions.roles', compact('roles', 'permissions'));
    }

    public function syncRolePermissions(Request $request, Role $role)
    {
        $data = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);
        $role->syncPermissions($data['permissions'] ?? []);
        return back()->with('success', 'Permissões atualizadas para o papel ' . $role->name);
    }
}


