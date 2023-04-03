<x-form action="{{ $route }}">
    @method('delete')
    <x-form-submit inline class="btn btn-red delsoft">
        <i class="fas fa-trash-alt"></i>
        <span class="hidden md:inline md:ml-2">Löschen</span>
    </x-form-submit>
</x-form>
