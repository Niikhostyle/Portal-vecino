<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudAdjunto extends Model
{
    use HasFactory;

    protected $table = 'solicitud_adjuntos';

    protected $fillable = [
        'solicitud_id',
        'filename',
        'path',
        'mime',
        'size',
        'uploaded_by',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
