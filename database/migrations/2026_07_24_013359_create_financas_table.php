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
        Schema::create('financas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->foreignId('cartao_id')->nullable()->constrained('cartoes')->nullOnDelete();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['receita', 'despesa'])->default('despesa');
            $table->enum('forma_pagamento', ['debito', 'credito', 'pix', 'dinheiro', 'boleto', 'transferencia']);
            $table->date('data_compra');
            $table->unsignedInteger('parcelas')->default(1);
            $table->unsignedInteger('parcela_atual')->default(1);
            $table->boolean('recorrente')->default(false);
            $table->enum('status', ['pendente', 'pago'])->default('pago');
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financas');
    }
};
