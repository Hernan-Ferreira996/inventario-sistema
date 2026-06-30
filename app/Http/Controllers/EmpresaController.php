<?php
namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount("sucursales")->orderBy("nombre")->get();
        return view("empresas.lista", compact("empresas"));
    }

    public function create()
    {
        return view("empresas.crear");
    }

    public function store(Request $request)
    {
        $request->validate([
            "nombre"      => "required|string|max:200",
            "ruc"         => "required|string|max:20|unique:empresas,ruc",
            "dv"          => "required|string|max:2",
            "pais"        => "required|string|max:100",
            "moneda"      => "required|string|max:10",
            "simbolo"     => "required|string|max:6",
            "fact_establecimiento" => "required|string|max:3",
            "fact_punto_expedicion" => "required|string|max:3",
        ]);

        $empresa = Empresa::create($request->except("_token"));

        // Crear sucursal principal automáticamente
        $empresa->sucursales()->create([
            "codigo"    => $empresa->fact_establecimiento,
            "nombre"    => "Casa Matriz",
            "direccion" => $empresa->direccion,
            "ciudad"    => $empresa->ciudad,
            "telefono"  => $empresa->telefono,
            "principal" => true,
            "activo"    => true,
        ]);

        return redirect()->route("empresas.show", $empresa)
            ->with("success", "Empresa creada correctamente.");
    }

    public function show(Empresa $empresa)
    {
        $empresa->load("sucursales.depositos");
        $usuarios = \App\Models\User::where("empresa_id", $empresa->id)->get();
        return view("empresas.detalle", compact("empresa", "usuarios"));
    }

    public function edit(Empresa $empresa)
    {
        return view("empresas.editar", compact("empresa"));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            "nombre"  => "required|string|max:200",
            "ruc"     => "required|string|max:20|unique:empresas,ruc,{$empresa->id}",
            "dv"      => "required|string|max:2",
            "pais"    => "required|string|max:100",
            "moneda"  => "required|string|max:10",
            "simbolo" => "required|string|max:6",
        ]);

        $empresa->update($request->except("_token", "_method"));

        return redirect()->route("empresas.show", $empresa)->with("success", "Empresa actualizada.");
    }

    public function destroy(Empresa $empresa)
    {
        if ($empresa->usuarios()->count() > 0) {
            return redirect()->route("empresas.index")
                ->with("error", "No se puede eliminar: la empresa tiene usuarios asignados.");
        }
        $empresa->delete();
        return redirect()->route("empresas.index")->with("success", "Empresa eliminada.");
    }
}
