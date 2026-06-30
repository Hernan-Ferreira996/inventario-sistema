<?php
namespace App\Models;

use App\Traits\PerteneceAEmpresa;
use Illuminate\Database\Eloquent\Model;
class MetodoPago extends Model
{
    use PerteneceAEmpresa;
    protected $table = "metodos_pago";
    protected $fillable = ["nombre","activo"];
    protected $casts = ["activo"=>"boolean"];
}
