<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValorCampoPersonalizado extends Model
{
    protected $table = 'valores_campos_personalizados';

    protected $fillable = ['campo_id', 'valorable_type', 'valorable_id', 'valor'];

    public function campo(): BelongsTo
    {
        return $this->belongsTo(CampoPersonalizado::class, 'campo_id');
    }
}
