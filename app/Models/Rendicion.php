<?php

namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rendicion extends Model
{
    use PerteneceAEmpresa;

    protected $table = 'rendiciones';
    protected $guarded = [];
    protected $casts = ['fecha' => 'date', 'monto_total' => 'decimal:2'];

    public function caja(): BelongsTo { return $this->belongsTo(Caja::class); }
    public function cobrador(): BelongsTo { return $this->belongsTo(User::class, 'cobrador_id'); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
    public function pagos(): HasMany { return $this->hasMany(Pago::class); }
}
