<x-app-layout>
    <x-slot name="header">
        {{ __('Permissions') }}
    </x-slot>

    <div>
        <div class="index-header mt-3 p-0">
            <div class="float-start">
                <x-btn-create route="{{ route('admin.permissions.create') }}" />
            </div>
            <div class="float-end">
                <x-search-filter name="name" action="{{ route('admin.permissions.index') }}" placeholder="Suche Permissions" />
            </div>
        </div>
        {{ $data->links() }}
        <x-table :items="$data" :fields="['Name']" hasActions isSmall>
            @foreach($data as $item)
                <tr>
                    @bindData($item)
                    <x-td field="name" />
                    <x-action routePrefix="admin.permissions" edit delete />
                    @endBindData
                </tr>
            @endforeach
        </x-table>
        {{ $data->links() }}
    </div>
</x-app-layout>
