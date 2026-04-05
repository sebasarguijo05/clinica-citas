<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">Mensajes</h2>
    </x-slot>

    <div class="min-h-screen bg-slate-200 py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden flex" style="height: 75vh;">

                {{-- Lista de conversaciones --}}
                <div class="w-80 border-r border-slate-200 flex flex-col flex-shrink-0">
                    <div class="px-4 py-3 border-b border-slate-200 bg-slate-50">
                        <h3 class="text-sm font-semibold text-slate-700">Conversaciones</h3>
                    </div>
                    <div class="overflow-y-auto flex-1">
                        @forelse($allAppointments as $apt)
                            @php
                                $unread = $apt->messages()
                                    ->where('user_id', '!=', auth()->id())
                                    ->where('is_read', false)->count();
                                $lastMsg = $apt->messages()->latest()->first();
                            @endphp
                            <a href="{{ route('patient.messages.show', $apt) }}"
                               class="flex items-start gap-3 px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition
                               {{ isset($appointment) && $appointment->id === $apt->id ? 'bg-orange-50 border-l-2 border-l-orange-500' : '' }}">
                                <div class="w-9 h-9 bg-slate-700 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-0.5">
                                    {{ strtoupper(substr($apt->doctor->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $apt->doctor->name }}</p>
                                        @if($unread > 0)
                                            <span class="bg-orange-500 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 ml-1">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-400 truncate">{{ $apt->doctor->specialty }}</p>
                                    @if($lastMsg)
                                        <p class="text-xs text-slate-500 truncate mt-0.5">
                                            {{ Str::limit($lastMsg->message, 35) }}
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-400 italic mt-0.5">Sin mensajes aún</p>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center text-slate-400 text-sm">
                                No tienes citas registradas.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Panel vacío --}}
                <div class="flex-1 flex items-center justify-center bg-slate-50">
                    <div class="text-center">
                        <div class="w-14 h-14 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 text-sm font-medium">Selecciona una conversación</p>
                        <p class="text-slate-400 text-xs mt-1">Elige una cita para ver los mensajes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>