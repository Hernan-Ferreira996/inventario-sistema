<?php
namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    public function store(Request $request, Empresa $empresa)
    {
        $request->validate([
            "codigo"  => "required|string|max:10|unique:sucursales,codigo,NULL,id,empresa_id,{$empresa->id}",
            "nombre"  => "required|string|max:150",
            "ciudad"  => "nullable|string|max:100",
            "telefono"=> "nullable|string|max:30",
            "direccion"=> "nullable|string|max:255",
        ]);

        $empresa->sucursales()->create($request->except("_token"));

        return redirect()->route("empresas.show", $empresa)->with("success", "Sucursal creada.");
    }

    public function update(Request $request, Empresa $empresa, Sucursal $sucursal)
    {
        $request->validate([
            "nombre"   => "required|string|max:150",
            "ciudad"   => "nullable|string|max:100",
            "telefono" => "nullable|string|max:30",
            "direccion"=> "nullable|string|max:255",
            "activo"   => "boolean",
        ]);

        $sucursal->update($request->only(["nombre","ciudad","telefono","direccion","activo"]));

        return redirect()->route("empresas.show", $empresa)->with("success", "Sucursal actualizada.");
    }

    public function destroy(Empresa $empresa, Sucursal $sucursal)
    {
        if ($sucursal->depositos()->count() > 0) {
            return redirect()->route("empresas.show", $empresa)
                ->with("error", "No se puede eliminar la sucursal: tiene depósitos/ubicaciones asignados.");
        }
        $sucursal->delete();
        return redirect()->route("empresas.show", $empresa)->with("success", "Sucursal eliminada.");
    }
}
