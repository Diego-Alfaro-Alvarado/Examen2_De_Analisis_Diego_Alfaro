<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_reservas', function (Blueprint $table) {
            $table->foreignId('reserva_id')->constrained('reservas')->cascadeOnDelete();
            $table->foreignId('salida_id')->constrained('salidas');
            $table->unsignedInteger('cantidad_personas');
            $table->decimal('precio_unitario', 10, 2);
            $table->primary(['reserva_id', 'salida_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_reservas');
    }
};
