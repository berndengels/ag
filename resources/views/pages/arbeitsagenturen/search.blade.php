<x-app-layout>
    <x-slot name="header">
        {{ $arbeitsagentur->name }}
    </x-slot>

    <div class="container">
        <div class="container m-2">
            <h5>Adresse</h5>
            <p>
                <span>{{ $arbeitsagentur->street }}</span><br>
                <span>{{ $arbeitsagentur->postcode }} {{ $arbeitsagentur->city }}</span>
            </p>
        </div>
        <div class="container m-2">
            <h5>Kontakt</h5>
            <p>
                <span><a href="mailto:{{ $arbeitsagentur->email }}" target="_blank">{{ $arbeitsagentur->email }}</a></span>
            </p>
            <ul>
                @foreach(explode("\n", $arbeitsagentur->fon) as $fon)
                    <li><a href="tel:+{{ $fon }}" target="_blank">{{ $fon }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="container m-2">
            <h5>Öffnungszeiten</h5>
            <ul>
                @foreach(explode("\n", $arbeitsagentur->opening_time) as $time)
                    <li>{{ $time }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
