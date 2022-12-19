<div class="column is-one-third is-dark-1" style="height:100%">
    <h5 class="subtitle has-text-white">Locations History</h5>
    <div class="scrollable">
        <table id="historyTable" class="table is-striped">
            <tbody>
                @foreach ($history as $record)
                    <tr>
                        <td>{{ $record->createdAt }}</td>
                        <th><a href="#" class="solar-system-link">{{ $record->solarSystemName }}</a>
                        </th>
                        <td class="security">{{ round($record->solarSystemSecurity, 1) }}</td>
                        <td>{{ $record->wormholeClass ?? null }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>