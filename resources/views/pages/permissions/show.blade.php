<x-app-layout>
    <x-slot name="header">
        {{ $permission->name }}
    </x-slot>
    <div>
        <x-btn-back route="{{ route('admin.permissions.index') }}" />
    </div>
</x-app-layout>
