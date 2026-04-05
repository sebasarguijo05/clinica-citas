@extends('layouts.admin')
@section('title', 'Mensajes')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden flex" style="height: 78vh;">

    {{-- Lista de conversaciones --}}
    <div class="w-80 border-r border-slate-200 flex flex-col flex-shrink-0">
        <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
            <h3 class="text-sm font-semibold text-slate-700">Todas las conversaciones</h3>
        </div>
        <div class="overflow-y-auto flex-1">
            @foreach($conversations as $apt)
                @php
                    $unread = $apt->messages()
                        ->where('user_id', '!=', auth()->id())
                        ->where('is_read', false)->count();
                    $lastMsg = $apt->messages()->latest()->first();
                @endphp
                <a href="{{ route('admin.messages.show', $apt) }}"
                   class="flex items-start gap-3 px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition
                   {{ $appointment->id === $apt->id ? 'bg-orange-50 border-l-2 border-l-orange-500' : '' }}">
                    <div class="w-9 h-9 bg-slate-700 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                        {{ strtoupper(substr($apt->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $apt->user->name }}</p>
                            @if($unread > 0)
                                <span class="bg-orange-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 ml-1">
                                    {{ $unread }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 truncate">Dr. {{ $apt->doctor->name }}</p>
                        @if($lastMsg)
                            <p class="text-xs text-slate-500 truncate mt-0.5">{{ Str::limit($lastMsg->message, 35) }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Área del chat --}}
    <div class="flex-1 flex flex-col">

        {{-- Header --}}
        <div class="px-5 py-3 border-b border-slate-200 bg-slate-50 flex items-center gap-3">
            <div class="w-9 h-9 bg-slate-700 rounded-full flex items-center justify-center text-white text-sm font-bold">
                {{ strtoupper(substr($appointment->user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-800">{{ $appointment->user->name }}</p>
                <p class="text-xs text-slate-400">
                    Dr. {{ $appointment->doctor->name }} ·
                    {{ $appointment->date->format('d/m/Y') }} ·
                    <span class="font-medium
                        {{ $appointment->status === 'approved' ? 'text-emerald-600' : ($appointment->status === 'rejected' ? 'text-red-500' : 'text-amber-500') }}">
                        {{ $appointment->statusLabel() }}
                    </span>
                </p>
            </div>
            <div class="ml-auto">
                <a href="{{ route('admin.appointments.show', $appointment) }}"
                   class="text-xs text-slate-400 hover:text-orange-500 flex items-center gap-1 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Ver cita
                </a>
            </div>
        </div>

        {{-- Mensajes --}}
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3 bg-slate-50" id="chat-messages">
            @forelse($appointment->messages as $msg)
                @php $isMine = $msg->user_id === auth()->id(); @endphp
                <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} items-end gap-2">
                    @if(!$isMine)
                        <div class="w-7 h-7 bg-slate-600 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr($appointment->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="max-w-sm">
                        <div class="px-4 py-2.5 rounded-2xl text-sm
                            {{ $isMine
                                ? 'bg-slate-800 text-white rounded-br-sm'
                                : 'bg-white text-slate-800 rounded-bl-sm shadow-sm border border-slate-200' }}">
                            {{ $msg->message }}
                        </div>
                        <p class="text-xs text-slate-400 mt-1 {{ $isMine ? 'text-right' : 'text-left' }}">
                            {{ $msg->created_at->format('h:i A') }}
                        </p>
                    </div>
                    @if($isMine)
                        <div class="w-7 h-7 bg-slate-800 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <p class="text-slate-400 text-sm">No hay mensajes aún.</p>
                </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="px-5 py-3 border-t border-slate-200 bg-white">
            <form method="POST" action="{{ route('admin.messages.store', $appointment) }}"
                  class="flex items-center gap-3">
                @csrf
                <input type="text" name="message"
                    placeholder="Escribe un mensaje al paciente..."
                    autocomplete="off"
                    class="flex-1 bg-slate-100 border-0 rounded-full px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    required>
                <button type="submit"
                    class="w-9 h-9 bg-orange-500 hover:bg-orange-600 text-white rounded-full flex items-center justify-center transition flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const chatMessages = document.getElementById('chat-messages');
    if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight;
</script>
@endsection