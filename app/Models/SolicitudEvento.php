<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEvento extends Model
{
    use HasFactory;

    protected $table = 'solicitud_eventos';

    protected $fillable = [
        'solicitud_id',
        'actor_user_id',
        'evento',
        'estado_nuevo',
        'comentario',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }
}
