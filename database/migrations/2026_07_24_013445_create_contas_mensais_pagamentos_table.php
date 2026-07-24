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
        Schema::create('contas_mensais_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conta_mensal_id')->constrained('contas_mensais')->onDelete('cascade');
            $table->unsignedTinyInteger('competencia_mes');
            $table->unsignedSmallInteger('competencia_ano');
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->date('data_pagamento')->nullable();
            $table->enum('status', ['pendente', 'pago', 'atrasado'])->default('pendente');
            $table->timestamps();

            $table->unique(['conta_mensal_id', 'competencia_mes', 'competencia_ano'], 'conta_mensal_competencia_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_mensais_pagamentos');
    }
};
