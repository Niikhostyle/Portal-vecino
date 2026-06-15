@extends('layouts.app')

@section('title', 'Calendario de Recintos')

@section('content')
<div class="min-h-screen bg-white">
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Calendario de Recintos</h1>
            <p class="mt-2 text-base text-slate-600">Consulta la disponibilidad de recintos municipales</p>
        </div>

        <!-- Filtros -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('recintos.calendario') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Recinto</label>
                    <select name="recinto_id" onchange="this.form.submit()" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                        @foreach($recintos as $recinto)
                            <option value="{{ $recinto->id }}" {{ $recintoSeleccionado == $recinto->id ? 'selected' : '' }}>
                                {{ $recinto->nombre }} ({{ ucfirst(str_replace('_', ' ', $recinto->tipo)) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Mes/Año</label>
                    <input type="month" name="fecha" value="{{ $fecha }}" onchange="this.form.submit()" class="h-11 w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-0 transition-colors">
                </div>
            </form>
        </div>

        <!-- Calendario -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Calendario - {{ $carbon->format('F Y') }}</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-slate-200">
                        <thead>
                            <tr>
                                @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                                    <th class="bg-slate-50 px-4 py-3 text-center text-xs font-medium uppercase text-slate-600">{{ $dia }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @php
                                $startOfMonth = $carbon->copy()->startOfMonth();
                                $endOfMonth = $carbon->copy()->endOfMonth();
                                $startDate = $startOfMonth->copy()->startOfWeek();
                                $endDate = $endOfMonth->copy()->endOfWeek();
                                $currentDate = $startDate->copy();
                            @endphp
                            @while($currentDate->lte($endDate))
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $isCurrentMonth = $currentDate->month == $carbon->month;
                                            $dateStr = $currentDate->format('Y-m-d');
                                            $hasReserva = isset($reservas[$dateStr]) && $reservas[$dateStr]->count() > 0;
                                        @endphp
                                        <td class="border border-slate-200 px-4 py-8 text-center {{ !$isCurrentMonth ? 'bg-slate-50 text-slate-400' : '' }} {{ $hasReserva && $isCurrentMonth ? 'bg-amber-50' : '' }}" style="min-width: 120px; height: 100px;">
                                            <div class="flex h-full flex-col">
                                                <span class="text-sm font-semibold {{ $isCurrentMonth ? 'text-slate-900' : 'text-slate-400' }}">{{ $currentDate->day }}</span>
                                                @if($hasReserva && $isCurrentMonth)
                                                    <div class="mt-2">
                                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800">
                                                            <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                            </svg>
                                                            Ocupado
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        @php $currentDate->addDay(); @endphp
                                    @endfor
                                </tr>
                            @endwhile
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(auth()->user()->isOficinaPartes() || auth()->user()->isAdministrador())
            <div class="mt-6">
                <a href="{{ route('recintos.reservas') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-slate-800">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Todas las Reservas
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
