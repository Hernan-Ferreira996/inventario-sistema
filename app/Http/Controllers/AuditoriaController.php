<?php
namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;

class AuditoriaController extends Controller
{
    public function index()
    {
        $registros = Activity::with('causer')
            ->latest()
            ->paginate(30);

        return view('auditoria.index', compact('registros'));
    }
}
