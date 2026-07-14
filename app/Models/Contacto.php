<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contacto extends Model
{
    protected $table = 'contactos';

    protected $fillable = ['empresa_id', 'nombre', 'cargo', 'telefono', 'email', 'cumpleanos', 'es_principal'];

    protected $casts = [
        'cumpleanos' => 'date',
        'es_principal' => 'boolean',
    ];

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }
}
