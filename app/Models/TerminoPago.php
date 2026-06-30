<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;

class TerminoPago extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'terminos_pago';
    protected $guarded = [];
}
