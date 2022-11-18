@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: 1rem">
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

    <div class="container">
        <div class="z-depth-5 white-text" style="background-color:rgba(0,0,0,.5);font-size:1.25rem;padding:1rem">
            <div class="row">
                <div class="col s3"><h5>Enemies</h5></div>
                <div class="col s3"><h5>Hardeners to Use</h5></div>
                <div class="col s3"><h5>Damage to use</h5></div>
            </div>
            @foreach ($damageTypes as $item)
            <div class="row m-0">
                <div class="col s3">{{ $item[0] }}</div>
                <div class="col s3">{{ $item[1] }}</div>
                <div class="col s3">{{ $item[2] }}</div>
            </div>
            @endforeach
        </div>
       
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('[data-menu-item="home"]').addClass('active');
        });
    </script>
@endpush
