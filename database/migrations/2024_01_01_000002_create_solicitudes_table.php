<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 50)->unique();
            $table->foreignId('tipo_id')->constrained('solicitud_tipos')->onDelete('restrict');
            $table->foreignId('vecino_id')->constrained('users')->onDelete('restrict');
            $table->enum('estado', [
                'enviada',
                'en_revision_op',
                'derivada',
                'en_gestion',
                'respondida',
                'rechazada'
            ])->default('enviada');
            $table->foreignId('asignado_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('prioridad', ['baja', 'normal', 'alta', 'urgente'])->default('normal');
            $table->json('datos_json')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->text('respuesta')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();
            
            $table->index('estado');
            $table->index('vecino_id');
            $table->index('asignado_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
