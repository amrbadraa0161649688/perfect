@extends('Layouts.master')
@section('style')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@200&display=swap');

        .v-application {
            font-family: "Cairo" !important;
        }

        .bootstrap-select {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="section-body mt-3" id="app">
        <v-app>
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12 col-lg-12">
                        <form action="{{route('financial-account.store')}}" method="post">
                            @csrf

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('account')}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">{{__('form type')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   value="{{app()->getLocale() == 'ar' ? $fin_sub_type_dt->formType->system_code_name_ar :
                                           $fin_sub_type_dt->formType->system_code_name_en}}"
                                                   readonly>
                                            <input type="hidden" value="{{$fin_sub_type_dt->form_type_id}}"
                                                   name="form_type_id">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">{{__('form sub type')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   value="{{app()->getLocale() == 'ar' ? $fin_sub_type_dt->formSubType->form_name_ar :
                                           $fin_sub_type_dt->formSubType->form_name_en}}"
                                                   readonly>
                                            <input type="hidden" value="{{$fin_sub_type_dt->fin_sub_type_id}}"
                                                   name="fin_sub_type_id">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">{{__('form sub type dt')}}</label>
                                            <input type="text" class="form-control" name=""
                                                   value="{{app()->getLocale() == 'ar' ? $fin_sub_type_dt->form_dt_name_ar :
                                           $fin_sub_type_dt->form_dt_name_en}}"
                                                   readonly>

                                            <input type="hidden" value="{{$fin_sub_type_dt->fin_sub_type_dt_id}}"
                                                   name="fin_sub_type_dt_id">
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{__('account level')}}</label>
                                            <select type="text" class="form-control mt-3 state-invalid"
                                                    v-model="acc_level"
                                                    required
                                                    @change="getAccountsList()">
                                                <option value=""></option>
                                                @for($i=1 ; $i<=5 ;$i++)
                                                    <option value="{{$i}}">{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{__('accounts list')}}</label>
                                            {{--<select type="text" class="form-control mt-3 state-invalid" required--}}
                                                    {{--name="acc_account_id">--}}
                                                {{--<option value="">@lang('home.choose')</option>--}}
                                                {{--<option v-for="account,index in accounts" :value="account.acc_id">@{{--}}
                                                    {{--account.acc_code }} ==> @{{--}}
                                                    {{--account.acc_name_ar }}--}}
                                                {{--</option>--}}
                                            {{--</select>--}}

                                            <v-autocomplete
                                                    required
                                                    name="acc_account_id"
                                                    :items="accounts"
                                                    item-value="acc_id"
                                                    item-text="acc_name_ar"
                                                    label="@lang('home.accounts')"
                                            ></v-autocomplete>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">{{__('sign')}}</label>
                                            <select class="form-control custom-select" name="sign" required>
                                                <option value="">@lang('home.choose')</option>
                                                <option value="+">+</option>
                                                <option value="-">-</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-primary" type="submit">{{__('save')}}</button>
                                        </div>
                                    </div>


                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </v-app>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
    <script>
        new Vue({
            el: '#app',
            vuetify: new Vuetify(),
            data: {
                acc_level: '',
                accounts: []
            },
            methods: {
                getAccountsList() {
                    console.log('a')
                    $.ajax({
                        type: 'GET',
                        data: {acc_level: this.acc_level},
                        url: '{{ route("financial-account.getAccountsList") }}'
                    }).then(response => {
                        this.accounts = response.data
                    })
                }
            }
        });
    </script>
@endsection