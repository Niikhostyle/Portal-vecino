<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecintoReserva extends Model
{
    use HasFactory;

    protected $table = 'recinto_reservas';

    protected $fillable = [
        'recinto_id',
        'solicitud_id',
        'fecha_inicio',
        'hora_inicio',
        'fecha_fin',
        'hora_fin',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function recinto()
    {
        return $this->belongsTo(Recinto::class, 'recinto_id');
    }

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
}
