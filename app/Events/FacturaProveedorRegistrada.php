<?php

namespace App\Events;

use App\Models\FacturaProveedor;
use Illuminate\Foundation\Events\Dispatchable;

class FacturaProveedorRegistrada
{
    use Dispatchable;

    public function __construct(public FacturaProveedor $facturaProveedor)
    {
    }
}
