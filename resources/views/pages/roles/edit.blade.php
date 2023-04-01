<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Role') }}
    </x-slot>

    <div>
        <x-btn-back route="{{ route('admin.roles.index') }}" />
        <x-form method="post" :action="route('admin.roles.update', $role)" class="w-half mt-3">
            @method('put')
            @bind($role)
            <x-form-input name="name" label="Name" required />
            <x-form-select name="permissions[]" label="Permissions" :options="$permissions" class="flexy" size="10" many-relation multiple />
            @endbind
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>
