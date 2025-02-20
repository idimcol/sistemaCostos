<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:Configuracion')->only('index');
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

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $user->name->$request->name;
        $user->email->$request->email;
        $user->save;

        return redirect()->back()->with('success', 'Perfil actualizado con éxito');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $repeatPassword = '';
        $notification = '';

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed|min:8',
        ]);

        if (Hash::check($request->password, $user->password)) {
            $repeatPassword = 'la nueva contraseña no puede ser igual a la contraseña actual';
        }else{
            $notification = 'Contraseña actualizada con éxito';
        }

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return back()->with(compact('repeatPassword', 'notification'));
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
