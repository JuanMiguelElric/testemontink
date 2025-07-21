<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $table = "produtos";
    protected $fillable =[
        "nome",
        "preco",
        "variacoes",
        "variacoestype"
    ];

    public function estoques():HasMany
    {
        return $this->hasMany(Estoque::class, "produto_id");
    }

    public function pedido_produtos():HasMany
    {
        return $this->hasMany(PedidoProduto::class, "produto_id");
    }

}
