<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = $this->getPermissionsTree();
        return view('admin.roles-permissions', compact('roles', 'permissions'));
    }

    public function update(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permissions = $request->permissions ?? [];
        
        $role->syncPermissions($permissions);
        
        return redirect()->back()->with('success', 'Permisos actualizados exitosamente');
    }

    private function getPermissionsTree()
    {
        $permissions = Permission::all();
        $tree = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $this->addToTree($tree, $parts, $permission);
        }

        return $tree;
    }

    private function addToTree(&$tree, $parts, $permission)
    {
        $current = &$tree;
        foreach ($parts as $part) {
            if (!isset($current[$part])) {
                $current[$part] = [];
            }
            $current = &$current[$part];
        }
        $current['__permission'] = $permission;
    }

}
