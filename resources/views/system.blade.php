@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Solar System Info</h1>

        <div class="search">
            <form class="pure-form">
                <fieldset>
                    <input type="text" id="system" value="{{ $system->solarSystemName ?? null }}" autofocus="true" />
                    <button type="button" id="search-btn" class="pure-button pure-button-primary">Search</button>
                </fieldset>
            </form>
        </div>

        @unless($system === null)
            {{-- {{ print_r($system) }} --}}
            <table class="pure-table pure-table-horizontal">
                <tbody>
                    <tr>
                        <td colspan="2" style="font-size:250%">{{ $system->solarSystemName }}</td>
                    </tr>
                    <tr>
                        <td>Region:</td>
                        <td>{{ $system->regionName }}</td>
                    </tr>
                    <tr>
                        <td>Constellation:</td>
                        <td>{{ $system->constellationName }}</td>
                    </tr>
                    <tr>
                        <td>Security:</td>
                        <td id="security">{{ round($system->security, 2) }}</td>
                    </tr>
                    @if ($system->wormholeClass ?? false)
                        <tr>
                            <td>Wormhole Class:</td>
                            <td><b>{{ $system->wormholeClass ?? '' }}</b></td>
                        </tr>
                        <tr>
                            <td>Star:</td>
                            <td>{{ $system->wormholeStar ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Planets:</td>
                            <td>{{ $system->wormholePlanets ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Moons:</td>
                            <td>{{ $system->wormholeMoons ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Statics:</td>
                            <td>
                                @if ($system->wormholeStatics ?? false)
                                    @foreach ($system->wormholeStatics as $item)
                                        {{ $item->hole }}

                                        @switch($item->in_class)
                                            @case(7)
                                                (<span style="color:lightgreen"><b>HS</b></span>)
                                            @break

                                            @case(8)
                                                (<span style="color:orange"><b>LS</b></span>)
                                            @break

                                            @case(9)
                                                (<span style="color:red"><b>NS</b></span>)
                                            @break

                                            @case(12)
                                                (Thera)
                                            @break

                                            @default
                                                (C{{ $item->in_class }})
                                        @endswitch
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="2">
                            <a target="_blank" href="https://evemaps.dotlan.net/system/{{ urlencode($system->solarSystemName) }}"><img src="/img/map.png" title="evemaps.dotlan.net"></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <h5>{{ $errorMessage }}</h5>
        @endunless

    </div>


<style>
    .pure-table tr td:first-child {
        color: gray;
    }
</style>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#search-btn').on('click', function(e){
            e.preventDefault();
            window.location.href = '/system/' + $('#system').val();
        });

        // security status color
        const security = parseFloat($('#security').text());

        $('#security').css('color', '#00BFFF');
        if (security <= 0.8) {
            $('#security').css('color', '#008000');
        }
        if (security < 0.6) {
            $('#security').css('color', '#FFD700');
        }
        if (security < 0.5) {
            $('#security').css('color', '#FF8C00');
        }
        if (security < 0) {
            $('#security').css('color', '#FF0000');
        }
    });
</script>
@endpush