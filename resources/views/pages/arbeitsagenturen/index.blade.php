<x-app-layout>
    <x-slot name="header">
        {{ __('Arbeitsagentur') }}
    </x-slot>

    <div>
        <div class="index-header mt-3 p-0">
            <div class="float-start">
                <x-btn-create route="{{ route('admin.arbeitsagenturen.create') }}" />
            </div>
            <div class="float-end">
                <x-search-filter name="postcode" method="post" action="{{ route('admin.arbeitsagenturen.search') }}" placeholder="Suche Arbeitsagentur" />
            </div>
        </div>
        {{ $data->links() }}
        <x-table :items="$data" :fields="['Kunden PLZ','Name']" hasActions isSmall>
            @foreach($data as $item)
                <tr class="odd:bg-gray-100">
                    @bindData($item)
                    <x-td field="customer_postcode" />
                    <x-td field="name" link="{{ route('admin.arbeitsagenturen.show', $item) }}" />
                    <x-action routePrefix="admin.arbeitsagenturen" edit delete />
                    @endBindData
                </tr>
            @endforeach
        </x-table>
        {{ $data->links() }}
    </div>
</x-app-layout>
