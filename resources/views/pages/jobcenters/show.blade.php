<x-app-layout>
    <x-slot name="header">
        {{ $jobcenter->name }}
    </x-slot>
    <div class="container">
        <x-btn-back route="{{ route('admin.jobcenters.index') }}" />
        {{ dd($data) }}
    </div>
</x-app-layout>
