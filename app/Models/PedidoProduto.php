<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PedidoProduto extends Model
{
    use HasFactory;

    protected $table = "pedido_produto";

    protected $fillable =["pedido_id","produto_id","quantidade"];

    public function Pedido():BelongsTo
    {
        return $this->belongsTo(Pedido::class, "pedido_id");
    }

    public function Produto():BelongsTo
    {
        return $this->belongsTo(Produto::class, "produto_id");
    }
}
