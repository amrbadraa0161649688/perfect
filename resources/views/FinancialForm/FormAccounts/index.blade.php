@extends('Layouts.master')
@section('content')
    <div class="section-body py-3">
        <div class="container-fluid">


            <div class="card">
                <div class="card-body">
                    <div class="row clearfix">
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly
                                   value="{{$form_sub_type_dt->formType->system_code_name_ar}}">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly
                                   value="{{$form_sub_type_dt->formSubType->form_name_ar}}">
                        </div>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" readonly
                                   value="{{$form_sub_type_dt->form_dt_name_ar}}">

                        </div>
                    </div>
                </div>
            </div>

            <div class="row clearfix">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{route('financial-account.create',$form_sub_type_dt->fin_sub_type_dt_id)}}"
                               class="btn btn-primary">{{__('add account')}}</a>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('name ar')}}</th>
                                        <th>{{__('account code')}}</th>
                                        <th>{{__('account level')}}</th>
                                        <th>{{__('sign')}}</th>
                                        <th>{{__('order')}}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($form_accounts as $form_account)
                                        <tr>
                                            <td></td>
                                            <td>{{app()->getLocale() == 'ar' ? $form_account->acc_name_ar
                                            : $form_account->aacc_name_en}}</td>
                                            <td>{{$form_account->acc_code}}</td>
                                            <td>{{$form_account->acc_level}}</td>
                                            <td>{{$form_account->sign}}</td>
                                            <td>{{$form_account->acc_order ? $form_account->acc_order : 0 }}</td>
                                            <td>
                                                <form style="display: inline-block"
                                                      action="{{route('financial-account.addAccOrder')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="fin_form_acc_id"
                                                           value="{{$form_account->fin_form_acc_id}}">
                                                    <input type="hidden" name="order" value="up">
                                                    <button class="btn btn-link" type="submit">
                                                        <i class="fe fe-chevron-up" style="font-weight:bold"></i>
                                                    </button>

                                                </form>

                                                <form style="display: inline-block"
                                                      action="{{route('financial-account.addAccOrder')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="fin_form_acc_id"
                                                           value="{{$form_account->fin_form_acc_id}}">
                                                    <input type="hidden" name="order" value="down">
                                                    <button class="btn btn-link" type="submit">
                                                        <i class="fe fe-chevron-down" style="font-weight:bold"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{route('financial-account.edit',$form_account->fin_form_acc_id)}}"
                                                   class="btn btn-link">
                                                    <i class="fa fa-edit"></i></a>

                                                <form action="{{route('financial-account.delete',$form_account->fin_form_acc_id)}}"
                                                      method="post" style="display: inline-block">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-link" type="submit">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="{{route('financial-sub-types')}}?FormSubType={{$form_sub_type_dt->formSubType->fin_sub_type_id}}"
                               class="btn btn-primary">{{__('back')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection