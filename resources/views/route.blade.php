@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns has-text-white">

            <div class="column is-half">

                <div class="tile is-parent is-vertical">
                    <article class="tile is-child notification is-dark-half">
                        <p class="title has-text-white">Waypoints Route</p>
                        <p class="subtitle has-text-white">Calculates optimal route though given waypoints</p>

                        <div class="field is-horizontal">
                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input id="solarSystem" class="input" type="text" placeholder="Solar system"
                                            value="{{ $system->solarSystemName ?? '' }}">
                                    <div id="suggesstions" class="has-background-white has-text-black py-1 px-3"
                                        style="display: none"></div>
                                    </p>
                                </div>
                                <div class="field">
                                    <p class="control is-expanded">
                                        <a id="addBtn" href="#" class="button is-primary" title="Add">add</a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="m-4 px-2 has-text-warning has-text-weight-bold is-size-5	">
                            <ol id="waypointsList"></ol>


                        </div>
                        <div>
                            <a id="clearBtn" href="#" class="button is-danger">clear</a>
                            <a id="routeBtn" href="#" class="button is-primary">route</a>
                        </div>

                    </article>
                </div>

            </div>

            <div class="column is-half">
                <div id="routeResult" class="is-dark-half"></div>

            </div>
        </div>

    </div>
    <style>
        #routeResult {
            padding: 2rem;
        }

        #routeResult ol {
            margin-left: 3rem;
        }

        #suggesstions {
            max-height: 200px;
            overflow-y: auto;
            position: fixed;
            margin-top: .25rem;
            z-index: 999;
        }

        #suggesstions>div:hover {
            background-color: #efefef;
            cursor: pointer;
        }
    </style>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#solarSystem").keyup(function() {
                $.post({
                    url: '/systemlist',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        search: $(this).val()
                    },
                }).done(function(response) {
                    $("#suggesstions").html("");
                    response.systems.forEach(element => {
                        $("#suggesstions").append("<div>" + element + "</div>")
                    });
                    $("#suggesstions").show();
                });
            });

            $(document).on('click', '#suggesstions>div', function(e) {
                $('#solarSystem').val($(this).text());
                $("#suggesstions").hide();
            });

            $(document).on('click', '#addBtn', function(e) {
                e.preventDefault();

                let name = $('#solarSystem').val().trim();
                $('#solarSystem').val("")

                $('#waypointsList li').each(function() {
                    if ($(this).text() == name) {
                        Toastify({
                            text: "waypoint already exists",
                            duration: 3000
                        }).showToast();
                        name = "";
                        return false;
                    }
                });

                if (name) {
                    $('#waypointsList').append('<li><span class="waypoint-item">' + name + '</span><button class="delete m-1"></button></li>');
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

                const waypoints = $('#waypointsList .waypoint-item').toArray().map(p => p.innerHTML);

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

                    if (response.error) {
                        Toastify({
                            text: response.error,
                            duration: 3000
                        }).showToast();
                        return;
                    }

                    response.route.forEach((path, pathIndex, array) => {
                        let list = $('<ol></ol>');
                        if (pathIndex === array.length - 1) {
                            path[0] += ' <sup>[return route]</sup>';
                        }
                        $('#routeResult')
                            .append('<div class="is-size-4 has-text-warning">' + path[0] +
                                '</div>')
                            .append(list);
                        path.forEach((waypoint, waypointIndex) => {
                            if (waypointIndex > 0) {
                                list.append('<li>' + waypoint + ' (<span class="security">' + Math.round(response.info[pathIndex][waypointIndex]['security'] * 10) / 10 + '</span>)</li>');
                            }
                        });
                        $('#routeResult').append('</ol>');
                        formatValues();
                    });

                });
            });
        });
    </script>
@endpush
