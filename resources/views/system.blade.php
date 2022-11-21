@extends('layouts.app')

@section('content')
    <div class="container main-content">
        <div class="tile is-parent is-vertical">
            <article class="tile is-child notification is-dark-half">
                <p class="title has-text-white">Solar system</p>
                <p class="subtitle has-text-white">Search for a solar system and track your character location</p>

                <div class="field is-horizontal">
                    <div class="field-body">
                        <div class="field">
                            <p class="control has-icons-left">
                                <input id="search" class="input" type="text" placeholder="Solar system"
                                    value="{{ $system->solarSystemName ?? '' }}">
                                <span class="icon is-small is-left">
                                    <i class="fa-solid fa-location-crosshairs"></i>
                                </span>
                            </p>
                        </div>
                        <div class="field">
                            <p class="control is-expanded">
                                <a id="search" href="#" class="button is-primary mr-4" title="Search">search</a>
                                <a id="locate" href="#" class="button is-primary" title="Get current location"><i
                                        class="fa-solid fa-location-crosshairs"></i></a>
                                <a id="autolocate" href="#" class="button is-danger"
                                    title="Auto-refresh current location"><i class="fa-solid fa-rotate"></i></a>
                            </p>
                        </div>
                    </div>
                </div>

            </article>
        </div>

        <div class="tile is-parent is-vertical has-text-white">
            <article class="tile is-child notification is-dark-half">

                @unless($system === null)

                    <div class="has-text-centered is-size-1 mb-4">
                        {{ $system->solarSystemName }}
                    </div>

                    <div class="has-text-centered mb-4" style="min-height: 280px">
                        <table class="table mx-auto">
                            <tbody>
                                <tr>
                                    <td>Security</td>
                                    <td><span class="security">{{ round($system->security, 2) }}</span></td>
                                </tr>
                                <tr>
                                    <td>Region</td>
                                    <td>{{ $system->regionName }}</td>
                                </tr>
                                <tr>
                                    <td>Constellation</td>
                                    <td>{{ $system->constellationName }}</td>
                                </tr>
                                <tr>
                                    <td>Rats</td>
                                    <td>{{ $system->rats }}</td>
                                </tr>

                                @if (isset($system->wormholeClass))
                                    <tr>
                                        <td>Wormhole Class</td>
                                        <td>{{ $system->wormholeClass }}</td>
                                    </tr>
                                    <tr>
                                        <td>Wormhole Statics</td>
                                        <td>
                                            @if (isset($system->wormholeStatics))
                                                @foreach ($system->wormholeStatics as $item)
                                                    <span style="margin-right:.25rem">
                                                        {{ $item->hole }}
                                                        (<span class="class-type" data-in-class="{{ $item->inClass }}"></span>)
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="has-text-centered mb-4">
                            <a class="button is-warning" target="_blank"
                                href="https://evemaps.dotlan.net/system/{{ urlencode($system->solarSystemName) }}">
                                DotLan Map
                            </a>
                            <a class="button is-black" target="_blank"
                                href="https://zkillboard.com/system/{{ urlencode($system->solarSystemID) }}">
                                zKillboard
                            </a>
                        </div>
                    </div>

                    

                    <div class="columns my-4">
                        <div class="column is-narrow scrollable mx-auto">
                            <table id="historyTable" class="table is-striped">
                                <tbody>
                                    @foreach ($history as $record)
                                        <tr>
                                            <td>{{ $record->createdAt }}</td>
                                            <th><a href="#" class="solar-system-link">{{ $record->solarSystemName }}</a></th>
                                            <td class="security">{{ round($record->solarSystemSecurity, 1) }}</td>
                                            <td>{{ $record->wormholeClass ?? null }}</td>
                                        </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column has-text-centered">
                            <div class="is-size-3">Nearest Trade Hubs</div>
                            @foreach ($jumps as $hubName => $jumps)
                                <span style="margin-right:1rem">{{ $hubName }}: <b>{{ $jumps }}</b></span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="columns">
                        <h5 class="column has-text-warning">
                            {{ $errorMessage }}
                        </h5>
                    </div>

                @endunless

            </article>
        </div>
    </div>

    <style>
        .scrollable {
            height: 20rem;
            overflow-y: auto;
        }
        .solar-system-link {
            text-decoration: none !important;
        }
        .class-type {
            font-weight: bold;
        }
    </style>
@endsection

@push('scripts')
    <script>
        const setLocationAutoUpdate = (enabled) => {
            if (enabled === true) {
                window.sessionStorage.autolocateInterval = setInterval(() => {
                    updateLocation();
                }, 10000);
                return;
            }
            if (window.sessionStorage.autolocateInterval) {
                clearInterval(window.sessionStorage.autolocateInterval);
            }
        };

        $(document).on("click", "#autolocate", function(e) {
            e.preventDefault();

            if (window.sessionStorage.hasOwnProperty("autolocate")) {
                window.sessionStorage.autolocate =
                    (window.sessionStorage.autolocate === 'true') ? 'false' : 'true';
                setAutoLocationBtnColor();
                setLocationAutoUpdate(
                    window.sessionStorage.autolocate !== 'false' ||
                    !window.sessionStorage.autolocateInterval
                );
                return false;
            }

            window.sessionStorage.autolocate = 'true';
            setAutoLocationBtnColor();
            return false;
        });

        $(document).on('click', '.solar-system-link', function(e) {
            e.preventDefault();
            window.location.href = '/system/' + $(this).text();
        });

        $(function() {
            $('[data-menu-item="system"]').addClass('active');

            formatValues();

            if (window.sessionStorage.autolocate === 'true') {
                setLocationAutoUpdate(true);
            }

            if (window.sessionStorage.locateOnLoad === 'true') {
                window.sessionStorage.locateOnLoad = false;
                updateLocation();
            }
        });
    </script>
@endpush
