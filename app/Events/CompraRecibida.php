<?php

namespace App\Events;

use App\Models\RecepcionCompra;
use Illuminate\Foundation\Events\Dispatchable;

class CompraRecibida
{
    use Dispatchable;

    public function __construct(public RecepcionCompra $recepcion)
    {
    }
}
