<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    use PerteneceAEmpresa;
    protected $table = 'categorias';
    protected $guarded = [];

    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
}
