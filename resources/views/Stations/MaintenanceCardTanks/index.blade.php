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

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>  @lang('maintenanceType.m_cards') </h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_1}}</span></h3>
                            <span><span class="text-success mr-2"><i
                                            class="fa fa-car"></i> {{$maintenance_cards_all_1 }} </span> </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>@lang('maintenanceType.mntns_card_q_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_2}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_2}} </span> </span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_o_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_3}}</span></h3>
                            <span><span class="text-danger mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_3}}</span>  </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6> @lang('maintenanceType.mntns_card_c_count')</h6>
                            <h3 class="pt-2"><span class="counter">{{$maintenance_cards_all_4}}</span></h3>
                            <span><span class="text-success mr-2"><i class="fa fa-car"></i> {{$maintenance_cards_all_4}}</span> </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--الفلاتر--}}
        <div class="card">
            <form action="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label "> المحطه</label>

                            <select class="selectpicker" multiple data-live-search="true"
                                    name="branch_id[]" data-actions-box="true">
                                @foreach($branches as $branch)
                                    <option value="{{$branch->branch_id}}"
                                            @if(!request()->branch_id) @if(session('branch')['branch_id'] == $branch->branch_id)
                                            selected @endif @endif
                                            @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch_id == $branch->branch_id)
                                            selected @endif @endforeach @endif>
                                        {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                        $branch->branch_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label "> نوع كرت
                                الصيانة </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_type[]" data-actions-box="true">

                                <option value=""> choose</option>
                                @foreach($mntns_cards_type as $mct)
                                    <option value="{{$mct->system_code_id}}"
                                            @if(request()->mntns_cards_type) @foreach(request()->mntns_cards_type as
                                                     $mntns_card_type) @if($mntns_card_type == $mct->system_code_id)
                                            selected @endif @endforeach @endif>
                                        {{ $mct->system_code_name_ar
                                        }}-{{ $mct->system_code_name_en }}
                                        - {{ $mct->system_code }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        {{--تاريخ اانشاء من والي--}}
                        <div class="col-md-4">
                            <label>@lang('home.created_date_from')</label>
                            <input type="date" class="form-control" name="created_date_from"
                                   @if(request()->created_date_from) value="{{request()->created_date_from}}"
                                    @endif>
                        </div>

                        <div class="col-md-4">
                            <label>@lang('home.created_date_to')</label>
                            <input type="date" class="form-control" name="created_date_to"
                                   @if(request()->created_date_to) value="{{request()->created_date_to}}" @endif>
                        </div>


                        <div class="col-md-4">
                            <label for="recipient-name" class="col-form-label"> نوع العمل </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_item_id[]" data-actions-box="true">
                                <option value=""> choose</option>
                                @foreach($maintenance_types as $maintenance_type)
                                    <option value="{{$maintenance_type->mntns_type_id}}">
                                        {{app()->getLocale() == 'ar' ? $maintenance_type->mntns_type_name_ar :
                                        $maintenance_type->mntns_type_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-2">
                            <label for="recipient-name" class="col-form-label"> الحاله </label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="mntns_cards_status[]" data-actions-box="true">
                                <option value=""> choose</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status->system_code_id}}"
                                            @if(request()->mntns_cards_status)
                                            @foreach(request()->mntns_cards_status as $mntns_card_status)
                                            @if($mntns_card_status == $status->system_code_id) selected @endif @endforeach
                                            @endif>
                                        {{app()->getLocale() == 'ar' ? $status->system_code_name_ar :
                                        $status->system_code_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <br>
                            <br>
                            <button type="submit" class="btn btn-primary">@lang('home.filter')</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        {{--/////////////////////////////////////////--}}

        <div class="row">
            <div class="col-md-12">
                <a href="{{route('maintenanceCardTanks.create')}}"
                   class="btn btn-primary btn-lg">{{__('Add New Card')}}</a>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped card_table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('maintenanceType.mntns_card_no')</th>
                        <th>@lang('maintenanceType.mntns_card_types')</th>
                        <th>@lang('home.branch')</th>
                        <th>@lang('MNTNS CARD CARS')</th>
                        <th>@lang('maintenanceType.mntns_card_status')</th>
                        <th>@lang('home.total')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($maintenance_cards as $maintenance_card)
                        <tr>
                            <td>{{$maintenance_card->mntns_cards_no}}</td>
                            <td>{{$maintenance_card->cardType->system_code_name_ar}}</td>
                            <td>{{$maintenance_card->branch->branch_name_ar}}</td>
                            <td>{{$maintenance_card->asset ? $maintenance_card->asset->asset_name_ar : ''}}</td>
                            <td>{{$maintenance_card->status->system_code_name_ar}}</td>
                            <td>{{$maintenance_card->cardSumTotal()}}</td>
                            <td>
                                <a href="{{route('maintenanceCardTanks.create2',$maintenance_card->mntns_cards_id)}}"
                                   class="btn btn-primary"><i class="fa fa-edit"></i></a>
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row w-100 mt-3">
            <div class="col-12">
                {{ $maintenance_cards->appends($data)->links() }}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection