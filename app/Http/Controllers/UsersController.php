<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CustomRole;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:configuracion')->only('index');
    }

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function profile()
    {
        return view('profile.index');
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();

        // Validar los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed', // Solo si se quiere cambiar la contraseña
        ]);

        // Actualizar los datos
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Redirigir con un mensaje de éxito
        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    public function show()
    {
        $user = Auth::user(); // Obtiene el usuario autenticado
        return view('profile.show', compact('user')); // Pasa los datos a la vista
    }

    public function create()
    {
        return view('auth.register');
    }

    // Método para almacenar el nuevo usuario
    public function store(Request $request)
    {
        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Crear el nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirigir al usuario a la página de inicio o a donde desees
        return redirect()->route('users.index')->with('success', 'Usuario registrado con éxito.');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $roleUsers = $user->roles->pluck('id')->toArray();

        return view('users.rolesUser', compact('user', 'roles', 'roleUsers'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rolesId = $request->input('roles', []);

        $roles = Role::wherein('id', $rolesId)->pluck('name')->toArray();
        $user->syncRoles($roles);

        return redirect()->route('users.index')->with('success', 'El role se ha asignado correctamente');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuario eliminado con éxito.');
    }
}
