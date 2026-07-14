<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\DocumentoAdjunto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoAdjuntoController extends Controller
{
    public function storeCliente(Request $request, Cliente $cliente)
    {
        return $this->guardar($request, $cliente);
    }

    public function storeProveedor(Request $request, Proveedor $proveedor)
    {
        return $this->guardar($request, $proveedor);
    }

    private function guardar(Request $request, $entidad)
    {
        $request->validate(['archivo' => 'required|file|max:10240']);

        $archivo = $request->file('archivo');
        $carpeta = 'adjuntos/' . $entidad->empresa_id . '/' . class_basename($entidad) . '/' . $entidad->id;
        $ruta = $archivo->store($carpeta, 'local');

        $entidad->documentosAdjuntos()->create([
            'empresa_id'     => $entidad->empresa_id,
            'usuario_id'     => auth()->id(),
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'ruta'           => $ruta,
            'tipo_mime'      => $archivo->getClientMimeType(),
            'tamano'         => $archivo->getSize(),
        ]);

        return back()->with('success', 'Documento adjuntado.');
    }

    public function download(DocumentoAdjunto $documentoAdjunto)
    {
        abort_unless($documentoAdjunto->empresa_id === auth()->user()->empresa_id, 403);
        return Storage::disk('local')->download($documentoAdjunto->ruta, $documentoAdjunto->nombre_archivo);
    }

    public function destroy(DocumentoAdjunto $documentoAdjunto)
    {
        abort_unless($documentoAdjunto->empresa_id === auth()->user()->empresa_id, 403);
        Storage::disk('local')->delete($documentoAdjunto->ruta);
        $documentoAdjunto->delete();
        return back()->with('success', 'Documento eliminado.');
    }
}
