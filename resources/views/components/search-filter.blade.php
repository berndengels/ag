<div>
    <x-form class="{{ $css ?: '' }}" :method="$method" :action="$action">
        @bind($name)
        @if ($options)
            <x-form-select
                    :name="$name"
                    :label="$label"
                    :options="$options"
                    :placeholder="$placeholder"
                    onchange="this.parent('form').submit()"
            />
        @else
            <x-form-input
                    class="d-inline-block"
                    :name="$name"
                    :label="$label"
                    :placeholder="$placeholder"
            />
            <x-form-submit class="btn btn-sm btn-secondary d-inline-block" name="submit">Suche</x-form-submit>
        @endif
        @endbind
    </x-form>
</div>
