@extends('layouts.app')

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
                            <div id="suggesstions" class="has-background-white has-text-black py-1 px-3"
                                style="display: none"></div>
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
            height: 80vh;
            overflow-y: auto;
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
        const getFromTemplate = (templateClass, replaces = {}) => {
            const template = $('#templates ' + templateClass).html();

            if (template) {
                let content = template;
                for (const property in replaces) {
                    content = content.split('%%' + property + '%%').join(replaces[property]);
                }
                return content;
            }

            throw 'template not found';
        };

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
            $.get('/locate')
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

            @if ($waypoints)
                @foreach ($waypoints as $name)
                    addWaypoint('{{ $name }}');
                @endforeach
            @endif

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
                    url: '/waypoint',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        system: name,
                    },
                }).fail(function(err) {
                    console.error(err);
                });
            });
        });
    </script>
@endpush
