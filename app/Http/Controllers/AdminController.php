<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SolicitudTipo;
use App\Models\Solicitud;
use App\Models\Recinto;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Gestión de Usuarios
    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $usuarios = $query->orderBy('name')->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function crearUsuario()
    {
        return view('admin.usuarios.create');
    }

    public function storeUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:administrador,oficina_partes,funcionario,vecino',
            'estado' => 'required|in:activo,inactivo',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => $request->estado,
        ]);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario creado exitosamente');
    }

    public function editarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function updateUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'rol' => 'required|in:administrador,oficina_partes,funcionario,vecino',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
            'estado' => $request->estado,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    // Gestión de Catálogo de Solicitudes
    public function catalogo()
    {
        $tipos = SolicitudTipo::orderBy('seccion')->orderBy('titulo')->get();
        return view('admin.catalogo.index', compact('tipos'));
    }

    public function crearTipo()
    {
        $recintos = Recinto::where('activo', true)->orderBy('nombre')->get();
        return view('admin.catalogo.create', compact('recintos'));
    }

    public function storeTipo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50|unique:solicitud_tipos,codigo',
            'titulo' => 'required|string|max:255',
            'seccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'campos' => 'nullable|array',
            'campos.*' => 'string|in:nombre,mail,telefono,direccion,recinto,fecha_inicio,fecha_fin,hora_inicio,hora_fin,detalle',
            'recintos_ids' => 'nullable|array',
            'recintos_ids.*' => 'integer|exists:recintos,id',
            'documentos_requeridos' => 'nullable|array',
            'documentos_requeridos.*' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['codigo', 'titulo', 'seccion', 'descripcion']);
        $data['activo'] = $request->has('activo') ? true : false;
        $data['requisitos_json'] = $this->buildRequisitosJson($request);

        SolicitudTipo::create($data);

        return redirect()->route('admin.catalogo')
            ->with('success', 'Tipo de solicitud creado exitosamente');
    }

    private function buildRequisitosJson(Request $request): array
    {
        $campos = $request->input('campos', []);
        $docReq = $request->input('documentos_requeridos', []);
        $docReq = is_array($docReq) ? array_values(array_filter(array_map('trim', $docReq))) : [];
        $req = [
            'campos' => array_values($campos),
            'requiere_adjuntos' => $request->boolean('requiere_adjuntos'),
            'documentos_requeridos' => $docReq,
        ];
        $req['usar_horarios_disponibles'] = $request->boolean('usar_horarios_disponibles');
        $recintosIds = $request->input('recintos_ids', []);
        if (is_array($recintosIds)) {
            $req['recintos_ids'] = array_values(array_map('intval', array_filter($recintosIds)));
        }
        return $req;
    }

    public function editarTipo($id)
    {
        $tipo = SolicitudTipo::findOrFail($id);
        $recintos = Recinto::where('activo', true)->orderBy('nombre')->get();
        return view('admin.catalogo.edit', compact('tipo', 'recintos'));
    }

    public function updateTipo(Request $request, $id)
    {
        $tipo = SolicitudTipo::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'seccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'campos' => 'nullable|array',
            'campos.*' => 'string|in:nombre,mail,telefono,direccion,recinto,fecha_inicio,fecha_fin,hora_inicio,hora_fin,detalle',
            'recintos_ids' => 'nullable|array',
            'recintos_ids.*' => 'integer|exists:recintos,id',
            'documentos_requeridos' => 'nullable|array',
            'documentos_requeridos.*' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['titulo', 'seccion', 'descripcion']);
        $data['activo'] = $request->has('activo') ? true : false;
        $data['requisitos_json'] = $this->buildRequisitosJson($request);

        $tipo->update($data);

        return redirect()->route('admin.catalogo')
            ->with('success', 'Tipo de solicitud actualizado exitosamente');
    }

    public function toggleTipoActivo($id)
    {
        $tipo = SolicitudTipo::findOrFail($id);
        $tipo->update(['activo' => ! $tipo->activo]);

        $mensaje = $tipo->activo
            ? 'Trámite habilitado correctamente.'
            : 'Trámite deshabilitado correctamente. Ya no será visible para los ciudadanos.';

        return back()->with('success', $mensaje);
    }

    public function eliminarTipo($id)
    {
        $tipo = SolicitudTipo::findOrFail($id);

        if ($tipo->codigo === 'OIRS') {
            return back()->with('error', 'El trámite OIRS es obligatorio del sistema y no puede eliminarse. Puede deshabilitarlo si lo necesita.');
        }
        // Verificar si tiene solicitudes asociadas
        if ($tipo->solicitudes()->count() > 0) {
            return back()->with('error', 'No se puede eliminar un tipo de solicitud que tiene solicitudes asociadas');
        }

        $tipo->delete();

        return redirect()->route('admin.catalogo')
            ->with('success', 'Tipo de solicitud eliminado exitosamente');
    }

    // Reportes
    public function reportes()
    {
        $stats = [
            'por_estado' => Solicitud::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->get(),
            'por_seccion' => Solicitud::join('solicitud_tipos', 'solicitudes.tipo_id', '=', 'solicitud_tipos.id')
                ->select('solicitud_tipos.seccion', DB::raw('count(*) as total'))
                ->groupBy('solicitud_tipos.seccion')
                ->get(),
            'pendientes_por_funcionario' => Solicitud::join('users', 'solicitudes.asignado_user_id', '=', 'users.id')
                ->whereIn('solicitudes.estado', ['derivada', 'en_gestion'])
                ->select('users.name', DB::raw('count(*) as total'))
                ->groupBy('users.id', 'users.name')
                ->get(),
        ];

        return view('admin.reportes', compact('stats'));
    }
}
