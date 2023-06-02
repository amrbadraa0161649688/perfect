@extends('Layouts.master')
@section('content')
    <div class="section-body py-4">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('store accounts')}}</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                            class="fe fe-chevron-up"></i></a>
                                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                                            class="fe fe-maximize"></i></a>
                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                            class="fe fe-x"></i></a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('company')}}</th>
                                        <th>{{__('branch')}}</th>
                                        <th>{{__('store types')}}</th>
                                        <th>{{__('journal type code')}}</th>
                                        <th> حساب المشتريات او المخزون</th>
                                        <th> حساب الايرادات</th>
                                        <th> حساب تكلفه البضاعه المباعه</th>
                                        <th> حساب المخزون</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($store_accounts as $k=>$store_account)
                                        <tr>
                                            <td>{{$k+1}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $store_account->company->company_name_ar :
                                            $store_account->company->company_name_en}}</td>

                                            <td>@if($store_account->branch) {{app()->getLocale() == 'ar' ? $store_account->branch->branch_name_ar :
                                            $store_account->branch->branch_name_en}} @endif</td>

                                            <td>{{app()->getLocale() == 'ar' ? $store_account->StoreType->system_code_name_ar :
                                            $store_account->StoreType->system_code_name_en}}</td>

                                            <td>{{app()->getLocale() == 'ar' ? $store_account->JournalType->journal_types_name_ar :
                                            $store_account->JournalType->journal_types_name_en}}</td>

                                            <td>{{$store_account->Account1 ? $store_account->Account1->acc_code .'==>' . $store_account->Account1->acc_name_ar : 'لا يوجد حساب'}}</td>
                                            <td>{{$store_account->Account2 ? $store_account->Account2->acc_code .'==>' . $store_account->Account2->acc_name_ar : 'لا يوجد حساب'}}</td>
                                            <td>{{$store_account->Account3 ? $store_account->Account3->acc_code .'==>' . $store_account->Account3->acc_name_ar : 'لا يوجد حساب'}}</td>
                                            <td>{{$store_account->Account4 ? $store_account->Account4->acc_code .'==>' . $store_account->Account4->acc_name_ar : 'لا يوجد حساب'}}</td>
                                            <td>
                                                <a class="btn btn-link"
                                                   href="{{route('storeAccounts.edit',$store_account->id)}}"></a><i
                                                        class="fa fa-edit"></i>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-6">
                                        {{ $store_accounts->links() }}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection