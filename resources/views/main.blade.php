@extends('layouts.app')

@section('content')
    <div class="container main-content">

        <div class="columns has-text-white p-3">
            <div class="column is-dark-half p-5">
                <div class="content">
                    <h3 class="title has-text-white">Wormhole Types</h3>

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

                </div>
            </div>
        </div>
        <div class="columns has-text-white p-3">
            <div class="column is-dark-half p-5">
                <div class="content">
                    <h3 class="title has-text-white">Damage Types</h3>

                    <div class="columns">
                        <div class="column is-size-4 has-text-warning">Enemy</div>
                        <div class="column is-size-4 has-text-warning">Use Hardeners</div>
                        <div class="column is-size-4 has-text-warning">Use Damage</div>
                    </div>
                    @foreach ($damageTypes as $item)
                        <div class="columns">
                            <div class="column py-0">{{ $item[0] }}</div>
                            <div class="column py-0">{{ $item[1] }}</div>
                            <div class="column py-0">{{ $item[2] }}</div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>
@endsection
