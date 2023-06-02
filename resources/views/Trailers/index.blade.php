@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>

@endsection

@section('content')
    <div class="section-body py-4">
        <div class="container-fluid">

            <div class="row">

                <div class="card">
                    <div class="card-body">
                        <form action="">

                            <div class="col-md-12">
                                <div class="row">
                                    {{--الشركات--}}
                                    <div class="col-md-3">
                                        <label>@lang('home.companies')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="company_id[]" required>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}"
                                                        @if(request()->company_id) @foreach(request()->company_id as
                                     $company_id) @if($company->company_id == $company_id) selected @endif @endforeach @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$company->company_name_ar}}
                                                    @else
                                                        {{$company->company_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach
                                        </select>

                                    </div>

                                    {{-- فرع--}}
                                    <div class="col-md-3">
                                        {{-- loc_from  --}}
                                        <label>@lang('trucks.loc_truck')</label>
                                        <select class="selectpicker" multiple data-live-search="true"
                                                name="branch_id[]" data-actions-box="true">
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->branch_id }}"
                                                        @if(request()->branch_id)
                                                        @foreach(request()->branch_id as $loc_branch_1)
                                                        @if($branch->branch_id == $loc_branch_1) selected @endif
                                                        @endforeach
                                                        @endif>
                                                    {{app()->getLocale()=='ar'
                                                    ? $branch->branch_name_ar
                                                    : $branch->branch_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--رقم الناقله--}}
                                    <div class="col-md-3">
                                        <label>{{__('Trailer Code')}}</label>
                                        <input type="text" class="form-control" name="asset_code"
                                               @if(request()->asset_code) value="{{request()->asset_code}}" @endif>
                                    </div>

                                    {{--رقم اللوحه--}}
                                    <div class="col-md-2">
                                        <label>{{__('Trailer Chasi')}}</label>
                                        <input type="text" class="form-control" name="asset_serial"
                                               @if(request()->asset_serial) value="{{request()->asset_serial}}" @endif>
                                    </div>

                                    <div class="col-md-1">
                                        <br>
                                        <br>
                                        <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                                    </div>


                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>


            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{route('Trailers.create')}}" class="btn btn-primary">{{__('Add Trailer')}}</a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('Trailer Code')}}</th>
                                        <th>{{__('Trailer Name')}}</th>
                                        <th>{{__('Trailer Status')}}</th>
                                        <th>{{__('Trailer Chasi')}}</th>
                                        <th>{{__('Manufactuer Company')}}</th>
                                        <th>{{__('Trailer Model')}}</th>
                                        <th> @lang('trucks.truck_ownership')</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($trailers as $k=>$trailer)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{$trailer->asset_code}}</td>
                                            <td>{{$trailer->asset_name_ar}}</td>
                                            <td>{{$trailer->asset_status == 0  ? __('Not Active') : __('Active') }}</td>
                                            <td>{{$trailer->asset_serial}}</td>
                                            <td>{{app()->getLocale() == 'ar' ?
                                            $trailer->manifacturerCompany->system_code_name_ar : $trailer->manifacturerCompany->system_code_name_en}}</td>
                                            <td>{{$trailer->asset_model}}</td>
                                            <td>{{$trailer->assetOwner ? $trailer->assetOwner->customer_name_full_ar : ''}}</td>
                                            <td><a href="{{route('Trailers.edit',$trailer->asset_id)}}"
                                                   class="tag tag-success">
                                                    <i class="fa fa-edit"></i>
                                                </a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row w-100 mt-3">
                <div class="col-12">
                    {{ $trailers->appends($data)->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection