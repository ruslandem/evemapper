@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s6">
                <div class="row">
                    <div class="input-field col">
                        <input id="solarSystem" type="text" class="validate" placeholder="Solar system" value="">
                    </div>
                    <div class="input-field col">
                        <a id="addBtn" href="#" class="btn"><i class="material-icons left">add</i> add</a>
                    </div>
                </div>
                <div class="row">
                    <ol id="waypointsList">
                        <li>Ohmahailen</li>
                        <li>Obanen</li>
                        <li>Arera</li>
                        <li>Isseras</li>
                        <li>Nasreri</li>
                    </ol>
                    <a id="clearBtn" href="#" class="btn red lighten-2"><i class="material-icons left">delete</i>
                        clear</a>
                    <a id="routeBtn" href="#" class="btn"><i class="material-icons left">call_split</i> route</a>
                </div>
            </div>
            <div class="col s6 yellow-text">
                <div id="routeResult"></div>
            </div>
        </div>

    </div>
    <style>
        #routeResult {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 1rem;
        }

        #waypointsList li {
            font-size: 125%;
            font-weight: bold;
            color: yellowgreen;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(function() {
            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                let name = $('#solarSystem').val().trim();
                $('#solarSystem').val("")

                $('#waypointsList li').each(function() {
                    if ($(this).text() == name) {
                        M.toast({
                            html: 'Waypoint already exists!'
                        });
                        name = "";
                        return false;
                    }
                });

                if (name) {
                    $('#waypointsList').append('<li>' + name + '</li>');
                }
            });

            $(document).on('click', '#clearBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('#waypointsList').html("");
                $('#routeResult').html("");
            });

            $(document).on('click', '#routeBtn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const waypoints = $('#waypointsList li').toArray().map(p => p.innerHTML);

                $.post({
                    url: '/route',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        waypoints: waypoints,
                    },
                }).done(function(response) {
                    $('#routeResult').html("");

                    response.route.forEach((path, pathIndex, array) => {
                        let list = $('<ol></ol>');
                        if (pathIndex === array.length - 1) {
                            path[0] += ' <sup>[return route]</sup>';
                        }
                        $('#routeResult')
                            .append('<h5>' + path[0] + '</h5>')
                            .append(list);
                        path.forEach((waypoint, waypointIndex) => {
                            if (waypointIndex > 0) {
                                list.append('<li>' + waypoint + '</li>');
                            }
                        });
                        $('#routeResult').append('</ol>');
                    });

                });
            });
        });
    </script>
@endpush
