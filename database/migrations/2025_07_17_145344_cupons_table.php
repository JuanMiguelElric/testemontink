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
        Schema::create('cupons', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Código do cupom
            $table->decimal('desconto', 5, 2); // Porcentagem de desconto
            $table->decimal('valor_minimo', 10, 2)->default(0); // Valor mínimo do carrinho para aplicar
            $table->date('validade'); // Data de validade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupons');
    }
};
