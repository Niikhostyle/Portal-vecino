<?php

use App\Models\SolicitudTipo;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        SolicitudTipo::updateOrCreate(
            ['codigo' => 'OIRS'],
            [
                'titulo' => 'OIRS - Oficina de Informaciones, Reclamaciones y Sugerencias',
                'seccion' => 'OIRS',
                'descripcion' => 'Canal oficial para solicitar información, presentar reclamos o enviar sugerencias a la municipalidad.',
                'requisitos_json' => [
                    'campos' => ['nombre', 'mail', 'telefono', 'direccion', 'detalle'],
                    'documentos_requeridos' => [],
                    'requiere_adjuntos' => false,
                    'usar_horarios_disponibles' => false,
                    'es_oirs' => true,
                ],
                'etapas_json' => [
                    ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                    ['nombre' => 'Detalle OIRS', 'descripcion' => 'Tipo, asunto y descripción'],
                    ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud'],
                ],
                'activo' => true,
            ]
        );
    }

    public function down(): void
    {
        SolicitudTipo::where('codigo', 'OIRS')->delete();
    }
};
