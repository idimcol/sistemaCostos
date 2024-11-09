<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permisos = Permission::all();
        return view('users.permisos', compact('permisos'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        return redirect()->back()->with('success', 'permiso creado con extito');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update([
            'name' => $request->name,
            'gard_name' => 'web'
        ]);
        return redirect()->route('permisos.index')->with('success', 'permiso actualizado con extito');;
    }

    public function destroy(Permission $permiso)
    {
        $permiso->delete();
        return redirect()->back()->with('success', 'permiso eliminado con extito');;
    }
}
