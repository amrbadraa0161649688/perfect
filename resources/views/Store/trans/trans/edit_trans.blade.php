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
    <style type="text/css">
        .ctd {
            text-align: center;

        }

        .full {
            padding-left: 40%;
        }
    </style>


@endsection

@section('content')

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="nav nav-tabs page-header-tab">

                    <li class="nav-item">
                        <a href="#data-grid" data-toggle="tab"
                           class="nav-link">@lang('home.edit')</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="#bonds-cash-grid"
                                            data-toggle="tab">@lang('home.bonds_cash')</a></li>

                </ul>
                <div class="header-action"></div>
            </div>
        </div>
    </div>

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="tab-content mt-3">

                <div class="tab-pane fade show active" id="data-grid" role="tabpanel">
                    <div class="card-body">
                        <div class="row card">
                            <form id="item_data_form" enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <input type="hidden" class="form-control" name="store_hd_id"
                                                   id="store_hd_id" value="{{ $purchase->store_hd_id }}">
                                            <input type="hidden" class="form-control" name="purchase_uuid"
                                                   id="purchase_uuid" value="{{ $purchase->uuid }}">
                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> رقم اذن
                                                    التحويل </label>
                                                <input type="text" class="form-control" name="store_hd_code"
                                                       id="store_hd_code" value="{{ $purchase->store_hd_code }}"
                                                       readonly disabled>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> تاريخ اذن
                                                    التحويل </label>
                                                <input type="text" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $purchase->created_date->format('Y-m-d H:m') }}"
                                                       readonly disabled>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> الفرع
                                                    المصدر </label>
                                                <input type="text" class="form-control" name="source_branch"
                                                       id="source_branch"
                                                       value="{{ $purchase->sourceBranch->getBranchName() }}" readonly
                                                       disabled>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> المستودع
                                                    المصدر </label>
                                                <input type="text" class="form-control" name="source_store"
                                                       id="source_store"
                                                       value="{{ $purchase->sourceStore->getSysCodeName() }}" readonly
                                                       disabled>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> الفرع
                                                    المستلم </label>
                                                <input type="text" class="form-control" name="dest_branch"
                                                       id="dest_branch"
                                                       value="{{ $purchase->destBranch->getBranchName() }}" readonly
                                                       disabled>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="recipient-name" class="col-form-label "> المستودع
                                                    المستلم </label>
                                                <input type="text" class="form-control" name="dest_store"
                                                       id="dest_store"
                                                       value="{{ $purchase->destStore->getSysCodeName() }}" readonly
                                                       disabled>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row card">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#search_item_modal">
                                <i class="fe fe-search mr-2"></i> عرض الاصناف
                            </button>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">
                                        @include('store.trans.trans.table.item_table')
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer row">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{config('app.telerik_server')}}?rpt={{$purchase->report_url_trans->report_url}}&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Print')}}
                                </a>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('store-transfer-trans.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>

                <div class="tab-pane fade" id="bonds-cash-grid" role="tabpanel">

                    <div class="card-title">
                        <a href="{{ url('bonds-add/cash/create?store_transfer_id='.$purchase->store_hd_id) }}"
                           class="btn btn-primary text-white" target="_blank">@lang('home.add_bond')</a>
                    </div>

                    <div class="card-body">
                        <div class="row card">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.bonds_number')</th>
                                        <th>@lang('home.bonds_date')</th>
                                        <th>@lang('home.sub_company')</th>
                                        <th>@lang('home.branch')</th>
                                        <th>@lang('home.bonds_account')</th>
                                        <th>@lang('home.payment_method')</th>
                                        <th>@lang('home.value')</th>
                                        <th>@lang('home.user')</th>
                                        <th>@lang('home.journal')</th>
                                        <th></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bonds_cash as $bond)
                                        <tr>
                                            <td>{{ $bond->bond_code }}</td>
                                            <td>{{ $bond->created_date }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                                            <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                                            <td>{{ $bond->bond_acc_id }}</td>
                                            <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                                            <td>{{ $bond->bond_amount_credit }}</td>
                                            <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
                                            <td>
                                                @if($bond->journalCash)

                                                    <a href="{{route('journal-entries.show',$bond->journalCash->journal_hd_id)}}"
                                                       class="btn btn-primary btn-block">{{$bond->journalCash->journal_hd_code}}</a>

                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{config('app.telerik_server')}}?rpt={{$bond->report_url_payment->report_url}}&id={{$bond->bond_id}}&lang=ar&skinName=bootstrap"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-print"></i></a>
                                                <a href="{{ route('Bonds-cash.show',$bond->bond_id) }}"
                                                   class="btn btn-primary btn-sm"
                                                   title="@lang('home.show')"><i
                                                            class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @include('store.search.search_item')
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
    <script type="text/javascript">
                @if(session('company'))
        var company_id =
                {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
                @else
        var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif
    </script>

    <script type="text/javascript">

        function getNum(number) {
            return parseFloat(number).toFixed(2);
        }

        $(document).ready(function () {
            vat_rate = 15 / 100;
            $('#item_id').on('change', function () {
                if ($('#item_id').val() !== '') {
                    item = $('#item_id :selected').data('item');
                    $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                    $('#item_balance').val($('#item_id :selected').data('balance'));
                    $('#store_vou_qnt_r').val($('#item_id :selected').data('balance'));
                    $('#store_vou_item_price_unit').val(item.item_price_cost);
                    $('#item_price_cost').val(item.item_price_cost);
                    $('#last_price_cost').val(item.last_price_cost);
                }
            });

            $('#store_vou_qnt_o').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }

                if ($('#store_vou_qnt_o').val() > parseFloat($('#store_vou_qnt_r').val())) {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لا يمكن تحويل كمية اكبر من الكمية الحالية');
                }

                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val() * $('#store_vou_item_price_unit').val()));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));
            });

            $('#store_vou_item_price_unit').change(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_o').val()) {
                    $('#store_vou_qnt_o').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_o').val() * $('#store_vou_item_price_unit').val()));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));
            });
        });

        function checkItemInput() {

            if (
                $('#item_id').val() == '' ||
                $('#store_vou_item_code').val() == '' ||
                $('#store_vou_qnt_o').val() == '' ||

                $('#store_vou_item_price_unit').val() == '' ||
                $('#store_vou_item_total_price').val() == '' ||
                $('#invoice_date_external').val() == '') {
                return false;
            }

            return true;

        }

        function saveItemRow() {
            if (!checkItemInput()) {
                return toastr.warning('لا يوجد بيانات لاضافتها');
            }
            tableBody = $("#item_table");
            rowNo = parseFloat($('#item_row_count').val()) + 1; //// ;

            //return  alert(rowNo);
            row_data = {
                'id': 'tr' + rowNo,
                'count': rowNo,
                'store_hd_id': $('#store_hd_id').val(),
                'store_vou_item_id': parseFloat($('#item_id').val()),
                'store_vou_item_code': $('#item_id :selected').data('item').item_code,
                'store_vou_loc': $('#item_id :selected').data('item').item_location,
                'store_vou_qnt_t_o': parseFloat($('#store_vou_qnt_o').val()),
                'store_vou_item_price_sales': $('#item_id :selected').data('item').item_price_sales,
                'store_vou_item_price_cost': parseFloat($('#last_price_cost').val()),
                'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                'item_balance': parseFloat($('#item_balance').val()),
                'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price').val()),
                'last_price_cost': parseFloat($('#last_price_cost').val()),
                'store_vou_vat_rate': (15 / 100),
                'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount').val()),
                'store_vou_price_net': parseFloat($('#store_vou_price_net').val()),
            };

            url = '{{ route('store-item-sales-trans.store') }}';
            var form = new FormData($('#item_data_form')[0]);
            form.append('item_table_data', JSON.stringify(row_data));
            form.append('item_type', 'trans');

            var data = form;
            $.ajax({
                type: 'POST',
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    updateItemTable(rowNo, row_data, data.uuid, data.total);

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function updateItemTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_qnt_t_o">' + rowData['store_vou_qnt_t_o'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + rowData['store_vou_item_price_unit'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_total_price">' + rowData['store_vou_item_total_price'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_vat_amount">' + rowData['store_vou_vat_amount'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_price_net">' + rowData['store_vou_price_net'] + '</td>' +
                '<td  class="ctd"> <button type="button" id="btnSave[]" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only" onclick="deleteItem(' + uuid + ')"><i class="fa fa-trash"></i></button></td>' +
                '<tr>' +
                '<div id="new_row"></div>';
            tableBody.append(markup);
            updateTotal(total);
            //reset input 
            $('#item_id').val('').change();
            $('#add_item_div').find('input').val('');
            $('#item_id').selectpicker('refresh');
        }

        function deleteItem(uuid) {
            var form = new FormData($('#item_data_form')[0]);
            form.append('uuid', uuid);

            $.ajax({

                type: 'POST',
                url: "{{route('store-transfer.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    console.log('return true');
                    console.log(data.data.uuid);
                    $('#' + data.data.uuid).remove();
                    updateTotal(data.total);
                    return 'true';
                }
                else {
                    toastr.warning(data.msg);
                    console.log('return dalse');
                    return 'false';
                }
            });


        }

        function updateTotal(total) {
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);
            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
        }


        function getSearchResult() {
            url = '{{ route('store-item.search') }}'
            $('#searchData').html('');
            var form = $('#search_data_form').serialize();

            $.ajax({
                type: 'GET',
                url: url,
                data: form,
                dataType: 'json',

            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
                    $('#searchData').html(data.view);

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }
    </script>
@endsection

