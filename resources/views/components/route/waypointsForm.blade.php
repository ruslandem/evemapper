<div class="column is-one-third is-dark-half mr-2">

    <div class="content">
        <p class="title is-3 has-text-white">Waypoints</p>
        <p class="subtitle is-6 has-text-white">Enter waypoints you plan to visit:</p>

        <div class="field has-addons">
            <div class="control">
                <input id="solarSystem" class="input" type="text" placeholder="Jita" value="">
                <div class="suggestions has-background-white has-text-black py-1 px-3" style="display: none">
                </div>
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