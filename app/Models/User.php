<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'estado',
        'run',
        'dv',
        'claveunica_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function solicitudesComoVecino()
    {
        return $this->hasMany(Solicitud::class, 'vecino_id');
    }

    public function solicitudesAsignadas()
    {
        return $this->hasMany(Solicitud::class, 'asignado_user_id');
    }

    public function eventosCreados()
    {
        return $this->hasMany(SolicitudEvento::class, 'actor_user_id');
    }

    public function adjuntosSubidos()
    {
        return $this->hasMany(SolicitudAdjunto::class, 'uploaded_by');
    }

    public function isAdministrador()
    {
        return $this->rol === 'administrador';
    }

    public function isOficinaPartes()
    {
        return $this->rol === 'oficina_partes';
    }

    public function isFuncionario()
    {
        return $this->rol === 'funcionario';
    }

    public function isVecino()
    {
        return $this->rol === 'vecino';
    }

    /** RUN numérico desde Clave Única (incluye fallback legacy en claveunica_id). */
    public function runNormalizado(): ?string
    {
        if ($this->run && preg_match('/^\d{1,8}$/', (string) $this->run)) {
            return (string) $this->run;
        }

        if ($this->claveunica_id && preg_match('/^\d{1,8}$/', (string) $this->claveunica_id)) {
            return (string) $this->claveunica_id;
        }

        if ($this->claveunica_id) {
            $parsed = self::parseRutComponents((string) $this->claveunica_id);
            if ($parsed['run']) {
                return $parsed['run'];
            }
        }

        return null;
    }

    /** RUT formateado desde datos de Clave Única (ej: 12.345.678-9). */
    public function rutFormateado(): ?string
    {
        $run = $this->runNormalizado();
        if (! $run) {
            return null;
        }

        $dv = $this->dv ? strtoupper((string) $this->dv) : null;
        if (! $dv && $this->claveunica_id) {
            $parsed = self::parseRutComponents((string) $this->claveunica_id);
            $dv = $parsed['dv'] ?? null;
        }

        if (! $dv) {
            return null;
        }

        return number_format((int) $run, 0, '', '.') . '-' . $dv;
    }

    /**
     * Parsea RUN y DV desde texto (ej: "12345678-9", "12.345.678-K").
     *
     * @return array{run: ?string, dv: ?string}
     */
    public static function parseRutComponents(?string $texto): array
    {
        if ($texto === null || trim($texto) === '') {
            return ['run' => null, 'dv' => null];
        }

        $texto = strtoupper(trim(str_replace('.', '', $texto)));

        if (preg_match('/^(\d{1,8})-([\dK])$/', $texto, $matches)) {
            return [
                'run' => ltrim($matches[1], '0') ?: '0',
                'dv' => $matches[2],
            ];
        }

        if (preg_match('/^(\d{1,8})$/', $texto, $matches)) {
            return [
                'run' => ltrim($matches[1], '0') ?: '0',
                'dv' => null,
            ];
        }

        return ['run' => null, 'dv' => null];
    }

    /**
     * Extrae RUN y DV desde la respuesta userinfo de Clave Única.
     *
     * @return array{run: ?string, dv: ?string}
     */
    public static function extraerRunDvDesdeRaw(array $raw, ?object $socialiteUser = null): array
    {
        $run = null;
        $dv = null;

        $rol = $raw['RolUnico'] ?? $raw['rolUnico'] ?? null;
        if (is_array($rol)) {
            if (isset($rol['numero'])) {
                $runDigits = preg_replace('/\D/', '', (string) $rol['numero']);
                if ($runDigits !== '') {
                    $run = ltrim($runDigits, '0') ?: '0';
                }
            }
            foreach (['DV', 'dv', 'D'] as $key) {
                if (! empty($rol[$key])) {
                    $dv = strtoupper(substr((string) $rol[$key], 0, 1));
                    break;
                }
            }
        }

        if ($socialiteUser) {
            if (! $run && ! empty($socialiteUser->run)) {
                $runDigits = preg_replace('/\D/', '', (string) $socialiteUser->run);
                if ($runDigits !== '') {
                    $run = ltrim($runDigits, '0') ?: '0';
                }
            }
            if (! $dv && ! empty($socialiteUser->dv)) {
                $dv = strtoupper(substr((string) $socialiteUser->dv, 0, 1));
            }
            if (! $run && ! empty($socialiteUser->id) && preg_match('/^\d{1,8}$/', (string) $socialiteUser->id)) {
                $run = (string) $socialiteUser->id;
            }
        }

        foreach (['sub', 'run'] as $claim) {
            if ((! $run || ! $dv) && ! empty($raw[$claim]) && is_string($raw[$claim])) {
                $parsed = self::parseRutComponents($raw[$claim]);
                $run = $run ?? $parsed['run'];
                $dv = $dv ?? $parsed['dv'];
            }
        }

        if ($run && ! preg_match('/^\d{1,8}$/', $run)) {
            $run = null;
        }

        return ['run' => $run, 'dv' => $dv];
    }

    /** Persiste RUN/DV en BD si faltan y hay datos raw de Clave Única en sesión. */
    public function asegurarIdentidadClaveUnica(): void
    {
        if ($this->rutFormateado()) {
            return;
        }

        $raw = session('claveunica_raw');
        if (! is_array($raw) || empty($raw)) {
            return;
        }

        $this->sincronizarRunDvDesdeRaw($raw);
        $this->refresh();
    }

    /** Actualiza run/dv en BD desde datos raw de Clave Única. */
    public function sincronizarRunDvDesdeRaw(array $raw, ?object $socialiteUser = null): bool
    {
        ['run' => $run, 'dv' => $dv] = self::extraerRunDvDesdeRaw($raw, $socialiteUser);

        if (! $run || ! $dv) {
            return false;
        }

        if ($this->run === $run && strtoupper((string) $this->dv) === $dv) {
            return true;
        }

        $this->update([
            'run' => $run,
            'dv' => $dv,
        ]);

        return true;
    }

    /** Nombre y RUT oficiales obtenidos al iniciar sesión con Clave Única. */
    public function datosIdentidadClaveUnica(): array
    {
        return [
            'nombre' => (string) ($this->name ?? ''),
            'rut' => $this->rutFormateado() ?? '',
        ];
    }

    /** Precarga contacto desde solicitudes previas; nombre y RUT siempre desde Clave Única. */
    public function datosPrecargadosSolicitud(): array
    {
        $datos = array_merge($this->datosIdentidadClaveUnica(), [
            'email' => $this->email ?? '',
            'telefono' => '',
            'direccion' => '',
        ]);

        $ultimaSolicitud = $this->solicitudesComoVecino()->latest()->first();
        if ($ultimaSolicitud && $ultimaSolicitud->datos_json) {
            $previos = $ultimaSolicitud->datos_json;
            $datos['email'] = $previos['email'] ?? $previos['mail'] ?? $datos['email'];
            $datos['telefono'] = $previos['telefono'] ?? '';
            $datos['direccion'] = $previos['direccion'] ?? '';
        }

        return $datos;
    }

    /** Fuerza nombre y RUT de Clave Única al guardar una solicitud. */
    public function aplicarIdentidadEnDatos(array $datos): array
    {
        $datos['nombre'] = (string) ($this->name ?? '');

        if ($rut = $this->rutFormateado()) {
            $datos['rut'] = $rut;
        }

        return $datos;
    }
}
