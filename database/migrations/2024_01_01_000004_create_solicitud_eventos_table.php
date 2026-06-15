<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->onDelete('cascade');
            $table->foreignId('actor_user_id')->constrained('users')->onDelete('restrict');
            $table->string('evento', 100);
            $table->string('estado_nuevo', 50)->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();
            
            $table->index('solicitud_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_eventos');
    }
};
