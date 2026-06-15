<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $recintosMunicipales = [
            'campos' => ['nombre', 'mail', 'telefono', 'direccion', 'recinto', 'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin'],
            'documentos_requeridos' => [],
            'requiere_adjuntos' => false,
            'usar_horarios_disponibles' => true,
        ];
        $recintosDeportivos = [
            'campos' => ['nombre', 'mail', 'telefono', 'direccion', 'recinto', 'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin'],
            'documentos_requeridos' => [],
            'requiere_adjuntos' => false,
            'usar_horarios_disponibles' => true,
        ];

        DB::table('solicitud_tipos')
            ->where('codigo', 'RECINTOS_MUNICIPALES')
            ->update(['requisitos_json' => json_encode($recintosMunicipales)]);

        DB::table('solicitud_tipos')
            ->where('codigo', 'RECINTOS_DEPORTIVOS')
            ->update(['requisitos_json' => json_encode($recintosDeportivos)]);
    }

    public function down(): void
    {
        $oldMunicipales = [
            'documentos_requeridos' => [],
            'requiere_adjuntos' => false,
        ];
        $oldDeportivos = [
            'documentos_requeridos' => [],
            'requiere_adjuntos' => false,
        ];

        DB::table('solicitud_tipos')
            ->where('codigo', 'RECINTOS_MUNICIPALES')
            ->update(['requisitos_json' => json_encode($oldMunicipales)]);

        DB::table('solicitud_tipos')
            ->where('codigo', 'RECINTOS_DEPORTIVOS')
            ->update(['requisitos_json' => json_encode($oldDeportivos)]);
    }
};
