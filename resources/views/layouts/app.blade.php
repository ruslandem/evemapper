<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eve Mapper</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    <ul id="authDropdown" class="dropdown-content">
        <li>
            <a href="/logout"><i class="material-icons tiny">exit_to_app</i> Logout</a>
        </li>
    </ul>
    <nav class="blue-grey darken-4" role="navigation">
        <div class="nav-wrapper container">

            <ul>
                <li>
                    <a class="main-title" href="/" class="amber-text lighten-4">Eve&nbsp;Mapper</a>
                </li>
                @unless(isset($sessionData['CharacterName']))
                    <li data-menu-item="home"><a href="/">Home</a></li>
                    <li class="right">
                        <a href="/auth" style="margin-top:.5rem">
                            <img src="\img\eve-sso-login-white-large.png" alt="Log in with EVE Online">
                        </a>
                    </li>
                @else
                    <li data-menu-item="home"><a href="/">Home</a></li>
                    <li data-menu-item="system"><a href="/system">System</a></li>
                    <li class="right">
                        <a class="dropdown-trigger" href="#!" data-target="authDropdown" title="Logged in as">
                            {{ $sessionData['CharacterName'] }}
                            <i class="material-icons right">arrow_drop_down</i>
                        </a>
                    </li>
                @endunless

            </ul>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <div id="progress" style="visibility:hidden">
        <div class="indeterminate"></div>
    </div>

    <!-- Content starts -->
    @yield('content')
    <!-- Content ends -->

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        window.sessionStorage.autolocateInterval = null;

        const setAutoLocationBtnColor = function() {
            if (window.sessionStorage.autolocate === 'true') {
                $('#autolocate').removeClass("red").addClass("green");
                return;
            }
            $('#autolocate').removeClass("green").addClass("red");
        };

        const updateLocation = () => {
            $.get('/locate')
                .done(function(response) {
                    if (response.solarSystemName && response.solarSystemName !=
                        '{{ $system->solarSystemName ?? '' }}') {
                        window.location.href = '/system/' + response.solarSystemName;
                        return false;
                    }
                })
                .fail(function(response) {
                    throw 'Failed to get location. Response: ' + response;
                    return false;
                });
        };

        $(document).on('click', '#locate', function(e) {
            e.preventDefault();
            updateLocation();
        });

        $(function() {
            $(".dropdown-trigger").dropdown({
                coverTrigger: false
            });

            setAutoLocationBtnColor();

            $(document).on("keypress", "#search", function(e) {
                if (e.which == 13) {
                    window.location.href = '/system/' + $(this).val();
                    return false;
                }
            });


        });
    </script>

    @stack('scripts')
</body>

</html>
