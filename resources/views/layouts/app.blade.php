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
    <nav class="blue-grey darken-4" role="navigation">
        <div class="nav-wrapper container"><a id="logo-container" href="/"
                class="brand-logo amber-text lighten-4">Eve&nbsp;Mapper</a>
            <ul class="right">
                <li>
                    @unless(isset($sessionData['CharacterName']))
                        <a href="/auth" style="margin-top:.5rem">
                            <img src="\img\eve-sso-login-white-large.png" alt="Log in with EVE Online">
                        </a>
                    @else
                        <a id="locate" href="/locate" class="btn-floating btn-small left"
                            style="margin-top:1rem;margin-right:2rem"><i class="material-icons">location_on</i></a>
                        <i class="material-icons left">account_box</i>
                        <span>{{ $sessionData['CharacterName'] }}</span>
                        <a href="/logout" class="btn">Logout</a>
                    @endunless
                </li>
            </ul>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <div class="container">
        <form class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="search" class="validate"
                        placeholder="Solar system name (e.g. Jita, J123456)"
                        value="{{ $system->solarSystemName ?? '' }}">
                </div>
            </div>
        </form>
    </div>

    <div id="progress" style="visibility:hidden">
        <div class="indeterminate"></div>
    </div>

    <!-- Content starts -->
    @yield('content')
    <!-- Content ends -->

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script>
        $(function() {
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
