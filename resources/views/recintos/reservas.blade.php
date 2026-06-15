@extends('layouts.app')

@section('title', 'Reservas de Recintos')

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Reservas de Recintos</h1>
            <p class="mt-2 text-base text-slate-600">Gestiona las reservas de recintos municipales</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('recintos.reservas') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Recinto</label>
                    <select name="recinto_id" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                        <option value="">Todos</option>
                        @foreach($recintos as $recinto)
                            <option value="{{ $recinto->id }}" {{ request('recinto_id') == $recinto->id ? 'selected' : '' }}>
                                {{ $recinto->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Estado</label>
                    <select name="estado" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="aprobada" {{ request('estado') === 'aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="rechazada" {{ request('estado') === 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                        <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        <span class="flex items-center justify-center">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Buscar
                        </span>
                    </button>
                </div>
            </form>
        </div>

        @if($reservas->count() > 0)
            <!-- Table Section -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <!-- Table Header -->
                <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Reservas</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/50">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Recinto</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Fecha Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Solicitante</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-600">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-slate-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($reservas as $reserva)
                                <tr class="transition-colors hover:bg-slate-50/50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $reserva->recinto->nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($reserva->fecha_inicio)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ $reserva->hora_inicio }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($reserva->fecha_fin)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">
                                            @if($reserva->solicitud)
                                                {{ $reserva->solicitud->vecino->name }}
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($reserva->estado === 'aprobada')
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Aprobada</span>
                                        @elseif($reserva->estado === 'rechazada')
                                            <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-medium text-rose-800">Rechazada</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800">{{ ucfirst($reserva->estado) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($reserva->estado === 'pendiente')
                                            <div class="flex items-center justify-end gap-2">
                                                <form method="POST" action="{{ route('recintos.reserva.aprobar', $reserva->id) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-emerald-700">
                                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Aprobar
                                                    </button>
                                                </form>
                                                <button type="button" onclick="openRejectModal({{ $reserva->id }})" class="inline-flex items-center rounded-lg bg-rose-600 px-3 py-1.5 text-xs font-medium text-white transition-colors hover:bg-rose-700">
                                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Rechazar
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
                    {{ $reservas->links() }}
                </div>
            </div>
        @else
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto mb-4 h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="mt-4 text-sm font-medium text-slate-900">No se encontraron reservas</p>
                <p class="mt-2 text-sm text-slate-600">Intenta ajustar los filtros de búsqueda</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Rechazar -->
<div id="rejectModal" x-data="{ open: false }" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 backdrop-blur-sm" @click.away="open = false" onclick="if(event.target===this) closeRejectModal()" style="display: none;">
    <div class="relative w-11/12 max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-xl" @click.stop>
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-900">Rechazar Reserva</h3>
            <button type="button" @click="open = false" onclick="closeRejectModal()" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-600 transition-colors hover:bg-slate-100">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <form id="rejectForm" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Motivo del Rechazo</label>
                <textarea name="motivo" rows="3" required minlength="10" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder:text-slate-500 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-0 transition-colors resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" @click="open = false" onclick="closeRejectModal()" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                    Cancelar
                </button>
                <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-rose-700">
                    Rechazar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(reservaId) {
    const form = document.getElementById('rejectForm');
    form.action = `/recintos/reserva/${reservaId}/rechazar`;
    const modal = document.getElementById('rejectModal');
    if (modal.__x && modal.__x.$data) {
        modal.__x.$data.open = true;
    } else {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }
}
function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    if (modal.__x && modal.__x.$data) modal.__x.$data.open = false;
    modal.style.display = 'none';
    modal.classList.add('hidden');
}
</script>
@endpush
@endsection
