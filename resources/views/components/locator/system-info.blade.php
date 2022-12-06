<div class="has-text-centered is-size-1 mb-4">
    {{ $system->solarSystemName }}
</div>

<div class="has-text-centered mb-4">
    <table class="table mx-auto">
        <tbody>
            <tr>
                <td>Security</td>
                <td><span class="security">{{ round($system->security, 2) }}</span></td>
            </tr>
            <tr>
                <td>Region</td>
                <td>{{ $system->regionName }}</td>
            </tr>
            <tr>
                <td>Constellation</td>
                <td>{{ $system->constellationName }}</td>
            </tr>
            <tr>
                <td>Rats</td>
                <td>{{ $system->rats }}</td>
            </tr>

            @if (isset($system->wormholeClass))
                <tr>
                    <td>Wormhole Class</td>
                    <td>{{ $system->wormholeClass }}</td>
                </tr>
                <tr>
                    <td>Wormhole Statics</td>
                    <td>
                        @if (isset($system->wormholeStatics))
                            @foreach ($system->wormholeStatics as $item)
                                <span style="margin-right:.25rem">
                                    {{ $item->hole }}
                                    (<span class="class-type" data-in-class="{{ $item->inClass }}"></span>)
                                </span>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="has-text-centered mb-4">
        <a class="button is-warning" target="_blank"
            href="https://evemaps.dotlan.net/map/{{ str_replace('+', '_', urlencode($system->regionName)) }}/{{ str_replace('+', '_', urlencode($system->solarSystemName)) }}#sec">
            DotLan Map <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
        <a class="button is-black" target="_blank"
            href="https://zkillboard.com/system/{{ urlencode($system->solarSystemID) }}">
            zKillboard <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
    </div>
</div>