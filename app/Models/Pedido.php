<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedido extends Model
{
    use HasFactory;

    protected $table = "pedidos";
    protected $fillable = ["cupon_id", "total", "nome_cliente", "frete", "status"];

    public function Cupom(): BelongsTo
    {
        return $this->belongsTo(Cupom::class, "cupon_id");
    }

    public function pedido_produtos(): HasMany
    {
        return $this->hasMany(PedidoProduto::class, "pedido_id");
    }

    // Accessor para exibir status legível
    public function getStatusTextoAttribute()
    {
        return match ((int) $this->status) {
            1 => 'Pago',
            2 => 'Cancelado',
            3 => 'Cupom Expirado',
            default => 'Pendente',
        };
    }
}
