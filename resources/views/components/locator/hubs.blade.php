<div class="columns">
    <div class="column has-text-centered">
        <h5 class="title">Nearest Trade Hubs</h5>
        @foreach ($jumps as $hubName => $jumps)
            <span class="tag is-medium is-info m-1">{{ $hubName }}: {{ $jumps }}
                <a href="/route?waypoints={{ $system }},{{ $hubName }}"
                    class="has-text-warning"><i class="fa-solid fa-route ml-1"></i></a>
            </span>
        @endforeach
    </div>
</div>