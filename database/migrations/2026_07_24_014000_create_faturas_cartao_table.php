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
        Schema::create('faturas_cartao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cartao_id')->constrained('cartoes')->onDelete('cascade');
            $table->unsignedTinyInteger('mes_referencia');
            $table->unsignedSmallInteger('ano_referencia');
            $table->decimal('valor_total', 10, 2)->default(0);
            $table->date('data_fechamento')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['aberta', 'fechada', 'paga'])->default('aberta');
            $table->timestamps();

            $table->unique(['cartao_id', 'mes_referencia', 'ano_referencia'], 'cartao_fatura_competencia_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faturas_cartao');
    }
};
