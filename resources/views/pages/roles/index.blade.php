<x-app-layout>
    <x-slot name="header">
        {{ __('Roles') }}
    </x-slot>

    <div>
        <div class="index-header mt-3 p-0">
            <div class="ms-0">
                <x-btn-create route="{{ route('admin.roles.create') }}" />
            </div>
            <div></div>
        </div>
        {{ $data->links() }}
        <x-table :items="$data" :fields="['Name']" hasActions isSmall>
            @foreach($data as $item)
                <tr>
                    @bindData($item)
                    <x-td field="name" />
                    <x-action routePrefix="admin.roles" edit delete />
                    @endBindData
                </tr>
            @endforeach
        </x-table>
        {{ $data->links() }}
    </div>
</x-app-layout>
