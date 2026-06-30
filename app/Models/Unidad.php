<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'unidades';
    protected $guarded = [];
}
