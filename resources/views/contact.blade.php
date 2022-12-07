@extends('layouts.app')
@section('title', '- Contact')

@section('content')
    <div class="container main-content">
        <h1 class="title is-1 has-text-centered mt-3 mb-5">Contact us</h1>
        <div class="p-6 is-dark-half scrolling">
            <div class="content">
                
                <x-contact.form />

                {!! GoogleReCaptchaV3::renderOne('contact_id', 'contact') !!}

            </div>
        </div>
    </div>
@endsection
