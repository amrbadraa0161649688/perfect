@extends('Layouts.master')
@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">

    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="section-body mt-3" id="app">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        @include('Includes.form-errors')
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="row mb-12">
                                <div class="col-md-3">
                                    <label>@lang('home.company')</label>
                                    @if(auth()->user()->user_type_id  == 1)
                                        <input type="text" class="form-control" value="{{app()->getLocale()=='ar' ? session('company_group')['company_group_ar'] :
                                             session('company_group')['company_group_en'] }}" readonly>
                                    @else
                                        <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                                {{ auth()->user()->companyGroup->company_group_ar }} @else
                                {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <label>@lang('home.brand')</label>
                                    <select class="form-control selectpicker @error('brand_id') is-invalid @enderror"
                                            data-live-search="true" data-actions-box="true" multiple
                                            name="brand_id[]" id="brand_id">
                                        <option value="" >@lang('home.brand')</option>
                                    @foreach($brands as $brand)
                                            <option value="{{ $brand->brand_id}}"
                                                    @if(request()->brand_id) @foreach(request()->brand_id as $brand_id) @if($brand->brand_id == $brand_id) selected @endif @endforeach @endif>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>@lang('home.search')</label>
                                    <input type="text" name="query" class="form-control"
                                           placeholder="{{__('home.search')}}"
                                           value="@if(request()->input('query')){{request()->input('query')}}@endif">
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary mt-4" type="submit">@lang('home.search')
                                        <i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('brandDts.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_dt_brand')
                            </a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>
                                <tr class="red" style="background-color: #ece5e7">
                                    <th class="sorting" style=" width: 10px; "></th>
                                    <th>@lang('home.brand_dt_no')</th>
                                    <th>@lang('home.brand_dt_name_ar')</th>
                                    <th>@lang('home.brand_dt_name_en')</th>
                                    <th>@lang('home.brand')</th>
                                    <th>@lang('home.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td></td>
                                        <td>{{$record->brand_dt_id}}</td>
                                        <td>{{$record->brand_dt_name_ar}}</td>
                                        <td>{{$record->brand_dt_name_en}}</td>
                                        <td><a href="{{route('brands.index')}}">{{$record->brand?$record->brand->name:''}}</a></td>
                                        <td>
                                            <a href="{{route('brandDts.edit' ,$record->brand_dt_id )}}"
                                               class="btn btn-primary btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{ $records->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection
