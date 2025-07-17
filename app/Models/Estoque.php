<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{
    use HasFactory;

    protected $table = "estoque";
    protected $fillable = ["produto_id", "quantidade"];

    public function produto():BelongsTo{
        return $this->belongsTo(Produto::class,"produto_id");
    }
 
}
