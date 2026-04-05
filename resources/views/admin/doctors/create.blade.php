@extends('layouts.admin')
@section('title', 'Nuevo Doctor')

@section('content')
@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
        <strong>Errores encontrados:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">➕ Nuevo Doctor</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.doctors.store') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                    @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad *</label>
                <input type="text" name="specialty" value="{{ old('specialty') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                    @error('specialty') border-red-500 @enderror">
                @error('specialty')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500
                    @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Biografía / Descripción</label>
                <textarea name="bio" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('bio') }}</textarea>
            </div>

            <div class="mb-6 flex items-center gap-2">
                <input type="checkbox" name="active" id="active" checked class="rounded">
                <label for="active" class="text-sm text-gray-700">Doctor activo</label>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Guardar Doctor
                </button>
                <a href="{{ route('admin.doctors.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection