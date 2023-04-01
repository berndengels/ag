<x-app-layout>
    <x-slot name="header">
        {{ $role->name }}
    </x-slot>

    <div>
        <x-btn-back route="{{ route('admin.roles.index') }}" />
    </div>
</x-app-layout>

