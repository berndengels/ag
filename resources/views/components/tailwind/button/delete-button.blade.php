<x-form action="{{ $route }}"
        class="d-inline-block m-0 p-0">
    @method('delete')
    <x-form-submit icon="fas fa-trash-alt" inline class="mt-0 btn-sm btn-outline-danger delSoft">
        <span class="d-none d-md-inline-block">Löschen</span>
    </x-form-submit>
</x-form>
