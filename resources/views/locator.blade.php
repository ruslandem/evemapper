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
        var signaturesTable, locator;

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

        $(document).on('click', '#locate', function(e) {
            e.preventDefault();
            locator.update();
        });

        $(document).on("click", "#autolocate", function(e) {
            e.preventDefault();

            locator.toggleAutoLocation();

            $(this).blur();
        });

        $(document).on('click', '.solar-system-link', function(e) {
            e.preventDefault();

            window.location.href = '{{ route('locate') }}/' + $(this).text();

            return false;
        });

        $(document).on("keypress", "#search", function(e) {
            if (e.which == 13) {
                window.location.href = '{{ route('locate') }}/' + $(this).val();
                return false;
            }
        });

        $(document).on("click", "#searchBtn", function(e) {
            window.location.href = '{{ route('locate') }}/' + $('#search').val();
            return false;
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

        $(function() {
            $('#search').solarSystemSelector('{{ route('api.systems') }}');

            locator = $('#searchBar').locator({
                requestUrl: "{{ route('api.locate') }}",
                callbackUrl: "{{ route('locate') }}",
                currenSolarSystem: currenSolarSystem
            });

            signaturesTable = $('#signatureTable').signaturesTable({
                url: {
                    get: "{{ route('api.getSignatures', ['system' => $system->solarSystemName ?? '']) }}",
                    update: "{{ route('api.updateSignatures') }}",
                    delete: "{{ route('api.deleteSignatures') }}",
                }
            });

            signaturesTable.show({
                highlightNewSignatures: false
            });
        });
    </script>
@endpush
