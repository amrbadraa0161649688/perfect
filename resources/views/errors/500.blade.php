@extends('errors::illustrated-layout')

@section('code', '500')

@section('title', __('Page Not Found'))

@section('image')

    <div style="background-image: url({{ asset('/images/error.jpg') }});" class="absolute pin bg-no-repeat md:bg-left lg:bg-center">
    </div>

@endsection

@section('message', __('Sorry, there is undefined error'))
