<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Permission') }}
    </x-slot>
    <div>
        <x-btn-back route="{{ route('admin.permissions.index') }}" />
        <h3 class="mt-3 text-green-800">Name: {{ $permission->name }}</h3>
        <x-form method="post" :action="route('admin.permissions.update', $permission)" class="w-half mt-3">
            @method('put')
            @bind($permission)
            <x-form-select name="name" label="Name" :options="$models" required />
            <x-form-select name="action" label="Aktion" :options="$actions" required />
            <x-form-input name="custom_name" label="Eigener Name" placeholder="Eigener Name" />
            @endbind
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>>

