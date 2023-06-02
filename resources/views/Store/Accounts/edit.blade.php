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

                <div class="col-lg-12">
                    <form action="{{route('storeAccounts.update',$store_account->id)}}" class="card" method="post">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <h3 class="card-title">{{__('store accounts')}}</h3>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">{{__('user')}}</label>
                                        <input type="text" class="form-control" disabled=""
                                               value="{{app()->getLocale() == 'ar' ?
                                               auth()->user()->user_name_ar : auth()->user()->user_name_en}}">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.companies')</label>
                                        <input type="text" class="form-control" name="company_id"
                                               value="{{app()->getLocale() == 'ar' ? $store_account->company->company_name_ar :
                                               $store_account->company->company_name_en}}" disabled="">

                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">@lang('home.branch')</label>
                                        <input type="text" class="form-control" name="company_id"
                                               value="{{app()->getLocale() == 'ar' ? $store_account->branch->branch_name_ar :
                                               $store_account->branch->branch_name_en}}" disabled="">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('store types')}}</label>
                                        <input type="text" class="form-control" name="company_id"
                                               value="{{app()->getLocale() == 'ar' ? $store_account->StoreType->system_code_name_ar :
                                               $store_account->StoreType->system_code_name_en}}" disabled="">
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{__('journal type code')}}</label>
                                        <select class="form-control" name="journal_type_code" required>
                                            @foreach($journal_types as $journal_type)
                                                <option value="{{$journal_type->journal_types_code}}"
                                                        @if($store_account->journal_type_code == $journal_type->journal_types_code) selected @endif>
                                                    {{ $journal_type->journal_types_name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('account 1')}}</label>
                                        <select name="acc_id_1" class="selectpicker" data-live-search="true"
                                                required>
                                            <option value=""></option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->acc_id}}"
                                                        @if($store_account->acc_id_1 == $account->acc_id) selected @endif>
                                                    {{ $account->acc_name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small> حساب المشتريات او المخزون</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('account 2')}}</label>
                                        <select name="acc_id_2" class="selectpicker" data-live-search="true">
                                            <option value=""></option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->acc_id}}"
                                                        @if($store_account->acc_id_2 == $account->acc_id) selected @endif>
                                                    {{ $account->acc_name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small> حساب الايرادات</small>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('account 3')}}</label>
                                        <select name="acc_id_3" class="selectpicker" data-live-search="true">
                                            <option value=""></option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->acc_id}}"
                                                        @if($store_account->acc_id_3 == $account->acc_id) selected @endif>
                                                    {{ $account->acc_name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small>حساب تكلفه البضاعه المباعه</small>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('account 4')}}</label>
                                        <select name="acc_id_4" class="selectpicker" data-live-search="true">
                                            <option value=""></option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->acc_id}}"
                                                        @if($store_account->acc_id_4 == $account->acc_id) selected @endif>
                                                    {{ $account->acc_name_ar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small>حساب المخزون</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">{{__('save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

@endsection