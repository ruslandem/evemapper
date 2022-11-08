@extends('layouts.app')

@section('content')
    <div class="container">

        <h1>Wormhole Search</h1>

        <div class="search">
            <form class="pure-form">
                <fieldset>
                    <input type="text" id="system" value="{{ $system->system ?? null }}" placeholder="000000" maxlength="7" autofocus="true" />
                    <button type="button" id="search-btn" class="pure-button pure-button-primary">Search</button>
                </fieldset>
            </form>
        </div>

        @unless($system === null)
            <table class="pure-table pure-table-horizontal">
                <tbody>
                    <tr>
                        <td>System:</td>
                        <td>{{ $system->system }}</td>
                    </tr>
                    <tr>
                        <td>Class:</td>
                        <td><b>C{{ $system->class }}</b></td>
                    </tr>
                    <tr>
                        <td>Star:</td>
                        <td>{{ $system->star }}</td>
                    </tr>
                    <tr>
                        <td>Planets:</td>
                        <td>{{ $system->planets }}</td>
                    </tr>
                    <tr>
                        <td>Moons:</td>
                        <td>{{ $system->moons }}</td>
                    </tr>
                    <tr>
                        <td>Statics:</td>
                        <td>
                            @foreach ($system->static as $item)
                                {{ $item->hole }}
                                
                                @switch($item->in_class)
                                    @case(7)
                                        (<span style="color:lightgreen"><b>HS</b></span>)
                                        @break
                                    @case(8)
                                        (<span style="color:orange"><b>LS</b></span>)
                                        @break
                                    @case(9)
                                        (<span style="color:red"><b>NS</b></span>)
                                        @break
                                    @case(12)
                                        (Thera)
                                        @break
                                
                                    @default
                                        (C{{$item->in_class}})
                                @endswitch
                                 
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <h5>{{ $errorMessage }}</h5>
        @endunless

    </div>
@endsection

<script>
    window.onload = function() {
        document.getElementById('search-btn').addEventListener("click", function() {
            let system = document.getElementById('system').value;
            window.location.href = '/system/' + system;
        }, false);
    };
</script>

<style>
    .pure-table tr td:first-child {
        color: gray;
    }
</style>
