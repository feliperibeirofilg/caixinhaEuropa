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
        Schema::create('caixinhas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('meta_valor', 10, 2);
            $table->json('quantidade')->nullable();
            $table->timestamps();
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId('caixinha_id')->nullable()->constrained('caixinhas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixinhas');
    }
};
