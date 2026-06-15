<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VecinoController;
use App\Http\Controllers\OficinaPartesController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecintosController;
use App\Http\Controllers\SolicitudController;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas Clave Única (callback debe coincidir con la URI registrada en el portal Clave Única)
Route::get('/auth/claveunica', [AuthController::class, 'redirectToClaveUnica'])->name('auth.claveunica');
Route::get('/callback', [AuthController::class, 'handleClaveUnicaCallback'])->name('auth.claveunica.callback');

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ruta de ejemplos de componentes (solo para desarrollo)
    Route::get('/ejemplos-componentes', function () {
        return view('examples.components');
    })->name('examples.components');

    // Rutas Vecino
    Route::middleware('role:vecino,administrador')->prefix('vecino')->name('vecino.')->group(function () {
        Route::get('/solicitudes', [VecinoController::class, 'index'])->name('solicitudes');
        Route::get('/mis-solicitudes', [VecinoController::class, 'misSolicitudes'])->name('mis-solicitudes');
        Route::get('/solicitud/{id}', [VecinoController::class, 'showSolicitud'])->name('solicitud.show');
        Route::get('/iniciar-solicitud/{tipoId}', [VecinoController::class, 'iniciarSolicitud'])->name('iniciar-solicitud');
        Route::post('/solicitud/{tipoId}', [VecinoController::class, 'storeSolicitud'])->name('solicitud.store');
        Route::get('/adjunto/{solicitudId}/{adjuntoId}', [VecinoController::class, 'descargarAdjunto'])->name('adjunto.descargar');
    });

    // Crear solicitud en nombre de ciudadano (OP, funcionario, admin)
    Route::middleware('role:oficina_partes,funcionario,administrador')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/crear-solicitud', [SolicitudController::class, 'index'])->name('crear-solicitud');
        Route::get('/crear-solicitud/{tipoId}', [SolicitudController::class, 'iniciarSolicitud'])->name('crear-solicitud.wizard');
        Route::post('/solicitud/{tipoId}', [SolicitudController::class, 'storeSolicitud'])->name('solicitud.store');
        // Registrar ciudadano manual (sin Clave Única)
        Route::get('/registrar-ciudadano', [SolicitudController::class, 'crearVecino'])->name('registrar-ciudadano');
        Route::post('/vecinos', [SolicitudController::class, 'storeVecino'])->name('vecinos.store');
    });

    // Rutas Oficina de Partes
    Route::middleware('role:oficina_partes,administrador')->prefix('op')->name('op.')->group(function () {
        Route::get('/bandeja', [OficinaPartesController::class, 'bandeja'])->name('bandeja');
        Route::get('/solicitud/{id}', [OficinaPartesController::class, 'showSolicitud'])->name('solicitud.show');
        Route::post('/derivar/{id}', [OficinaPartesController::class, 'derivar'])->name('solicitud.derivar');
        Route::post('/rechazar/{id}', [OficinaPartesController::class, 'rechazar'])->name('solicitud.rechazar');
    });

    // Rutas Funcionario
    Route::middleware('role:funcionario,administrador')->prefix('funcionario')->name('funcionario.')->group(function () {
        Route::get('/asignadas', [FuncionarioController::class, 'asignadas'])->name('asignadas');
        Route::get('/historial', [FuncionarioController::class, 'historial'])->name('historial');
        Route::get('/solicitud/{id}', [FuncionarioController::class, 'showSolicitud'])->name('solicitud.show');
        Route::post('/rechazar/{id}', [FuncionarioController::class, 'rechazar'])->name('rechazar');
        Route::post('/responder/{id}', [FuncionarioController::class, 'responder'])->name('responder');
        Route::post('/solicitar-info/{id}', [FuncionarioController::class, 'solicitarInfo'])->name('solicitar-info');
        Route::get('/adjunto/{solicitudId}/{adjuntoId}', [FuncionarioController::class, 'descargarAdjunto'])->name('adjunto.descargar');
    });

    // Rutas Admin
    Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('usuarios');
        Route::get('/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('usuarios.create');
        Route::post('/usuarios', [AdminController::class, 'storeUsuario'])->name('usuarios.store');
        Route::get('/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [AdminController::class, 'updateUsuario'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('usuarios.destroy');
        
        Route::get('/catalogo', [AdminController::class, 'catalogo'])->name('catalogo');
        Route::get('/catalogo/crear', [AdminController::class, 'crearTipo'])->name('catalogo.create');
        Route::post('/catalogo', [AdminController::class, 'storeTipo'])->name('catalogo.store');
        Route::get('/catalogo/{id}/editar', [AdminController::class, 'editarTipo'])->name('catalogo.edit');
        Route::put('/catalogo/{id}', [AdminController::class, 'updateTipo'])->name('catalogo.update');
        Route::patch('/catalogo/{id}/toggle', [AdminController::class, 'toggleTipoActivo'])->name('catalogo.toggle');
        Route::delete('/catalogo/{id}', [AdminController::class, 'eliminarTipo'])->name('catalogo.destroy');
        
        Route::get('/reportes', [AdminController::class, 'reportes'])->name('reportes');
    });

    // Rutas Recintos (accesible para todos los roles autenticados)
    Route::prefix('recintos')->name('recintos.')->group(function () {
        Route::get('/calendario', [RecintosController::class, 'calendario'])->name('calendario');
        Route::get('/{recintoId}/horarios-disponibles', [RecintosController::class, 'horariosDisponibles'])->name('horarios-disponibles');
        Route::post('/verificar-disponibilidad', [RecintosController::class, 'verificarDisponibilidad'])->name('verificar-disponibilidad');
        
        Route::middleware('role:oficina_partes,funcionario,administrador')->group(function () {
            Route::get('/reservas', [RecintosController::class, 'reservas'])->name('reservas');
            Route::post('/reserva/{id}/aprobar', [RecintosController::class, 'aprobarReserva'])->name('reserva.aprobar');
            Route::post('/reserva/{id}/rechazar', [RecintosController::class, 'rechazarReserva'])->name('reserva.rechazar');
        });
    });
});
