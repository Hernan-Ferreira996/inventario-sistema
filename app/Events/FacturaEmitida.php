<?php

namespace App\Events;

use App\Models\Factura;
use Illuminate\Foundation\Events\Dispatchable;

class FacturaEmitida
{
    use Dispatchable;

    public function __construct(public Factura $factura)
    {
    }
}
