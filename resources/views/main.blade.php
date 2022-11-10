@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row z-depth-5" style="background-color:rgba(0,0,0,.5);font-size:1.25rem;padding:1rem">
            <div class="col center green-text accent-2">
                <h4>High</h4>
                @foreach ($classes['High'] as $name)
                    {{ $name }} <br>
                @endforeach
            </div>
            <div class="col center orange-text darken-3">
                <h4>Low</h4>
                @foreach ($classes['Low'] as $name)
                    {{ $name }} <br>
                @endforeach
            </div>
            <div class="col center red-text lighten-4">
                <h4>Null</h4>
                @foreach ($classes['Null'] as $name)
                    {{ $name }} <br>
                @endforeach
            </div>
            <div class="col center light-blue-text lighten-5">
                <h4>Thera</h4>
                @foreach ($classes['Thera'] as $name)
                    {{ $name }} <br>
                @endforeach
            </div>
            @foreach (['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C13'] as $class)
                <div class="col center white-text">
                    <h4>{{ $class }}</h4>
                    @foreach ($classes[$class] as $name)
                        {{ $name }} <br>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
