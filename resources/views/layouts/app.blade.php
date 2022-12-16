<!DOCTYPE html>
<html lang="en">

<head>
    <title>Eve Mapper @yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="keywords" content="evemapper, eve online, eve, eve market, route, evemaps" />
    <meta property="og:title" content="Eve Mapper @yield('title')" />
    <meta property="og:description" content="EveMapper is an online routing tool and resource database for Eve Online" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ asset('') }}">
    <link rel="stylesheet" href="/css/fontawesome.min.css">
    <link rel="stylesheet" href="/css/bulma.min.css">
    <link rel="stylesheet" href="/css/toastify.css">
    <link rel="stylesheet" href="/css/tippy/tippy.css">
    <link rel="stylesheet" href="/css/tippy/themes/light.css">
    <link rel="stylesheet" href="/css/bulmaswatch.min.css">
    <link rel="stylesheet" href="/css/cookieconsent.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    @include('parts.navbar')

    <!-- Content start -->
    @yield('content')
    <!-- Content end -->

    @include('parts.footer')

    <script src="/js/app.js"></script>
    <script src="/js/cookie-consent.js"></script>

    @stack('scripts')

    {!! GoogleReCaptchaV3::init() !!}

    @include('parts.google-tag')
</body>

</html>
