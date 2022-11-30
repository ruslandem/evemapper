@extends('layouts.app')
@section('title', '- Route')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered m-3">Route Calculator</h1>

        <div class="columns has-text-white p-3">

            {{-- Waypoints Form Start --}}
            <div class="column is-one-third is-dark-half mr-2">

                <div class="content">
                    <p class="title is-3 has-text-white">Waypoints</p>
                    <p class="subtitle is-6 has-text-white">Enter waypoints you plan to visit:</p>

                    <div class="field has-addons">
                        <div class="control">
                            <input id="solarSystem" class="input" type="text" placeholder="Jita"
                                value="{{ $system->solarSystemName ?? '' }}">
                            <div class="suggestions has-background-white has-text-black py-1 px-3" style="display: none">
                            </div>
                        </div>
                        <div class="control">
                            <a class="button is-primary" id="addBtn" title="Add solar system">
                                add
                            </a>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <ol id="waypointsList"></ol>
                </div>

                <div class="content">
                    <a id="clearBtn" href="#" class="button is-danger">clear</a>
                    <a id="routeBtn" href="#" class="button is-success">route</a>
                </div>
            </div>
            {{-- Waypoints Form End --}}

            {{-- Route Start --}}
            <div class="results-wrapper column auto is-dark-half">
                <p class="title is-3 has-text-white">Optimal route</p>
                <div class="content">
                    <span id="routeResult">No route waypoints</span>
                </div>
            </div>
            {{-- Route End --}}

        </div>

    </div>
    <style>
        .results-wrapper {
            height: 100%;
        }

        .results-wrapper>.content {
            height: 70vh;
            overflow-y: auto;
        }
    </style>

    <div id="templates" style="display:none">

        <div class="waypoint-template">
            <li>
                <span class="waypoint-item">%%name%%</span>
                <button class="delete is-small m-1"></button>
            </li>
        </div>

        <div class="result-title">
            <span class="is-size-4 has-text-warning tag">
                <a href="#" class="mx-2" data-waypoint-link="%%name%%">
                    <i class="fa-solid fa-location-dot"></i>
                </a>
                %%name%%
                <span class="tag is-size-6" style="display:%%returnRoute%%">(return route)</span>
            </span>
        </div>

        <div class="result-waypoint">
            <li>
                %%name%%
                (<span class="security">%%security%%</span>)
            </li>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const addWaypoint = (name) => {
            let exists = false;
            if (name) {
                $('#waypointsList .waypoint-item').each(function() {
                    if ($(this).text() == name) {
                        exists = true;
                        return false;
                    }
                });

                if (!exists) {
                    const content = getFromTemplate('.waypoint-template', {
                        'name': name
                    });
                    $('#waypointsList').append(content);
                }
            }

            return !exists;
        };

        $(function() {
            // get current location on page load
            $('#solarSystem').attr('readonly', true).val('loading...');
            $('#addBtn').attr('disabled', true);
            $.get('{{ route('api.locate') }}')
                .done(function(response) {
                    if (response.solarSystemName) {
                        $('#solarSystem').val(response.solarSystemName);
                        $('#solarSystem').attr('readonly', false);
                        $('#addBtn').attr('disabled', false);
                        return false;
                    }
                })
                .fail(function(response) {
                    throw 'Failed to get location. Response: ' + response;
                    return false;
                });

            $('#solarSystem').solarSystemSelector('{{ route('api.systems') }}');

            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();

                const name = $('#solarSystem').val().trim();
                $('#solarSystem').val("");

                if (!addWaypoint(name)) {
                    toast('waypoint already exists');
                }
            });

            $(document).on('click', '#clearBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('#waypointsList').html("");
                $('#routeResult').html("");
            });

            $(document).on('click', '#waypointsList li .delete', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parent('li').remove();
            });

            $(document).on('click', '#routeBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const waypoints = $('#waypointsList .waypoint-item')
                    .toArray()
                    .map(p => p.innerHTML);

                $.post({
                    url: '{{ route('api.route') }}',
                    headers: {
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    data: {
                        waypoints: waypoints,
                    },
                }).done(function(response) {
                    $('#routeResult').html("");

                    if (response.error) {
                        Toastify({
                            text: response.error,
                            duration: 3000
                        }).showToast();
                        return;
                    }

                    const numOptions = {
                        minimumFractionDigits: 1,
                        maximumFractionDigits: 1
                    };

                    response.route.forEach((path, pathIndex, array) => {
                        let list = $('<ol></ol>');

                        path.forEach((waypoint, waypointIndex) => {
                            if (waypointIndex > 0) {
                                const security =
                                    response.info[pathIndex][waypointIndex][
                                        'security'
                                    ];

                                const replaces = {
                                    'name': waypoint,
                                    'security': new Intl.NumberFormat('en-US',
                                            numOptions)
                                        .format(security)
                                };

                                list.append(
                                    getFromTemplate('.result-waypoint',
                                        replaces)
                                );
                            }
                        });

                        $('#routeResult')
                            .append(
                                getFromTemplate('.result-title', {
                                    'name': path[0],
                                    'returnRoute': (pathIndex === (array.length - 1)) ?
                                        'visible' : 'none',
                                })
                            )
                            .append(list);

                        formatValues();
                    });

                });
            });

            $(document).on('click', 'a[data-waypoint-link]', function(e) {
                e.preventDefault();
                const name = $(this).data('waypoint-link');
                $.post({
                        url: '{{ route('api.waypoint') }}',
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        data: {
                            system: name,
                        },
                    }).done(() => {
                        Toastify({
                            text: `Added waypoint: ${name}`,
                            duration: 3000
                        }).showToast();
                    })
                    .fail((err) => {
                        throw err;
                    });
            });

            @if ($waypoints)
                @foreach ($waypoints as $name)
                    addWaypoint('{{ $name }}');
                @endforeach
                $('#routeBtn').trigger('click');
            @endif
        });
    </script>
@endpush
