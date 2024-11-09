<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('users.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        
        return back()->with('success', 'role creado exitosamente');
    }

    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $permisos = Permission::all();
        $rolePermisos = $role->permissions->pluck('id')->toArray();
        return view('users.rolepermisos', compact('role', 'permisos', 'rolePermisos'));
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('permissions.show', compact('role'));
    }

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $permisosId = $request->input('permissions', []);

        $permisos = Permission::whereIn('id', $permisosId)->pluck('name')->toArray();

        $role->syncPermissions($permisos);
        return redirect()->route('roles.index')->with('success', 'los permisos se han asignado correctamente al role');
    }

    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        $role->permissions()->detach();
        $role->users()->detach();
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'el rol fue eliminado exitosamente');
    }
}
