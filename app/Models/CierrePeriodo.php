<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierrePeriodo extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'cierres_periodo';
    protected $guarded = [];
    protected $casts = ['fecha_cierre' => 'date'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
