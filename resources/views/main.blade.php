@extends('layouts.app')

@section('content')
    <div class="container main-content">
        <div class="tile is-parent is-vertical">
            <article class="tile is-child notification is-dark-half">
                <p class="title has-text-white">Wormhole Types</p>
                <p class="subtitle has-text-white"></p>
                <div class="columns has-text-centered">
                    <div class="column has-text-success">
                        <div class="is-size-4">High</div>
                        @foreach ($classes['High'] as $name)
                            {{ $name }} <br>
                        @endforeach
                    </div>
                    <div class="column has-text-warning">
                        <div class="is-size-4">Low</div>
                        @foreach ($classes['Low'] as $name)
                            {{ $name }} <br>
                        @endforeach
                    </div>
                    <div class="column has-text-danger">
                        <div class="is-size-4">Null</div>
                        @foreach ($classes['Null'] as $name)
                            {{ $name }} <br>
                        @endforeach
                    </div>
                    <div class="column has-text-info">
                        <div class="is-size-4">Thera</div>
                        @foreach ($classes['Thera'] as $name)
                            {{ $name }} <br>
                        @endforeach
                    </div>
                    @foreach (['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C13'] as $class)
                        <div class="column has-text-white">
                            <div class="is-size-4">{{ $class }}</div>
                            @foreach ($classes[$class] as $name)
                                {{ $name }} <br>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </article>
            <article class="tile is-child notification is-dark-half">
                <p class="title has-text-white">Rats Damage Types</p>
                <p class="subtitle has-text-white"></p>
                <div class="has-text-white has-text-centered">
                    <div class="columns">
                        <div class="column is-size-4 has-text-warning">Enemies</div>
                        <div class="column is-size-4 has-text-warning">Hardeners to Use</div>
                        <div class="column is-size-4 has-text-warning">Damage to use</div>
                    </div>
                    @foreach ($damageTypes as $item)
                        <div class="columns">
                            <div class="column py-0">{{ $item[0] }}</div>
                            <div class="column py-0">{{ $item[1] }}</div>
                            <div class="column py-0">{{ $item[2] }}</div>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
@endsection
