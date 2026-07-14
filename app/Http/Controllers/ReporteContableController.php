<?php

namespace App\Http\Controllers;

use App\Models\CuentaContable;

class ReporteContableController extends Controller
{
    public function balanceComprobacion()
    {
        $filas = CuentaContable::where('imputable', true)->orderBy('codigo')->get()
            ->map(fn ($c) => [
                'cuenta' => $c,
                'debe'   => (float) $c->movimientos()->sum('debe'),
                'haber'  => (float) $c->movimientos()->sum('haber'),
                'saldo'  => $c->saldo,
            ])
            ->filter(fn ($f) => $f['debe'] > 0 || $f['haber'] > 0)
            ->values();

        return view('contabilidad.reportes.balance-comprobacion', compact('filas'));
    }

    public function estadoResultados()
    {
        $ingresos = CuentaContable::where('tipo', 'ingreso')->where('imputable', true)->get()
            ->map(fn ($c) => ['cuenta' => $c, 'saldo' => $c->saldo]);
        $gastos = CuentaContable::where('tipo', 'gasto')->where('imputable', true)->get()
            ->map(fn ($c) => ['cuenta' => $c, 'saldo' => $c->saldo]);

        $totalIngresos = $ingresos->sum('saldo');
        $totalGastos = $gastos->sum('saldo');
        $resultado = $totalIngresos - $totalGastos;

        return view('contabilidad.reportes.estado-resultados', compact('ingresos', 'gastos', 'totalIngresos', 'totalGastos', 'resultado'));
    }

    public function balanceGeneral()
    {
        $activos = CuentaContable::where('tipo', 'activo')->where('imputable', true)->get()
            ->map(fn ($c) => ['cuenta' => $c, 'saldo' => $c->saldo]);
        $pasivos = CuentaContable::where('tipo', 'pasivo')->where('imputable', true)->get()
            ->map(fn ($c) => ['cuenta' => $c, 'saldo' => $c->saldo]);
        $patrimonios = CuentaContable::where('tipo', 'patrimonio')->where('imputable', true)->get()
            ->map(fn ($c) => ['cuenta' => $c, 'saldo' => $c->saldo]);

        $ingresos = (float) CuentaContable::where('tipo', 'ingreso')->where('imputable', true)->get()->sum('saldo');
        $gastos = (float) CuentaContable::where('tipo', 'gasto')->where('imputable', true)->get()->sum('saldo');
        $resultadoEjercicio = $ingresos - $gastos;

        $totalActivo = $activos->sum('saldo');
        $totalPasivo = $pasivos->sum('saldo');
        $totalPatrimonio = $patrimonios->sum('saldo') + $resultadoEjercicio;

        return view('contabilidad.reportes.balance-general', compact(
            'activos', 'pasivos', 'patrimonios', 'totalActivo', 'totalPasivo', 'totalPatrimonio', 'resultadoEjercicio'
        ));
    }
}
