<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cupom extends Model
{
    use HasFactory;

    protected $table = "cupons";
      protected $fillable = [
        'codigo', 'desconto', 'valor_minimo', 'validade'
    ];

        public function isValido($subtotal)
    {
        return now()->lte($this->validade) && $subtotal >= $this->valor_minimo;
    }

    public function pedidos():HasMany
    {
        return $this->hasMany(Pedido::class);
    }
}
