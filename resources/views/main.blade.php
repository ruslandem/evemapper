@extends('layouts.app')

@section('content')
    <div class="container main-content pt-3">

        <x-main.wormholeTypes :classes="$classes"/>
        
        <x-main.damageTypes :types="$damageTypes"/>

    </div>
@endsection
