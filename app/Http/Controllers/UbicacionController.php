<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class UbicacionController extends Controller
{
    public function index() { return redirect()->route('dashboard'); }
    public function create() { return redirect()->route('dashboard'); }
    public function store(Request $r) { return redirect()->route('dashboard'); }
    public function show($id) { return redirect()->route('dashboard'); }
    public function edit($id) { return redirect()->route('dashboard'); }
    public function update(Request $r, $id) { return redirect()->route('dashboard'); }
    public function destroy($id) { return redirect()->route('dashboard'); }
}