@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    {{-- datatable styles --}}
    <style>
        .full {
            /* padding-left: 50%; */
            padding-inline-end: 40% !important;
        }
    </style>

@endsection


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <form action="">
                        @if(session('company_group'))
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ session('company_group')['company_group_ar'] }} @else
                            {{ session('company_group')['company_group_en'] }} @endif" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="company_id" name="company_id" onchange="getData()" disabled>
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if( $user_data['company']->company_id == $company->company_id) selected @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="branch_id" name="branch_id" onchange="getData()" disabled>
                            <option value="">@lang('home.choose')</option>
                            @foreach($branch_lits as $branch)
                                <option value="{{$branch->branch_id}}"
                                        @if( $user_data['branch']->branch_id == $branch->branch_id) selected @endif>
                                    {{ $branch->getBranchName() }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="col-md-3">
                    <form action="">
                        <select class="form-control" id="warehouses_type" name="warehouses_type" onchange="getData()">
                            <option value="">@lang('home.choose')</option>
                            @foreach($warehouses_type_lits as $warehouses_type)
                                <option value="{{$warehouses_type->system_code_id}}">
                                    {{ $warehouses_type->getSysCodeName() }}
                                </option>
                            @endforeach
                        </select>
                    </form> 
                </div>

            </div>
            <br>
        <!-- <div class="row" id ="search_input">
                <div class="col-md-5 row">
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_s" id="item_code_s" placeholder="@lang('storeItem.item_code')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_vendor_code_s" id="item_vendor_code_s" placeholder="@lang('storeItem.item_vendor_code')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_1_s" id="item_code_1_s" placeholder="@lang('storeItem.item_code_1')">
                    </div>
                    
                </div>
                <div class="col-md-5 row">
                    
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_code_2_s" id="item_code_2_s" placeholder="@lang('storeItem.item_code_2')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_name_a_s" id="item_name_a_s" placeholder="@lang('storeItem.item_name_a')">
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="item_name_e_s" id="item_name_e_s" placeholder="@lang('storeItem.item_name_e')">
                    </div>
                </div>
                <div class="col-md-2 row">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-primary btn-block" onclick="getData()">
                            @lang('storeItem.search_button')
                </button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-warning btn-block" onclick="resetSearch()">
@lang('storeItem.clear_button')
                </button>
            </div>
        </div>
    </div> -->
            @if($page == 'inv')

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <form action="{{route('store-sales-inv.approveAllInvoice')}}"
                                  method="post">
                                @csrf
                                @foreach($purchase as $k=>$pur)
                                    <input type="hidden" name="store_hd_id[]"
                                           value="{{$pur->store_hd_id }}">
                                @endforeach
                                

                                <div class="header-action">
                                    <button type="submit" class="btn btn-info btn-sm">
                                        Approve All
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#create_inv_modal">
                                        <i class="fe fe-plus mr-2"></i> @lang('sales.add_inv_button')
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>


                    <div class="table-responsive">

                        <table class="table table-striped table-lg inv_table">
                            <thead class="thead-light">
                            <tr>

                                <th>No</th>
                                <th> @lang('sales.branch') </th>
                                <th> @lang('sales.warehouse_type') </th>
                                <th> @lang('sales.inv_no') </th>
                                <th> @lang('sales.create_date') </th>
                                <th> @lang('sales.customer_name') </th>
                                <th> @lang('sales.customer_mobile') </th>
                                <th> @lang('sales.payment_method') </th>

                                <th style="font-size: 16px ;font-weight: bold; color: blue"> @lang('sales.status') </th>
                                <th> @lang('sales.total') </th>
                                <th> @lang('home.paid') </th>
                                <th> @lang('home.bond') </th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($purchase as $k=>$pur)
                                <tr>
                                    <td>{{$k+1}}</td>
                                    <td>{{$pur->branch_name_ar}}</td>
                                    <td>{{$pur->store_category_type_name}}</td>
                                    <td>{{$pur->store_hd_code}}</td>
                                    <td>{{$pur->store_vou_date}}</td>
                                    <td>{{$pur->store_acc_name}}</td>
                                    <td>{{$pur->store_vou_ref_after}}</td>
                                    <td>{{$pur->payment_method_name}}</td>
                                    <td>{{$pur->status_name_ar}}</td>
                                    <td>{{$pur->store_vou_total}}</td>
                                    <td>{{$pur->store_vou_payment}}</td>
                                    <td>{{$pur->bond_code}}</td>
                                    <td colspan=" 2">
                                        {{--@if(auth()->user()->user_type_id != 1)--}}
                                        {{--@foreach(session('job')->permissions as $job_permission)--}}
                                        {{--@if($job_permission->app_menu_id == 65 && $job_permission->permission_add)--}}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <a class="btn btn-icon"
                                                   href="{{ route('store-sales-inv.edit',$pur->uuid ) }}"
                                                   title="">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </div>
                                            <div class="col-md-2">
                                                @if($pur->status_system_code)
                                                    @if($pur->status_system_code != 125001)
                                                        <a class="btn btn-icon"
                                                           href="{{config('app.telerik_server')}}?rpt={{$pur->report_url}}&id={{$pur->uuid}}&lang=ar&skinName=bootstrap"
                                                           title="" target="_blank">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="col-md-3">
                                                @if($pur->store_vou_payment != $pur->store_vou_total)
                                                    <button type="button" class="btn btn-info btn-sm"
                                                            data-toggle="modal"
                                                            data-target="#exampleModal{{ $pur->uuid }}"
                                                            data-whatever="@mdo">
                                                        @lang('home.add_bond')
                                                    </button>
                                                @endif
                                            </div>

                                            @if(!$pur->journal_hd_id)
                                                <div class="col-md-2">
                                                    <form action="{{route('store-sales-inv.approveOneInvoice')}}"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="store_hd_id"
                                                               value="{{$pur->store_hd_id}}">
                                                        <button type="submit" class="btn btn-info btn-sm">
                                                            @lang('home.approve')
                                                        </button>
                                                    </form>

                                                </div>
                                            @endif

                                            {{-- bond modal --}}
                                            <div class="modal fade" id="exampleModal{{$pur->uuid}}"
                                                 tabindex="-1" role="dialog"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                                                            <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="{{ route('store-sales-inv.addBondWithJournal2') }}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.sub_company')</label>
                                                                            @if(session('company'))
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       disabled=""
                                                                                       value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                                                            @else
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       disabled=""
                                                                                       value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.branch')</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   disabled=""
                                                                                   value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.created_date')</label>
                                                                            <input type="date"
                                                                                   class="form-control date"
                                                                                   name="bond_date"
                                                                                   required>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.user')</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   disabled=""
                                                                                   placeholder="Company"
                                                                                   value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                                                                        </div>
                                                                    </div>

                                                                    <input type="hidden"
                                                                           name="transaction_id"
                                                                           value="{{$pur->store_hd_id}}">

                                                                    {{--النشاط--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.bonds_activity')</label>

                                                                            <input type="text" disabled=""
                                                                                   value="فاتوره بيع"
                                                                                   class="form-control">
                                                                            <input type="hidden"
                                                                                   name="transaction_type"
                                                                                   value="65">

                                                                        </div>
                                                                    </div>

                                                                    {{--الرقم المرجعي--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.reference_number')</label>

                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   name="bond_ref_no"
                                                                                   value="{{ $pur->store_hd_code }}"
                                                                                   readonly>
                                                                        </div>
                                                                    </div>

                                                                    {{--القيمه المستحقه--}}
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.deserved_value')</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   readonly
                                                                                   id="deserved_value"
                                                                                   value="{{ $pur->store_vou_total - $pur->store_vou_payment }}">

                                                                        </div>
                                                                    </div>

                                                                    {{--نوع الحساب--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.account_type')</label>

                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   readonly
                                                                                   value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('company_group_id',
                                $pur->company_group_id)->where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('company_group_id',
                                $pur->company_group_id)->where('system_code',56002)
                                                   ->first()->system_code_name_en}}">

                                                                            <input type="hidden" value="{{ \App\Models\SystemCode::where('company_group_id',
                                $pur->company_group_id)->where('system_code',56002)
                                            ->first()->system_code_id}}" name="account_type">
                                                                        </div>
                                                                    </div>

                                                                    {{--نوع العميل--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <input type="hidden"
                                                                                   name="customer_type"
                                                                                   value="customer">

                                                                            <label class="form-label">@lang('home.customer')</label>

                                                                            <input type="text" readonly
                                                                                   class="form-control"
                                                                                   @if($pur->customer_id)
                                                                                   value="{{$pur->store_acc_name  }}" @endif>
                                                                            <input type="hidden"
                                                                                   name="customer_id"
                                                                                   @if($pur->store_acc_name)
                                                                                   value="{{ $pur->customer_id }}" @endif>
                                                                        </div>
                                                                    </div>

                                                                    {{-- قم الحساب للعميل--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.account_code')</label>
                                                                            <input type="hidden"
                                                                                   class="form-control"
                                                                                   name="bond_acc_id"
                                                                                   @if($pur->customer_id)
                                                                                   value="{{ $pur->customer_account_id}}" @endif>
                                                                            <input type="text" readonly
                                                                                   class="form-control"
                                                                                   @if($pur->customer_id)
                                                                                   value="{{$pur->acc_name_ar }} . {{ $pur->acc_code }}"
                                                                                    @endif>

                                                                        </div>
                                                                    </div>

                                                                    {{--انواع الايرادات--}}
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.cash_types')</label>

                                                                            @if(app()->getLocale() == 'ar')
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       readonly
                                                                                       value="{{App\Models\SystemCode::where('company_id',
                                $pur->company_id)->where('system_code',580003)->first()->system_code_name_ar}}">
                                                                            @else
                                                                                <input type="text"
                                                                                       class="form-control"
                                                                                       readonly
                                                                                       value="{{App\Models\SystemCode::where('company_id',
                                $pur->company_id)->where('system_code',580003)->first()->system_code_name_en}}">
                                                                            @endif

                                                                            <input type="hidden"
                                                                                   name="bond_doc_type"
                                                                                   value="{{App\Models\SystemCode::where('company_id',
                                $pur->company_id)->where('system_code',580003)->first()->system_code_id}}">
                                                                        </div>
                                                                    </div>

                                                                    {{--طرق الدفع--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.payment_method')</label>
                                                                            <select class="form-control"
                                                                                    onchange="validInputs($(this))"
                                                                                    name="bond_method_type"
                                                                                    required>
                                                                                <ooption
                                                                                        value="">@lang('home.choose')</ooption>
                                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 57)
                                                        ->where('company_group_id', $pur->company_group_id)->get() as $payment_method)
                                                                                    <option value="{{ $payment_method->system_code }}">{{ app()->getLocale()=='ar' ?
                                                 $payment_method->system_code_name_ar : $payment_method->system_code_name_en }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    {{--رقم العمليه--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.process_number')</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   name="process_number"
                                                                                   disabled="">
                                                                        </div>
                                                                    </div>

                                                                    {{--البنك--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.bank')</label>
                                                                            <select class="form-control"
                                                                                    name="bond_bank_id"
                                                                                    disabled>
                                                                                <option value="">@lang('home.choose')</option>
                                                                                @foreach(App\Models\SystemCode::where('sys_category_id', 40)
                                                        ->where('company_group_id', $pur->company_group_id)->get() as $bank)
                                                                                    <option value="{{ $bank->system_code_id }}">
                                                                                        {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                                                                         $bank->system_code_name_en }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    {{--القيمه--}}
                                                                    <div class="col-sm-6 col-md-4">
                                                                        <div class="form-group">
                                                                            <label class="form-label">@lang('home.value')</label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   onkeyup="validPaid($(this))"
                                                                                   name="bond_amount_credit"
                                                                                   required
                                                                                   value="{{$pur->store_vou_total - $pur->store_vou_payment}}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-sm-6 col-md-6">
                                                                        <label class="form-label">@lang('home.notes')</label>
                                                                        <textarea class="form-control"
                                                                                  name="bond_notes"
                                                                                  placeholder="@lang('home.notes')"></textarea>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="submit"
                                                                                class="btn btn-primary btn-sm"
                                                                                id="submit_button">
                                                                            @lang('home.add_bond')
                                                                        </button>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        {{--@endif--}}
                                        {{--@endforeach--}}
                                        {{--@endif--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{$purchase->links()}}
                </div>
                <div id="showData"></div>    
                @include('store.sales.inv.create_inv')                                                                       
            @else
           

            @endif
            
            
        </div>
    </div>


@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script type="text/javascript">
        {{--@if($page != 'inv')--}}

        $(function () {
            //company_id = {{ auth()->user()->company_id }};
            //getData(company_id);
            getData();
        });


        function getData() {
            //console.log($('#company_id').val());
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            search = {
                company_id: $('#company_id').val(),
                warehouses_type: $('#warehouses_type').val(),
                branch_id: $('#branch_id').val()
            };
            $.ajax({
                type: 'get',
                url: "{{route('store-sales-inv.data','inv')}}",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                    search: search,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function (data) {
                //App.stopPageLoading();
                if (data.success == false) {
                    toastr.warning(data.msg);
                }
                $('#showData').html(data.view);
            });
        }

        function closeItemModal() {
            $('#create_inv_modal').on('hidden.bs.modal', function () {
                //$('#create_inv_modal .modal-body').html('');
            });
            $('#create_inv_modal').modal('hide');
        }

        function resetSearch() {
            $('#search_input').find('input:text').val('');
        }

        //
        {{--@endif--}}

    </script>
@endsection

