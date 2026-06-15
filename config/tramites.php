<?php

return [
    /*
    | Campos que se pueden activar por trámite. Las claves son el name en datos[].
    */
    'campos' => [
        'nombre' => ['label' => 'Nombre completo', 'tipo' => 'text', 'required_default' => true],
        'mail' => ['label' => 'Correo electrónico', 'tipo' => 'email', 'required_default' => true],
        'telefono' => ['label' => 'Teléfono', 'tipo' => 'tel', 'required_default' => true],
        'direccion' => ['label' => 'Dirección', 'tipo' => 'text', 'required_default' => true],
        'recinto' => ['label' => 'Recinto', 'tipo' => 'recinto', 'required_default' => true],
        'fecha_inicio' => ['label' => 'Fecha inicio', 'tipo' => 'date', 'required_default' => true],
        'fecha_fin' => ['label' => 'Fecha fin', 'tipo' => 'date', 'required_default' => true],
        'hora_inicio' => ['label' => 'Hora inicio', 'tipo' => 'time', 'required_default' => true],
        'hora_fin' => ['label' => 'Hora fin', 'tipo' => 'time', 'required_default' => true],
        'detalle' => ['label' => 'Detalle o comentarios', 'tipo' => 'textarea', 'required_default' => false],
    ],

    'requiere_adjuntos_key' => 'requiere_adjuntos',
    'documentos_requeridos_key' => 'documentos_requeridos',
];
