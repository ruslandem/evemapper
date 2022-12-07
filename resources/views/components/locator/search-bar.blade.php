<div class="columns has-text-white p-3">
    <div class="column is-dark-half">
        <div class="content">
            <p class="title is-3 has-text-white">Solar System</p>
            <p class="subtitle is-6 has-text-white">Search for a solar system and track your character location:</p>

            <div id="searchBar" class="field has-addons">
                <div class="control">
                    <input id="search" class="input" type="text" placeholder="Jita" autocomplete="off"
                        value="{{ $system }}">
                    <div class="suggestions has-background-white has-text-black py-1 px-3" style="display: none">
                    </div>
                </div>
                <div class="control">
                    <a class="button is-primary" id="searchBtn" title="Find solar system"><i
                            class="fa-solid fa-search mr-2"></i>search</a>
                </div>
                <div class="control">
                    <a id="locate" href="#" class="button is-warning" title="Get current location"><i
                            class="fa-solid fa-location-crosshairs"></i></a>
                </div>
                <div class="control">
                    <a id="autolocate" href="#" class="button is-danger"
                        title="Auto-refresh current location"><i class="fa-solid fa-rotate"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>