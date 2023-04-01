<x-app-layout>
    <x-slot name="header">
        {{ __('Create Role') }}
    </x-slot>

    <div>
        <x-btn-back route="{{ route('admin.roles.index') }}" />
        <x-form method="post" :action="route('admin.roles.store')" class="w-half mt-3">
            <x-form-input name="name" label="Name" required />
            <x-form-select name="permissions[]" label="Permissions" :options="$permissions" class="flexy" size="10" many-relation multiple />
            <div class="mt-2">
                <x-form-submit class="btn-sm btn-primary" icon="fas fa-save">Speichern</x-form-submit>
            </div>
        </x-form>
    </div>
</x-app-layout>
