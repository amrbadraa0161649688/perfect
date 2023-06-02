@extends('Layouts.master')

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
                            <div class="row">
                                <div class="col-md-5">
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
                                <div class="col-md-5">
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
                            <a href="{{ route('brands.create') }}" class="btn btn-primary">
                                <i class="fe fe-plus mr-2"></i>@lang('home.add_brand')
                            </a>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover  yajra-datatable">
                                <thead>

                                <tr class="red" style="background-color: #ece5e7">
                                    <th class="sorting" style=" width: 10px; "></th>
                                    <th>@lang('home.brand_no')</th>
                                    <th>@lang('home.logo')</th>
                                    <th>@lang('home.brand_name_ar')</th>
                                    <th>@lang('home.brand_name_en')</th>
                                    <th>@lang('home.actions')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($records as $record)
                                    <tr>
                                        <td></td>
                                        <td>{{$record->brand_id}}</td>
                                        <td>
                                            @if($record->brand_logo_url)
                                                <a href="{{$record->brand_logo_url}}" target="_blank">
                                                    <img class="avatar avatar-blue"
                                                         src="{{$record->brand_logo_url}}"></a>
                                            @endif
                                        </td>
                                        <td>{{$record->brand_name_ar}}</td>
                                        <td>{{$record->brand_name_en}}</td>
                                        <td>
                                            <a href="{{route('brands.edit' ,$record->brand_id )}}"
                                               class="btn btn-primary btn-sm"
                                               title="@lang('home.edit')">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('brandDts.index').'?brand_id[]='.$record->brand_id}}"
                                               class="btn btn-danger btn-sm"
                                               title="@lang('home.show')">
                                                <i class="fa fa-eye"></i>
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
