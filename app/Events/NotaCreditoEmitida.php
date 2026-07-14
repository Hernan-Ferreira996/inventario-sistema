<?php

namespace App\Events;

use App\Models\NotaCredito;
use Illuminate\Foundation\Events\Dispatchable;

class NotaCreditoEmitida
{
    use Dispatchable;

    public function __construct(public NotaCredito $notaCredito)
    {
    }
}
