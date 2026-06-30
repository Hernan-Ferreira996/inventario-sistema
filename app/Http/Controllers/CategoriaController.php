<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy("nombre")->paginate(20);
        $categorias->each(fn($c) => $c->productos_count = $c->productos()->count());
        return view("categorias.lista", compact("categorias"));
    }

    public function create() { return view("categorias.crear"); }

    public function store(Request $request)
    {
        $request->validate(["nombre" => "required|string|max:100|unique:categorias,nombre"]);
        Categoria::create($request->only("nombre", "descripcion"));
        return redirect()->route("categorias.index")->with("exito", "Categoria creada.");
    }

    public function show(Categoria $categoria) { return redirect()->route("categorias.index"); }

    public function edit(Categoria $categoria) { return view("categorias.editar", compact("categoria")); }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate(["nombre" => "required|string|max:100"]);
        $categoria->update($request->only("nombre", "descripcion", "activo"));
        return redirect()->route("categorias.index")->with("exito", "Categoria actualizada.");
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route("categorias.index")->with("exito", "Categoria eliminada.");
    }
}
