<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTipo extends Model
{
    use HasFactory;

    protected $table = 'solicitud_tipos';

    protected $fillable = [
        'codigo',
        'titulo',
        'seccion',
        'descripcion',
        'requisitos_json',
        'etapas_json',
        'activo',
    ];

    protected $casts = [
        'requisitos_json' => 'array',
        'etapas_json' => 'array',
        'activo' => 'boolean',
    ];

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'tipo_id');
    }

    public function scopeHabilitados($query)
    {
        return $query->where('activo', true);
    }

    public static function findHabilitadoOrFail(int|string $id): self
    {
        $tipo = static::where('id', $id)->where('activo', true)->first();

        if (! $tipo) {
            abort(404, 'Este trámite no está disponible en este momento.');
        }

        return $tipo;
    }

    public function esOirs(): bool
    {
        if ($this->codigo === 'OIRS') {
            return true;
        }

        $req = $this->requisitos_json;

        return is_array($req) && ! empty($req['es_oirs']);
    }

    /** Reglas de validación adicionales al crear una solicitud de este tipo. */
    public function reglasValidacionStore(): array
    {
        if ($this->esOirs()) {
            return [
                'datos.tipo_oirs' => 'required|in:informacion,reclamo,sugerencia',
                'datos.asunto' => 'required|string|min:5|max:255',
                'datos.detalle' => 'required|string|min:20',
            ];
        }

        return [];
    }

    /**
     * Devuelve los campos habilitados para este tipo (requisitos aplicados).
     * requisitos_json puede ser: array de strings (legacy = solo documentos) o array con 'campos' => [...].
     */
    public function getCamposRequisitosAttribute(): array
    {
        $req = $this->requisitos_json;
        if (empty($req)) {
            return [];
        }
        if (isset($req['campos']) && is_array($req['campos'])) {
            return $req['campos'];
        }
        return [];
    }

    /**
     * Documentos requeridos (lista de nombres para mostrar al ciudadano).
     */
    public function getDocumentosRequeridosAttribute(): array
    {
        $req = $this->requisitos_json;
        if (empty($req)) {
            return [];
        }
        if (isset($req['documentos_requeridos']) && is_array($req['documentos_requeridos'])) {
            return array_values(array_filter($req['documentos_requeridos']));
        }
        // Legacy: requisitos_json era un array de strings
        if (array_values($req) === $req && is_array($req)) {
            return array_values(array_filter($req));
        }
        return [];
    }

    /** Si el trámite requiere paso de adjuntos. */
    public function getRequiereAdjuntosAttribute(): bool
    {
        $req = $this->requisitos_json;
        if (empty($req) || ! is_array($req)) {
            return true;
        }
        return (bool) ($req['requiere_adjuntos'] ?? true);
    }

    /** Si usa fecha + horarios disponibles (como recintos deportivos). */
    public function getUsarHorariosDisponiblesAttribute(): bool
    {
        $req = $this->requisitos_json;
        if (empty($req) || ! is_array($req)) {
            return false;
        }
        return (bool) ($req['usar_horarios_disponibles'] ?? false);
    }

    /** IDs de recintos asignados a este tipo (si está configurado). */
    public function getRecintosIdsAttribute(): array
    {
        $req = $this->requisitos_json;
        if (empty($req) || ! is_array($req) || ! isset($req['recintos_ids'])) {
            return [];
        }
        return array_values(array_map('intval', (array) $req['recintos_ids']));
    }
}
