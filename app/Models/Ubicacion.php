<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'ubicaciones';
    protected $guarded = [];
}
