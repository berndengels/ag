<x-app-layout>
    <x-slot name="header">
        {{ $jobcenter->name }}
    </x-slot>

    <div class="container">
        <div class="container m-2">
            <h5>Adresse</h5>
            <p>
                <span>{{ $jobcenter->street }}</span><br>
                <span>{{ $jobcenter->postcode }} {{ $jobcenter->city }}</span>
            </p>
        </div>
        <div class="container m-2">
            <h5>Kontakt</h5>
            <p>
                <span><a href="mailto:{{ $jobcenter->email }}" target="_blank">{{ $jobcenter->email }}</a></span>
            </p>
            <ul>
                @foreach(explode("\n", $jobcenter->fon) as $fon)
                    <li><a href="tel:+{{ $fon }}" target="_blank">{{ $fon }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="container m-2">
            <h5>Öffnungszeiten</h5>
            <ul>
                @foreach(explode("\n", $jobcenter->opening_time) as $time)
                    <li>{{ $time }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
