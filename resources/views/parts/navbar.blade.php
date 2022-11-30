<!-- Navbar start -->
<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a class="navbar-item is-size-3 has-text-weight-bold logo" href="/">
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
                <a class="navbar-item" href="/system">
                    System
                </a>
                <a class="navbar-item" href="/route">
                    Route
                </a>
                @unless(Auth::check())
                    <a class="navbar-item" href="/auth">
                        <img src="/img/eve-sso-login-white-large.png" alt="Log in with EVE Online">
                    </a>
                @else
                    <div class="navbar-item dropdown is-right">
                        <div class="dropdown-trigger">
                            <button class="button" aria-haspopup="true" aria-controls="profile-menu">
                                <span><img class="mt-2 mr-3"
                                        src="https://image.eveonline.com/Character/{{ Auth::id() }}_32.png" /></span>
                                <span>{{ Auth::user()->characterName }}</span>
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
<!-- Navbar end -->