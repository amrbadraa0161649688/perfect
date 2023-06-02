@extends('Layouts.master')
@section('content')
    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="iq-card-body">
                <p>@lang('home.branch_location')</p>
                <iframe class="w-100"
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCRYhpP7z5ziCArrOu5fzdKQgD5AKp-VtU&q={{ $branch->branch_lng }},
                        {{ $branch->branch_lat }}&zoom=18&maptype=roadmap"
                        height="500" allowfullscreen="">

                </iframe>
            </div>
        </div>
    </div>
@endsection
