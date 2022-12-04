@extends('layouts.app')
@section('title', '- Locator')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered m-3">Locator</h1>

        <div class="columns has-text-white p-3">
            <div class="column is-dark-half">
                <div class="content">
                    <p class="title is-3 has-text-white">Solar System</p>
                    <p class="subtitle is-6 has-text-white">Search for a solar system and track your character location:</p>

                    <div id="searchBar" class="field has-addons">
                        <div class="control">
                            <input id="search" class="input" type="text" placeholder="Jita" autocomplete="off"
                                value="{{ $system->solarSystemName ?? '' }}">
                            <div class="suggestions has-background-white has-text-black py-1 px-3" style="display: none">
                            </div>
                        </div>
                        <div class="control">
                            <a class="button is-primary" id="searchBtn" title="Find solar system"><i
                                    class="fa-solid fa-search mr-2"></i>search</a>
                        </div>
                        <div class="control">
                            <a id="locate" href="#" class="button is-warning" title="Get current location"><i
                                    class="fa-solid fa-location-crosshairs"></i></a>
                        </div>
                        <div class="control">
                            <a id="autolocate" href="#" class="button is-danger"
                                title="Auto-refresh current location"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="columns has-text-white p-3">
            <div class="column auto is-dark-half mr-2">
                @unless($system === null)

                    <div class="has-text-centered is-size-1 mb-4">
                        {{ $system->solarSystemName }}
                    </div>

                    <div class="has-text-centered mb-4">
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
                                href="https://evemaps.dotlan.net/map/{{ str_replace('+', '_', urlencode($system->regionName)) }}/{{ str_replace('+', '_', urlencode($system->solarSystemName)) }}#sec">
                                DotLan Map <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
                            </a>
                            <a class="button is-black" target="_blank"
                                href="https://zkillboard.com/system/{{ urlencode($system->solarSystemID) }}">
                                zKillboard <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column has-text-centered">
                            <h5 class="title">Nearest Trade Hubs</h5>
                            @foreach ($jumps as $hubName => $jumps)
                                <span class="tag is-medium is-info m-1">{{ $hubName }}: {{ $jumps }}
                                    <a href="/route?waypoints={{ $system->solarSystemName }},{{ $hubName }}"
                                        class="has-text-warning"><i class="fa-solid fa-route ml-1"></i></a>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="columns">
                        <div class="column has-text-centered">
                            <h5 class="title">Cosmic Signatures</h5>

                            <div class="table-container p-5">
                                <table id="signatureTable" class="table is-fullwidth is-bordered is-size-7">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Group</th>
                                            <th>Created</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <div class="m-5">
                                <a id="updateSignatures" href="#" class="button">update</a>
                                <a id="replaceSignatures" href="#" class="button">replace</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="columns">
                        <h5 class="column has-text-warning">
                            {{ $errorMessage }}
                        </h5>
                    </div>

                @endunless
            </div>

            <div class="column is-one-third is-dark-half" style="height:100%">
                <h5 class="subtitle has-text-white">Locations History</h5>
                <div class="scrollable">
                    <table id="historyTable" class="table is-striped">
                        <tbody>
                            @foreach ($history as $record)
                                <tr>
                                    <td>{{ $record->createdAt }}</td>
                                    <th><a href="#" class="solar-system-link">{{ $record->solarSystemName }}</a>
                                    </th>
                                    <td class="security">{{ round($record->solarSystemSecurity, 1) }}</td>
                                    <td>{{ $record->wormholeClass ?? null }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <style>
        .scrollable {
            height: 62vh;
            overflow-y: auto;
        }

        .solar-system-link {
            text-decoration: none !important;
        }
    </style>
@endsection

@push('scripts')
    <script>
        const currenSolarSystem = "{{ $system->solarSystemName ?? '' }}";

        const seedSignaturesTable = (data, highlight = false) => {
            const oldSignatures = $('#signatureTable td:nth-child(1)').
            map(function() {
                return $(this).text();
            }).get();

            let tableBody = $('#signatureTable tbody');
            tableBody.text("");
            data.forEach(element => {
                let row = $("<tr" + (highlight && !oldSignatures.includes(element.signatureId) ?
                    ' class="has-background-warning-dark"' : "") + "></tr>");
                row.append(`<td>${element.signatureId}</td>`);
                row.append(`<td>${element.signatureName}</td>`);
                row.append(`<td>${element.groupName}</td>`);
                row.append(`<td>${element.created_at}</td>`);
                row.append(`<td><button class="delete"></button></td>`);
                tableBody.append(row);
            });
        };

        const getSignatures = () => {
            $.get({
                    url: "{{ route('api.getSignatures', ['system' => $system->solarSystemName]) }}"
                })
                .done(response => {
                    if (response.signatures) {
                        seedSignaturesTable(response.signatures);
                    }
                });
        };

        const updateSignatures = (replace = false) => {
            navigator.clipboard.readText()
                .then(text => {
                    $.post({
                            url: "{{ route('api.updateSignatures') }}",
                            headers: {
                                "X-CSRF-TOKEN": getCsrfToken(),
                            },
                            data: {
                                solarSystemName: currenSolarSystem,
                                text: text,
                                replace: replace,
                            }
                        })
                        .done(response => {
                            if (response.signatures) {
                                seedSignaturesTable(response.signatures, true);
                            }
                            if (response.updated) {
                                Toastify({
                                    text: `Updated ${response.updated} signatures`,
                                    duration: 3000
                                }).showToast();
                            }
                        });

                });
        };

        const deleteSignature = (signatureId) => {
            $.post({
                    type: "delete",
                    url: "{{ route('api.deleteSignatures') }}",
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                    },
                    data: {
                        solarSystemName: currenSolarSystem,
                        signatureId: signatureId,
                    }
                })
                .done(response => {
                    if (response.deleted) {
                        getSignatures();
                        Toastify({
                            text: `Deleted signature ${signatureId}`,
                            duration: 3000
                        }).showToast();
                    }
                });
        };

        $(document).on("click", ".delete", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).parents('tr').children('td').first().text();
            console.log(e);
            console.log(id);
            if (id) {
                deleteSignature(id);
            }
        });

        const updateLocation = () => {
            $('#searchBar a').attr('disabled', true);

            $.get('{{ route('api.locate') }}')
                .done(function(response) {
                    if (
                        response.solarSystemName &&
                        response.solarSystemName != currenSolarSystem
                    ) {
                        return openSystemPage(response.solarSystemName);
                    }
                    $('#searchBar a').attr('disabled', false);
                })
                .fail(function(response) {
                    throw 'Failed to get location (' + response + ')';
                });
        };

        const openSystemPage = (systemName) => {
            window.location.href = '{{ route('locate') }}/' + systemName;
            return false;
        };

        // Auto-location
        const autoLocationInterval = 10000;
        window.sessionStorage.removeItem('autolocateInterval');

        const getAutoLocationState = () => {
            if (window.sessionStorage.key)
                if (!window.sessionStorage.hasOwnProperty("autolocate")) {
                    window.sessionStorage.autolocate = 'false';
                    return false;
                }
            return window.sessionStorage.autolocate === 'true';
        };

        const setAutoLocationState = (state) => {
            window.sessionStorage.autolocate = (state ? 'true' : 'false');
            if (state) {
                $('#autolocate')
                    .removeClass("has-background-danger	")
                    .addClass("has-background-success");
            } else {
                $('#autolocate')
                    .removeClass("has-background-success")
                    .addClass("has-background-danger");
            }
        };

        const setAutoLocation = (state) => {
            if (state === true) {
                window.sessionStorage.autolocateInterval = setInterval(() => {
                    updateLocation();
                }, autoLocationInterval);
            } else {
                if (window.sessionStorage.autolocateInterval) {
                    clearInterval(window.sessionStorage.autolocateInterval);
                    window.sessionStorage.removeItem('autolocateInterval');
                }
            }
            setAutoLocationState(state);
        };

        $('#search').solarSystemSelector('{{ route('api.systems') }}');

        $(document).on('click', '#locate', function(e) {
            e.preventDefault();
            updateLocation();
        });

        $(document).on("click", "#autolocate", function(e) {
            e.preventDefault();

            const currentState = getAutoLocationState();
            setAutoLocation(!currentState);

            Toastify({
                text: "Autolocation " + (!currentState ? "on" : "off"),
                duration: 3000
            }).showToast();

            $(this).blur();
        });

        $(document).on('click', '.solar-system-link', function(e) {
            e.preventDefault();
            window.location.href = '{{ route('locate') }}/' + $(this).text();
        });

        $(document).on("keypress", "#search", function(e) {
            if (e.which == 13) {
                return openSystemPage($(this).val());
            }
        });

        $(document).on("click", "#searchBtn", function(e) {
            return openSystemPage($('#search').val());
        });

        $(function() {
            $('[data-menu-item="system"]').addClass('active');

            formatValues();

            setAutoLocation(
                getAutoLocationState()
            );

            getSignatures();
        });

        $(document).on('click', '#updateSignatures', function(e) {
            e.preventDefault();
            updateSignatures();
        });

        $(document).on('click', '#replaceSignatures', function(e) {
            e.preventDefault();
            updateSignatures(true);
        });
    </script>
@endpush
