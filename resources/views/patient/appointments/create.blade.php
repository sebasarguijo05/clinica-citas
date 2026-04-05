<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ➕ Solicitar Nueva Cita
        </h2>
    </x-slot>

    <div class="py-8 min-h-screen bg-slate-200">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-6">

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('patient.appointments.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor *</label>
                        <select name="doctor_id"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                            @error('doctor_id') border-red-500 @enderror">
                            <option value="">— Selecciona un doctor —</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }} — {{ $doctor->specialty }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha *</label>
                        <input type="date" name="date" value="{{ old('date') }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                            @error('date') border-red-500 @enderror">
                        @error('date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora *</label>
                        <select name="time"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                            @error('time') border-red-500 @enderror">
                            <option value="">— Selecciona una hora —</option>
                            @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00'] as $hora)
                                <option value="{{ $hora }}" {{ old('time') == $hora ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($hora)->format('h:i A') }}
                                </option>
                            @endforeach
                        </select>
                        @error('time')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita</label>
                        <textarea name="reason" rows="3" placeholder="Describe brevemente el motivo de tu visita..."
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('reason') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                            Solicitar Cita
                        </button>
                        <a href="{{ route('patient.appointments.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>