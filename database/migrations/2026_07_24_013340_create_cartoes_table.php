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
        Schema::create('cartoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('nome');
            $table->enum('tipo', ['credito', 'debito'])->default('credito');
            $table->string('bandeira')->nullable();
            $table->decimal('limite', 10, 2)->nullable();
            $table->unsignedTinyInteger('dia_fechamento')->nullable();
            $table->unsignedTinyInteger('dia_vencimento')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartoes');
    }
};
