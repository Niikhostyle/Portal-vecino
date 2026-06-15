<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Buscar usuario por email
        $user = User::where('email', $request->email)->first();

        // Verificar que el usuario existe y tiene password (no es solo de Clave Única)
        if (!$user || !$user->password) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas no son correctas.'],
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no son correctas.'],
        ]);
    }

    /**
     * Redirigir a Clave Única para autenticación
     */
    public function redirectToClaveUnica()
    {
        return Socialite::driver('claveunica')->redirect();
    }

    /**
     * Manejar callback de Clave Única
     */
    public function handleClaveUnicaCallback()
    {
        try {
            // Verificar si hay un error en la respuesta de Clave Única
            if (request()->has('error')) {
                $error = request()->get('error');
                $errorDescription = request()->get('error_description', 'Error desconocido');
                \Log::error('Error de Clave Única en callback', [
                    'error' => $error,
                    'description' => $errorDescription,
                ]);
                return redirect()->route('login')
                    ->with('error', 'Error de Clave Única: ' . $errorDescription);
            }

            $claveUnicaUser = Socialite::driver('claveunica')->user();
            
            // Datos raw de Clave Única (sub = ID único OpenID, RolUnico = RUT chileno)
            $rawData = $claveUnicaUser->getRaw();
            \Log::info('Datos raw de Clave Única', ['raw' => $rawData]);
            
            // claveunica_id = "sub" (identificador único OpenID Connect)
            $claveunicaId = isset($rawData['sub']) ? (string) $rawData['sub'] : ($claveUnicaUser->id ? (string) $claveUnicaUser->id : null);

            ['run' => $run, 'dv' => $dv] = User::extraerRunDvDesdeRaw($rawData, $claveUnicaUser);

            $email = isset($claveUnicaUser->email) ? (string) $claveUnicaUser->email : null;
            
            // Nombre completo: construir desde raw (Clave Única envía name.nombres y name.apellidos)
            $name = '';
            if (!empty($rawData['name']) && is_array($rawData['name'])) {
                $nombres = isset($rawData['name']['nombres']) && is_array($rawData['name']['nombres'])
                    ? implode(' ', $rawData['name']['nombres'])
                    : '';
                $apellidos = isset($rawData['name']['apellidos']) && is_array($rawData['name']['apellidos'])
                    ? implode(' ', $rawData['name']['apellidos'])
                    : '';
                $name = trim($nombres . ' ' . $apellidos);
            } elseif (! empty($rawData['name']) && is_string($rawData['name'])) {
                $name = trim($rawData['name']);
            }
            
            // Fallback: first_name y last_name del objeto User de Socialite
            if (empty($name) && (isset($claveUnicaUser->first_name) || isset($claveUnicaUser->last_name))) {
                $first = isset($claveUnicaUser->first_name) ? (string) $claveUnicaUser->first_name : '';
                $last = isset($claveUnicaUser->last_name) ? (string) $claveUnicaUser->last_name : '';
                $name = trim($first . ' ' . $last);
            }
            
            if (empty($name)) {
                $name = 'Usuario Clave Única';
            }
            
            \Log::info('Datos recibidos de Clave Única (procesados)', [
                'id' => $claveunicaId,
                'name' => $name,
                'email' => $email,
                'run' => $run,
                'dv' => $dv,
            ]);
            
            // Validar que tengamos al menos un identificador
            if (empty($claveunicaId)) {
                throw new \Exception('No se recibió el ID de Clave Única');
            }
            
            // Buscar usuario existente: por sub (claveunica_id), run+dv, o claveunica_id=run (legacy)
            $user = null;
            if ($claveunicaId) {
                $user = User::where('claveunica_id', $claveunicaId)->first();
            }
            if (! $user && $run && $dv) {
                $user = User::where('run', $run)->where('dv', $dv)->first();
            }
            // Usuarios creados con bug: claveunica_id contenía RolUnico.numero (RUN)
            if (! $user && $run && preg_match('/^\d{1,8}$/', (string) $run)) {
                $user = User::where('claveunica_id', $run)->first();
            }

            if ($user) {
                // Usuario existe, actualizar datos si es necesario
                $updateData = [
                    'claveunica_id' => $claveunicaId ? (string) $claveunicaId : $user->claveunica_id,
                    'name' => (string) $name,
                ];
                
                if ($run) {
                    $updateData['run'] = (string) $run;
                }
                if ($dv) {
                    $updateData['dv'] = strtoupper((string) $dv);
                }
                if ($email && ! $user->email) {
                    $updateData['email'] = (string) $email;
                }
                
                \Log::info('Actualizando usuario con datos', ['data' => $updateData]);
                
                $user->update($updateData);
            } else {
                // Crear nuevo usuario como vecino por defecto
                // Asegurar que todos los valores sean strings o null antes de crear
                $userData = [
                    'claveunica_id' => $claveunicaId ? (string) $claveunicaId : null,
                    'name' => (string) $name,
                    'run' => $run ? (string) $run : null,
                    'dv' => $dv ? strtoupper((string) $dv) : null,
                    'email' => $email ? (string) $email : null,
                    'password' => null, // Sin password para usuarios de Clave Única
                    'rol' => 'vecino', // Por defecto vecino
                    'estado' => 'activo',
                ];
                
                \Log::info('Creando usuario con datos', ['data' => $userData]);
                
                $user = User::create($userData);
            }

            // Autenticar al usuario
            Auth::login($user, true);

            session(['claveunica_raw' => $rawData]);
            
            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Contracts\Container\BindingResolutionException $e) {
            \Log::error('Error de configuración Clave Única', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);
            return redirect()->route('login')
                ->with('error', 'Error de configuración con Clave Única. Verifica las credenciales en .env');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Error de estado inválido en Clave Única', [
                'message' => $e->getMessage(),
            ]);
            return redirect()->route('login')
                ->with('error', 'La sesión de Clave Única expiró. Por favor, intenta nuevamente.');
        } catch (\Exception $e) {
            \Log::error('Error en callback Clave Única', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $errorMessage = config('app.debug') 
                ? 'Error: ' . $e->getMessage() 
                : 'Error al autenticar con Clave Única. Por favor, intenta nuevamente.';
            
            return redirect()->route('login')
                ->with('error', $errorMessage);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $endSessionUrl = config('services.claveunica.end_session_url');
        $returnTo = URL::route('login');

        if ($endSessionUrl) {
            $separator = str_contains($endSessionUrl, '?') ? '&' : '?';
            $logoutUrl = $endSessionUrl . $separator . http_build_query([
                'post_logout_redirect_uri' => $returnTo,
            ]);
            return redirect()->away($logoutUrl);
        }

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
