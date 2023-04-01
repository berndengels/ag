<x-app-layout>
    <x-slot name="header">
        {{ __('Create Permission') }}
    </x-slot>
    <div>
        <x-btn-back route="{{ route('admin.permissions.index') }}" />
        <x-form method="post" :action="route('admin.permissions.store')" class="w-half mt-3">
            <x-form-select name="model" label="Model" :options="$models" required />
            <x-form-select name="action" label="Aktion" :options="$actions" required />
            <x-form-input name="name" label="Eigener Name" />
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>
