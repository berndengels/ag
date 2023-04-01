<x-app-layout>
    <x-slot name="header">
        {{ __('Create Jobcenter') }}
    </x-slot>
    <div>
        <x-btn-back route="{{ route('admin.jobcenters.index') }}" />
        <x-form method="post" :action="route('admin.jobcenters.store')" class="w-half mt-3">
            <x-form-input name="name" label="Name" required />
            <x-form-select name="locations" label="PLZ" :options="$locations" required />
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>
