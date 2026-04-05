@extends('layouts.admin')
@section('title', 'Calendario de Citas')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-lg font-semibold text-slate-800">Calendario de Citas</h2>
        <p class="text-sm text-slate-500 mt-0.5">Vista general de todas las citas de la clínica</p>
    </div>
</div>

{{-- Leyenda --}}
<div class="flex gap-3 mb-5">
    <span class="inline-flex items-center gap-2 text-xs text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span> Pendiente
    </span>
    <span class="inline-flex items-center gap-2 text-xs text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Aprobada
    </span>
    <span class="inline-flex items-center gap-2 text-xs text-slate-600 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm">
        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Rechazada
    </span>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div id="calendar"></div>
</div>

<style>
    .fc .fc-toolbar-title { font-size: 1rem; font-weight: 600; color: #1e293b; }
    .fc .fc-button { background-color: #1e293b !important; border-color: #1e293b !important; font-size: 0.75rem !important; padding: 0.35rem 0.75rem !important; border-radius: 0.5rem !important; }
    .fc .fc-button:hover { background-color: #334155 !important; }
    .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #f97316 !important; border-color: #f97316 !important; }
    .fc .fc-today-button { background-color: #f97316 !important; border-color: #f97316 !important; }
    .fc .fc-daygrid-day.fc-day-today { background-color: #fff7ed !important; }
    .fc .fc-col-header-cell { background-color: #f8fafc; padding: 0.5rem 0; }
    .fc .fc-col-header-cell-cushion { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; text-decoration: none; }
    .fc .fc-daygrid-day-number { font-size: 0.8rem; color: #475569; text-decoration: none; padding: 4px 8px; }
    .fc-event { border-radius: 6px !important; border: none !important; font-size: 0.72rem !important; padding: 2px 6px !important; }
    .fc .fc-event-title { font-weight: 500; }
    .fc th, .fc td { border-color: #e2e8f0 !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left:   'prev,next today',
                center: 'title',
                right:  'dayGridMonth,timeGridWeek,listMonth'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week:  'Semana',
                list:  'Lista'
            },
            events: '{{ route('admin.calendar.events') }}',
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                if (info.event.url) window.location.href = info.event.url;
            },
            eventDidMount: function(info) {
                info.el.title =
                    'Paciente: ' + info.event.extendedProps.patient + '\n' +
                    'Doctor: '   + info.event.extendedProps.doctor  + '\n' +
                    'Estado: '   + info.event.extendedProps.status;
            },
            height: 'auto',
            nowIndicator: true,
        });
        calendar.render();
    });
</script>

@endsection