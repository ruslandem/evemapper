@extends('layouts.app')
@section('title', '- Route')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered m-3">Route Calculator</h1>
        <div class="columns has-text-white p-3">
            <x-route.waypointsForm />
            <x-route.routeResult />
        </div>
    </div>

    <x-route.waypointsTemplates />
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
