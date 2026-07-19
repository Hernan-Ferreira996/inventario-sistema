<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreCaja extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'cierres_caja';
    protected $guarded = [];
    protected $casts = [
        'fecha' => 'date',
        'saldo_inicial' => 'decimal:2',
        'total_cobrado' => 'decimal:2',
        'saldo_final' => 'decimal:2',
    ];

    public function caja(): BelongsTo { return $this->belongsTo(Caja::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class); }
}
