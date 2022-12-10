@extends('layouts.app')
@section('title', '- Locator')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered m-3">Locator</h1>

        <x-locator.search-bar system="{{ $system->solarSystemName ?? '' }}" />

        <div class="columns has-text-white p-3">
            <div class="column auto is-dark-half mr-2">
                @unless($system === null)
                    <x-locator.systemInfo :system="$system" />

                    <x-locator.hubs system="{{ $system->solarSystemName ?? '' }}" :jumps="$jumps" />

                    <x-locator.signatures />
                @else
                    <div class="columns">
                        <h5 class="column has-text-warning">
                            {{ $errorMessage }}
                        </h5>
                    </div>
                @endunless
            </div>

            <x-locator.history :history="$history" />
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
        const autoLocationInterval = 20000;

        const signaturesTable = $('#signatureTable').signaturesTable({
            url: {
                get: "{{ route('api.getSignatures', ['system' => $system->solarSystemName ?? '']) }}",
                update: "{{ route('api.updateSignatures') }}",
                delete: "{{ route('api.deleteSignatures') }}",
            }
        });

        $(document).on("click", ".delete", (e) => {
            e.preventDefault();

            const id = $(e.currentTarget).parents('tr').children('td').first().text();

            if (id) {
                signaturesTable.delete({
                    solarSystem: currenSolarSystem,
                    id: id
                });
            }
        });

        const updateLocation = () => {
            $('#searchBar a').attr('disabled', true);
            $('#searchBar i.fa-rotate').addClass('fa-spin');

            $.get('{{ route('api.locate') }}')
                .done(function(response) {
                    if (
                        response.solarSystemName &&
                        response.solarSystemName != currenSolarSystem
                    ) {
                        return openSystemPage(response.solarSystemName);
                    }
                    $('#searchBar a').attr('disabled', false);
                    $('#searchBar i.fa-rotate').removeClass('fa-spin');
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

            signaturesTable.show({
                highlightNewSignatures: false
            });
        });

        $(document).on('click', '#updateSignatures', function(e) {
            e.preventDefault();
            signaturesTable.update({
                solarSystem: currenSolarSystem
            });

        });

        $(document).on('click', '#replaceSignatures', function(e) {
            e.preventDefault();
            signaturesTable.update({
                solarSystem: currenSolarSystem,
                replace: true
            });
        });
    </script>
@endpush
