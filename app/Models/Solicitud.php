<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'folio',
        'tipo_id',
        'vecino_id',
        'estado',
        'asignado_user_id',
        'prioridad',
        'datos_json',
        'motivo_rechazo',
        'respuesta',
        'fecha_respuesta',
    ];

    protected $casts = [
        'datos_json' => 'array',
        'fecha_respuesta' => 'datetime',
    ];

    public function tipo()
    {
        return $this->belongsTo(SolicitudTipo::class, 'tipo_id');
    }

    public function vecino()
    {
        return $this->belongsTo(User::class, 'vecino_id');
    }

    public function asignado()
    {
        return $this->belongsTo(User::class, 'asignado_user_id');
    }

    public function adjuntos()
    {
        return $this->hasMany(SolicitudAdjunto::class, 'solicitud_id');
    }

    public function eventos()
    {
        return $this->hasMany(SolicitudEvento::class, 'solicitud_id')->orderBy('created_at', 'desc');
    }

    public function reservaRecinto()
    {
        return $this->hasOne(RecintoReserva::class, 'solicitud_id');
    }

    /** Correo del vecino para notificaciones (prioriza el ingresado en la solicitud). */
    public function emailNotificacionVecino(): ?string
    {
        $datos = $this->datos_json ?? [];
        foreach (['email', 'mail'] as $campo) {
            $email = $datos[$campo] ?? null;
            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        $this->loadMissing('vecino');

        if ($this->vecino?->email && filter_var($this->vecino->email, FILTER_VALIDATE_EMAIL)) {
            return $this->vecino->email;
        }

        return null;
    }
}
