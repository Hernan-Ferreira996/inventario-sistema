<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with("roles")->orderBy("name")->paginate(20);
        return view("usuarios.lista", compact("usuarios"));
    }

    public function create()
    {
        $grupos = Role::with("permissions")->orderBy("name")->get();
        return view("usuarios.crear", compact("grupos"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name"       => "required|string|max:150",
            "email"      => "required|email|unique:users,email",
            "password"   => ["required", "confirmed", Rules\Password::defaults()],
            "empresa_id" => "nullable|exists:empresas,id",
            "grupos"     => "nullable|array",
            "grupos.*"   => "exists:roles,id",
        ]);

        $empresaId = $request->empresa_id;
        // Si no es super-admin, el usuario creado pertenece a la misma empresa del creador
        if (!auth()->user()->esSuperAdmin()) {
            $empresaId = auth()->user()->empresa_id;
        }

        $usuario = User::create([
            "name"       => $request->name,
            "email"      => $request->email,
            "password"   => Hash::make($request->password),
            "empresa_id" => $empresaId,
        ]);

        if ($request->filled("grupos")) {
            $roles = Role::whereIn("id", $request->grupos)->pluck("name")->toArray();
            $usuario->syncRoles($roles);
        }

        return redirect()->route("usuarios.index")->with("success", "Usuario creado correctamente.");
    }

    public function show(User $usuario)
    {
        $usuario->load("roles.permissions");
        $grupos = Role::with("permissions")->orderBy("name")->get();
        return view("usuarios.detalle", compact("usuario", "grupos"));
    }

    public function edit(User $usuario)
    {
        $grupos = Role::with("permissions")->orderBy("name")->get();
        $sucursales = $usuario->empresa_id
            ? \App\Models\Sucursal::where("empresa_id", $usuario->empresa_id)->orderBy("nombre")->get()
            : collect();
        $ubicaciones = $usuario->empresa_id
            ? \App\Models\Ubicacion::where("empresa_id", $usuario->empresa_id)->where("activo", true)
                ->with("sucursal")->orderBy("nombre")->get()->groupBy(fn($u) => $u->sucursal?->nombre ?? "Sin sucursal")
            : collect();
        return view("usuarios.editar", compact("usuario", "grupos", "sucursales", "ubicaciones"));
    }

    public function update(Request $request, User $usuario)
    {
        $request->validate([
            "name"   => "required|string|max:150",
            "email"  => "required|email|unique:users,email," . $usuario->id,
            "grupos" => "nullable|array",
            "grupos.*" => "exists:roles,id",
            "sucursales" => "nullable|array",
            "sucursales.*" => "exists:sucursales,id",
            "ubicaciones" => "nullable|array",
            "ubicaciones.*" => "exists:ubicaciones,id",
        ]);

        $usuario->update([
            "name"  => $request->name,
            "email" => $request->email,
        ]);

        if ($request->filled("password")) {
            $request->validate(["password" => ["confirmed", Rules\Password::defaults()]]);
            $usuario->update(["password" => Hash::make($request->password)]);
        }

        $roles = $request->filled("grupos")
            ? Role::whereIn("id", $request->grupos)->pluck("name")->toArray()
            : [];
        $usuario->syncRoles($roles);

        if ($usuario->empresa_id) {
            $usuario->sucursales()->sync($request->input("sucursales", []));
            $usuario->ubicaciones()->sync($request->input("ubicaciones", []));
        }

        return redirect()->route("usuarios.index")->with("success", "Usuario actualizado.");
    }

    public function destroy(User $usuario)
    {
        if (auth()->id() === $usuario->id) {
            return redirect()->route("usuarios.index")->with("error", "No puedes eliminar tu propio usuario.");
        }
        $usuario->delete();
        return redirect()->route("usuarios.index")->with("success", "Usuario eliminado.");
    }
}
