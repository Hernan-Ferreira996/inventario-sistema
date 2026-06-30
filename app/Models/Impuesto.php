<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'impuestos';
    protected $guarded = [];
}
