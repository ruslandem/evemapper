<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eve Mapper</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/css/fontawesome.min.css">
    <link rel="stylesheet" href="/css/bulma.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    <!-- START NAV -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item is-size-5 has-text-weight-bold" href="#">
                    Eve Mapper
                </a>
                <span class="navbar-burger burger" data-target="navbarMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </div>
            <div id="navbarMenu" class="navbar-menu">
                <div class="navbar-end">
                    <a class="navbar-item" href="/">
                        Home
                    </a>
                    @unless(isset($sessionData['CharacterName']))
                        <a class="navbar-item" href="/auth">
                            <img src="/img/eve-sso-login-white-large.png" alt="Log in with EVE Online">
                        </a>
                    @else
                        <a class="navbar-item" href="/system">
                            System
                        </a>
                        <a class="navbar-item" href="/route">
                            Route
                        </a>
                        <div class="navbar-item dropdown is-right">
                            <div class="dropdown-trigger">
                                <button class="button" aria-haspopup="true" aria-controls="profile-menu">
                                    <span>{{ $sessionData['CharacterName'] }}</span>
                                    <span class="icon is-small">
                                        <i class="fas fa-angle-down" aria-hidden="true"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="dropdown-menu" id="profile-menu" role="menu">
                                <div class="dropdown-content">
                                    <a href="/logout" class="dropdown-item">
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endunless
                </div>
            </div>
        </div>
    </nav>
    <!-- END NAV -->

    <!-- Content starts -->
    @yield('content')
    <!-- Content ends -->

    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>Eve Mapper</strong>.
                &copy;2022 - All rights reserved. Fly safe ;-)
            </p>
        </div>
    </footer>

    <script src="/js/app.js"></script>

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

        $(document).on("keypress", "#search", function(e) {
            if (e.which == 13) {
                window.location.href = '/system/' + $(this).val();
                return false;
            }
        });

        $(function() {
            setAutoLocationBtnColor();
        });
    </script>

    @stack('scripts')
</body>

</html>
