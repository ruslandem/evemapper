@extends('layouts.app')

@section('content')
    <div class="container">
        <form class="col s12">
            <div class="row">
                <div class="input-field col s10">
                    <input type="text" id="search" class="validate" placeholder="Enter solar system name here"
                        value="{{ $system->solarSystemName ?? '' }}">
                </div>
                <div class="col s1">
                    <a id="locate" href="#" class="btn-floating btn-small"
                        style="margin-top:1rem;margin-right:2rem">
                        <i class="material-icons">location_on</i>
                    </a>
                </div>
                <div class="col s1">
                    <a id="autolocate" href="#" class="btn-floating btn-small"
                            style="margin-top:1rem;margin-right:0;padding-right:0"><i
                                class="material-icons">autorenew</i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="container">

        @unless($system === null)

            <div class="row">
                <h1 class="center s12 white-text">{{ $system->solarSystemName }}</h1>
            </div>

            <div class="row">
                <div class="col s6 offset-s3 blue-grey lighten-4" style="padding:0px">
                    <table class="striped">
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
                                                    (<span class="classType" data-in-class="{{ $item->inClass }}"></span>)
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col s12 center">
                    <a class="waves-effect waves-light btn yellow accent-4 black-text" target="_blank"
                        href="https://evemaps.dotlan.net/system/{{ urlencode($system->solarSystemName) }}">
                        DotLan Map
                    </a>
                    <a class="waves-effect waves-light btn deep-orange darken-4" target="_blank"
                        href="https://zkillboard.com/system/{{ urlencode($system->solarSystemID) }}">
                        zKillboard
                    </a>
                </div>
            </div>

            <div class="row">
                <div id="locationHistory" class="col s6 offset-s3 white" style="padding:1rem">
                    @foreach ($history as $record)
                        <div class="history-record left" style="width:100%">
                            {{ $record->createdAt }}
                            <span style="margin-left:.5rem;font-weight:bold;"><a
                                    href="/system/{{ $record->solarSystemName }}">{{ $record->solarSystemName }}</a></span>
                            <span class="security"
                                style="margin-left:.5rem;">{{ round($record->solarSystemSecurity, 1) }}</span>
                            <span style="margin-left:.5rem;">{{ $record->wormholeClass }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col s6 offset-s3 center grey">
                    <div style="font-weight:bold" class="white-text">Jumps to trade hubs:</div>
                    @foreach ($jumps as $hubName => $jumps)
                        <span style="margin-right:1rem">{{ $hubName }}<i
                                class="tiny material-icons">arrow_forward</i>{{ $jumps }}</span>
                    @endforeach
                </div>
            </div>
        @else
            <div class="row">
                <h5 class="col s12 center light orange-text darken-4">
                    {{ $errorMessage }}
                </h5>
            </div>

        @endunless

    </div>


    <style>
        .pure-table tr td:first-child {
            color: gray;
        }

        #locationHistory {
            height: 20rem;
            overflow-y: auto;
            background-color: rgba(242, 242, 242, 0.75) !important;
        }

        .history-record:hover {
            background-color: rgb(187, 187, 187);
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

        const formatValues = () => {
            // security status color
            $('.security').each(function() {
                let security = parseFloat($(this).text());
                $(this).css('color', '#00BFFF')
                if (security <= 0.8) {
                    $(this).css('color', '#008000');
                }
                if (security < 0.6) {
                    $(this).css('color', '#FFD700');
                }
                if (security < 0.5) {
                    $(this).css('color', '#FF8C00');
                }
                if (security < 0) {
                    $(this).css('color', '#FF0000');
                }
            });

            // statics format
            $('.classType').each(function() {
                let inClass = parseInt($(this).data('in-class'));

                $(this).text(function() {
                    switch (inClass) {
                        case 7:
                            $(this).addClass('green-text accent-4');
                            return "High";
                        case 8:
                            $(this).addClass('orange-text accent-4');
                            return "Low";
                        case 9:
                            $(this).addClass('red-text accent-4');
                            return "Null";
                        case 12:
                            $(this).addClass('teal-text accent-4');
                            return "Thera";
                    }

                    $(this).addClass('light-blue-text accent-4');
                    return "C" + inClass;
                });
            });
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

        $(function() {
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
