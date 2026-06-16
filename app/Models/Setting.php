<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'app_settings';

    /** Devuelve todas las configuraciones como arreglo clave => valor (cacheado). */
    private static function all_settings(): array
    {
        try {
            return Cache::rememberForever(self::CACHE_KEY, function () {
                return self::query()->pluck('value', 'key')->all();
            });
        } catch (\Throwable $e) {
            // La tabla puede no existir aún (antes de migrar).
            return [];
        }
    }

    /** Obtiene el valor de una configuración o el valor por defecto. */
    public static function get(string $key, $default = null)
    {
        $settings = self::all_settings();

        return $settings[$key] ?? $default;
    }

    /** Guarda una configuración y limpia la caché. */
    public static function set(string $key, $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /** Indica si una configuración booleana está activada. */
    public static function enabled(string $key, bool $default = true): bool
    {
        $valor = self::get($key, $default ? '1' : '0');

        return in_array((string) $valor, ['1', 'true', 'on', 'yes'], true);
    }
}
