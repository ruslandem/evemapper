<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WH Ident</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/pure-min.css"
        integrity="sha384-X38yfunGUhNzHpBaEBsWLO+A0HDYOQi8ufWDkZ0k9e0eXz/tH3II7uKZ9msv++Ls" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/purecss@3.0.0/build/grids-responsive-min.css">

    <link rel="stylesheet" href="/css/app.css">

    
</head>

<body>

    <div id="layout" class="pure-g">

        <div class="sidebar pure-u-1 pure-u-md-1-4">
            <div class="header">
                <img src="/img/planet.svg" style="width:50px;height:50px;color:white">
                <h1 class="brand-title">WH Ident</h1>

                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a class="pure-button" href="/">Main</a>
                        </li>
                        <li class="nav-item">
                            <a class="pure-button" href="/system/J000000">System</a>
                        </li>
                    </ul>
                </nav>

                <nav class="nav">
                    <ul class="nav-list">
                        <li class="nav-item">
                            @unless(App\Core\EveAuth::isAuthenticated())
                                <a class="pure-button" href="/auth">SSO Login</a>
                            @else
                                <a class="pure-button" href="/locate">Get Location</a>
                                <a class="pure-button" href="/clear">Logout</a>
                            @endunless
                        </li>

                    </ul>
                </nav>
            </div>
        </div>

        <div class="content pure-u-1 pure-u-md-3-4">

            @yield('content')

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    @stack('scripts')

</body>

</html>
