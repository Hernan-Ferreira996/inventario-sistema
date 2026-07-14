<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpresaModulo extends Model
{
    protected $table = 'empresa_modulos';

    protected $fillable = ['empresa_id', 'modulo_id', 'habilitado', 'fecha_vencimiento', 'notas'];

    protected $casts = [
        'habilitado' => 'boolean',
        'fecha_vencimiento' => 'date',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(Modulo::class);
    }
}
