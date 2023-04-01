@if($items->count())
    <table class="table-auto caption-top border-collapse {{ $class }}">
        <thead>
            <tr>
                @foreach($captions as $field)
                    <th scope="col" @if($styles[$field]) class="{{ $styles[$field] }}"@endif >
                        @if($links && isset($links[$field]))
                            @sortablelink(...$links[$field])
                        @else
                            {{ $field }}
                        @endif
                    </th>
                @endforeach
                @if($hasActions)
                    <th scope="col">Aktion</th>
                @endif
            </tr>
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
@else
    <div class="mt-3">
        <h5>Keine Daten vorhanden</h5>
    </div>
@endif
