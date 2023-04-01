<td class="table-action">
    @if($show)
        <x-btn-show route="{{ route($routePrefix . '.show', $model) }}" />
    @endif
    @if($info)
        <x-btn-info route="{{ route($routePrefix . '.show', $model) }}" />
    @endif
    @if($edit)
        <x-btn-edit route="{{ route($routePrefix . '.edit', $model) }}" />
    @endif
    @if($delete)
        <x-btn-delete route="{{ route($routePrefix . '.destroy', $model) }}" />
    @endif
</td>
