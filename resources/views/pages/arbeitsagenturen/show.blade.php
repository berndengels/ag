<x-app-layout>
    <x-slot name="header">
        {{ $arbeitsagentur->name }}
    </x-slot>
    <div class="container">
        <x-btn-back route="{{ route('admin.arbeitsagenturen.index') }}" />
        {{ dd($data) }}
    </div>
</x-app-layout>
