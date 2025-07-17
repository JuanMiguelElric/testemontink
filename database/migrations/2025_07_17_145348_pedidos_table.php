<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("cupon_id");
            $table->string("nome_cliente",255);
            $table->float("total",10,2);
            $table->json("frete");
            $table->integer("status");// 0=> pendente de pagamento 1=> pagamento efetuado 2=> não entregue 3 => entregue
            $table->timestamps();
            $table->foreign('cupon_id')->references('id')->on('cupons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
