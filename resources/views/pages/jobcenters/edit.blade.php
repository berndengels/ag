<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Jobcenter') }}
    </x-slot>
    <div>
        <x-btn-back route="{{ route('admin.jobcenters.index') }}" />
        <h3 class="mt-3 text-green-800">Name: {{ $jobcenter->name }}</h3>
        <x-form method="post" :action="route('admin.jobcenters.update', $jobcenter)" class="w-half mt-3">
            @method('put')
            @bind($jobcenter)
            <x-form-input name="name" label="Name" placeholder="Jobcenter Name" />
            @endbind
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>>

