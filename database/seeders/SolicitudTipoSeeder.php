<?php

namespace Database\Seeders;

use App\Models\SolicitudTipo;
use Illuminate\Database\Seeder;

class SolicitudTipoSeeder extends Seeder
{
    public function run(): void
    {
        // A) Credencial de Discapacidad
        SolicitudTipo::create([
            'codigo' => 'CRED_DISCAPACIDAD',
            'titulo' => 'Solicitud de Credencial de Discapacidad',
            'seccion' => 'SOCIAL',
            'descripcion' => 'Solicitud para obtener credencial de discapacidad municipal',
            'requisitos_json' => [
                'Fotocopia de Carnet',
                'Documentación médica'
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Documentos', 'descripcion' => 'Adjuntar documentos requeridos'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // B) Patentes Comerciales, Profesionales e Industriales
        SolicitudTipo::create([
            'codigo' => 'PATENTES',
            'titulo' => 'Solicitudes de Patentes Comerciales, Profesionales e Industriales',
            'seccion' => 'TRÁNSITO Y RENTAS',
            'descripcion' => 'Solicitud de patentes comerciales, profesionales o industriales',
            'requisitos_json' => [
                'Datos del solicitante',
                'Detalle del trámite (alta/modificación/consulta u otro)'
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Detalle del Trámite', 'descripcion' => 'Información específica del trámite'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // C) Traslado de vehículos - Permiso de Circulación
        SolicitudTipo::create([
            'codigo' => 'TRASLADO_VEHICULO',
            'titulo' => 'Solicitud de traslado de vehículos - Permiso de Circulación',
            'seccion' => 'TRÁNSITO Y RENTAS',
            'descripcion' => 'Solicitud para traslado de vehículos y permiso de circulación',
            'requisitos_json' => [
                'No tener deuda en municipalidad de origen (declaración/validación)',
                'Permiso de circulación',
                'Padrón o inscripción',
                'SOAP',
                'Revisión técnica'
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Documentos', 'descripcion' => 'Adjuntar documentos requeridos'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // D) Movilización
        SolicitudTipo::create([
            'codigo' => 'MOVILIZACION',
            'titulo' => 'Solicitud de movilización',
            'seccion' => 'MOVILIZACIÓN',
            'descripcion' => 'Solicitud de movilización municipal',
            'requisitos_json' => [
                'Fotocopia de carnet',
                'Informe de consulta médica (solo datos NO sensibles)'
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Documentos', 'descripcion' => 'Adjuntar informe médico (NO incluir diagnósticos ni antecedentes sensibles)'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // E) Recintos municipales
        SolicitudTipo::create([
            'codigo' => 'RECINTOS_MUNICIPALES',
            'titulo' => 'Solicitud de recintos municipales',
            'seccion' => 'RECINTOS MUNICIPALES: SALONES, TEATRO, ESPACIO COMUNITARIO',
            'descripcion' => 'Solicitud para reservar salones, teatro o espacios comunitarios',
            'requisitos_json' => [
                'campos' => ['nombre', 'mail', 'telefono', 'direccion', 'recinto', 'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin'],
                'documentos_requeridos' => [],
                'requiere_adjuntos' => false,
                'usar_horarios_disponibles' => true,
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Selección de Recinto', 'descripcion' => 'Elegir recinto y fecha/horario'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // F) Recintos deportivos
        SolicitudTipo::create([
            'codigo' => 'RECINTOS_DEPORTIVOS',
            'titulo' => 'Solicitud de recintos deportivos',
            'seccion' => 'DEPORTES',
            'descripcion' => 'Solicitud para reservar recintos deportivos municipales',
            'requisitos_json' => [
                'campos' => ['nombre', 'mail', 'telefono', 'direccion', 'recinto', 'fecha_inicio', 'fecha_fin', 'hora_inicio', 'hora_fin'],
                'documentos_requeridos' => [],
                'requiere_adjuntos' => false,
                'usar_horarios_disponibles' => true,
            ],
            'etapas_json' => [
                ['nombre' => 'Identificación', 'descripcion' => 'Datos del solicitante'],
                ['nombre' => 'Selección de Recinto', 'descripcion' => 'Elegir recinto y fecha/horario'],
                ['nombre' => 'Confirmación', 'descripcion' => 'Revisar y enviar solicitud']
            ],
            'activo' => true,
        ]);

        // G) OIRS - Oficina de Informaciones, Reclamaciones y Sugerencias
        SolicitudTipo::create([
            'codigo' => 'OIRS',
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
        ]);
    }
}
