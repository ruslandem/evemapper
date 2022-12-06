@extends('layouts.app')
@section('title', '- Locator')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered m-3">Locator</h1>

        <x-locator.search-bar system="{{ $system->solarSystemName ?? '' }}"/>

        <div class="columns has-text-white p-3">
            <div class="column auto is-dark-half mr-2">
                @unless($system === null)
                    <x-locator.systemInfo :system="$system"/>

                    <x-locator.hubs system="{{ $system->solarSystemName }}" :jumps="$jumps"/>

                    <x-locator.signatures/>
                @else
                    <div class="columns">
                        <h5 class="column has-text-warning">
                            {{ $errorMessage }}
                        </h5>
                    </div>

                @endunless
            </div>

            <x-locator.history :history="$history"/>
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

        const seedSignaturesTable = (data, highlight = true) => {
            const oldSignatures = $('#signatureTable tbody td:nth-child(1)').
            map(function() {
                return $(this).text();
            }).get();

            let tableBody = $('#signatureTable tbody');
            tableBody.text("");
            data.forEach(element => {
                let row = $("<tr" + (highlight && !oldSignatures.includes(element.signatureId) ?
                    ' class="new-signature"' : "") + "></tr>");
                row.append(`<td>${element.signatureId}</td>`);
                row.append(`<td>${element.groupName}</td>`);
                row.append(`<td>${element.signatureName}</td>`);
                row.append(`<td title="${hdate.prettyPrint(element.created_at, {showTime: true})}">${hdate.relativeTime(element.created_at)}</td>`);
                row.append(`<td><button class="delete"></button></td>`);
                tableBody.append(row);
            });
        };

        const getSignatures = (highlight = true) => {
            $.get({
                    url: "{{ route('api.getSignatures', ['system' => $system->solarSystemName ?? '']) }}"
                })
                .done(response => {
                    if (response.data) {
                        seedSignaturesTable(response.data, highlight);
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
                            if (response) {
                                toast(`${response.updated ?? 0} added and ${response.created ?? 0} updated signatures`);
                                getSignatures();
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
                    if (response) {
                        toast(`Deleted signature ${signatureId}`);
                        getSignatures();
                    }
                });
        };

        $(document).on("click", ".delete", (e) => {
            e.preventDefault();
            const id = $(e.currentTarget).parents('tr').children('td').first().text();
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

            toast("Autolocation " + (!currentState ? "on" : "off"));

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

            getSignatures(false);
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
