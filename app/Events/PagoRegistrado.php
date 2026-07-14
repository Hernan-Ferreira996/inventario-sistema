<?php

namespace App\Events;

use App\Models\Pago;
use Illuminate\Foundation\Events\Dispatchable;

class PagoRegistrado
{
    use Dispatchable;

    public function __construct(public Pago $pago)
    {
    }
}
