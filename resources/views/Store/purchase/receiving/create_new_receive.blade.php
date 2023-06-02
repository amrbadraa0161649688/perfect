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

                <div class="header-action">

                </div>
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
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">@lang('purchase.receive_no')</label>
                                                <input type="text" class="form-control" name="store_hd_code"
                                                       id="store_hd_code" value="{{ $purchase->store_hd_code }}"
                                                       readonly disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">@lang('purchase.item_category')</label>
                                                <select class="form-select form-control" name="store_category_type"
                                                        id="store_category_type" disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($warehouses_type_list as $w_t)
                                                        <option value="{{$w_t->system_code}}" {{($purchase->store_category_type == $w_t->system_code_id? 'selected': '' )}}> {{ $w_t->getSysCodeName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label">  @lang('purchase.vendor') </label>
                                                <select class="form-select form-control" name="store_acc_no"
                                                        id="store_acc_no" required disabled>
                                                    <option value="" selected> choose</option>
                                                    @foreach($vendor_list as $vendor)
                                                        <option value="{{$vendor->customer_id}}"
                                                                data-vendorname="{{ $vendor->getCustomerName() }}"
                                                                data-vendorvat="{{ $vendor->customer_vat_no }}" {{($purchase->store_acc_no == $vendor->customer_id ? 'selected': '' )}}> {{ $vendor->getCustomerName() }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name"
                                                       class="col-form-label ">  @lang('purchase.create_date') </label>
                                                <input type="text" class="form-control" name="created_date"
                                                       id="created_date"
                                                       value="{{ $purchase->created_date->format('Y-m-d H:m') }}"
                                                       readonly disabled>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('purchase.vendor_name') </label>
                                                <input type="text" class="form-control" name="store_acc_name"
                                                       id="store_acc_name" value="{{$purchase->store_acc_name}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label ">  @lang('purchase.vat_no') </label>
                                                <input type="number" class="form-control" name="store_acc_tax_no"
                                                       id="store_acc_tax_no" value="{{$purchase->store_acc_tax_no}}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label"> @lang('purchase.payment_method')</label>
                                                <select class="form-select form-control" name="store_vou_pay_type"
                                                        id="store_vou_pay_type" required>
                                                    <option value="" selected> choose</option>
                                                    @foreach($payemnt_method_list as $p_method)
                                                        <option value="{{$p_method->system_code}}" {{($purchase->store_vou_pay_type == $p_method->system_code? 'selected': '' )}}> {{ $p_method->getSysCodeName() }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> @lang('purchase.inv_supp_no') </label>
                                                <input type="number" class="form-control " name="store_vou_ref_after"
                                                       id="store_vou_ref_after"
                                                       value="{{ $purchase->store_vou_ref_after }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> {{__('Supply Date')}} </label>
                                                <input type="date" class="form-control " name="vou_datetime"
                                                       id="vou_datetime"
                                                       value="{{ $purchase->vou_datetime }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label "> {{__('Discount Value')}} </label>
                                                <input type="number" class="form-control " name="vou_discount_rate"
                                                       id="vou_discount_rate" value="">
                                            </div>

                                            <div class="col-md-4">
                                                <label for="recipient-name"
                                                       class="col-form-label">@lang('purchase.note') </label>
                                                <textarea rows="2" class="form-control" name="store_vou_notes"
                                                          id="store_vou_notes" placeholder="Here can be your note"
                                                          value=""> {{$purchase->store_vou_notes }}</textarea>
                                            </div>


                                            <div class="col-md-4">
                                                <br>
                                                <br>
                                                <button type="submit" onclick="saveItemRow(2)"
                                                        class="btn btn-primary btn-block">
                                                    <i class="fe fe-save mr-2"></i> تحديث
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-4">
                                    <br>
                                    <form action="{{ route('store-purchase-receiving.edit',$purchase->uuid ) }}">
                                        <button type="submit" class="btn btn-primary btn-lg" id="add_bond"
                                                style="margin-bottom: 10px;margin-right: 10px">
                                            @lang('home.add_bond')
                                        </button>
                                        <input type="hidden" id="total_sum" name="total_sum"
                                               value="{{$purchase->store_vou_total}}">
                                        <input type="hidden" name="qr" value="bond-cash">
                                    </form>
                                </div>


                            </div>

                        </div>
                        <div class="row card">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                            data-target="#search_item_modal">
                                        <i class="fe fe-search mr-2"></i> @lang('purchase.show_item')
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    @foreach(session('job')->permissions as $job_permission)
                                        @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
                                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal"
                                                    data-target="#add_item_modal">
                                                <i class="fe fe-plus mr-2"></i> @lang('storeItem.add_item_button')
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <br>
                                    <div class="table-responsive">

                                        @include('store.purchase.receiving.table.new_item_table')

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer row">


                            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                                <a href="{{config('app.telerik_server')}}?rpt=nasil/store_hd_pr&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Print')}}

                                </a>
                            </div>
                            <div class="col-sm-3 col-xs-3 col-md-3 col-lg-3">
                                <a href="{{config('app.telerik_server')}}?rpt=nasil/store_hd_pr_branch_balance&id={{$purchase->uuid}}&lang=ar&skinName=bootstrap"
                                   title="{{trans('Print')}}" class="btn btn-warning btn-block" id="showReport"
                                   target="_blank">
                                    {{trans('Balance Branch')}}

                                </a>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <a href="{{ route('store-purchase-receiving.index') }}"
                                   class="btn btn-secondary btn-block"> @lang('storeItem.back_button') </a>
                            </div>
                        </div>
                    </div>
                    <br>
                </div>
            </div>

            @include('store.search.search_item')
            @include('store.search.create')
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


            $('#vou_discount_rate').keyup(function () {
                // alert($('#vou_discount_rate').val())
                var vat_ratio = $('#vou_discount_rate').val();
                var total_sum_div = $('#total_sum_div').text();
                $('#total_discount_div').val(vat_ratio)

                $('#total_sum_vat_div').text((0.15 * (total_sum_div - $('#total_discount_div').val())).toFixed(2))

                $('#total_sum_net_div').text((parseFloat($('#total_sum_vat_div').text()) + parseFloat(total_sum_div) - parseFloat($('#total_discount_div').val())).toFixed(2))
            });


            $('#store_vou_item_price_unit_vat').keyup(function () {

                $('#store_vou_item_price_unit').val(getNum($('#store_vou_item_price_unit_vat').val() / (1 + .15)))


                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a);


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b);


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

            });


            if ($('#total_sum').val() == 0) {
                $('#add_bond').attr('disabled', 'disabled')
            } else {
                $('#add_bond').removeAttr('disabled')
            }

            vat_rate = '{{$purchase->vendor->customer_vat_rate ? $purchase->vendor->customer_vat_rate : 15/100}}';

            $('#item_id').on('change', function () {
                if ($('#item_id').val() !== '') {
                    item = $('#item_id :selected').data('item');
                    $('#store_vou_item_code').val($('#item_id :selected').data('itemname'));
                    $('#item_balance').val($('#item_id :selected').data('balance'));
                    $('#store_vou_item_price_unit').val(item.item_price_cost);
                    $('#store_vou_item_price_unit_vat').val(getNum($('#store_vou_item_price_unit').val() * 1.15))
                    $('#item_price_cost').val(item.item_price_cost);
                    $('#last_price_cost').val(item.last_price_cost);
                    $('#store_vou_qnt_i').val('');
                    $('#store_vou_item_total_price').val('');

                    $('#exampleModalLongTitle').text($('#item_id :selected').data('item').item_name_a + $('#item_id :selected').data('item').item_code)
                }
            });

            $('#store_vou_qnt_i').keyup(function () {
                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_i').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_i').val() * $('#store_vou_item_price_unit').val()));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));

                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a);


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b);


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))
            });

            $('#store_vou_item_price_unit').keyup(function () {

                $('#store_vou_item_price_unit_vat').val(getNum($('#store_vou_item_price_unit').val() * 1.15))


                if ($('#item_id').val() == '') {
                    $('#store_vou_qnt_i').val(0)
                    return toastr.warning('لابد من اختيار الصنف');
                }
                if (!$('#store_vou_qnt_i').val()) {
                    $('#store_vou_qnt_i').val(0)
                    return toastr.warning('لابد من ادخال الكمية');
                }
                $('#store_vou_item_total_price').val(getNum($('#store_vou_qnt_i').val() * $('#store_vou_item_price_unit').val()));
                $('#store_vou_vat_amount').val(getNum($('#store_vou_item_total_price').val() * vat_rate));
                $('#store_vou_price_net').val(parseFloat($('#store_vou_item_total_price').val()) + parseFloat($('#store_vou_vat_amount').val()));


                var total_sum_div = $('#total_sum_div').text();
                var a = parseFloat(total_sum_div) + parseFloat($('#store_vou_item_total_price').val());
                $('#total_sum_div_0').text(a);


                var total_sum_vat_div = $('#total_sum_vat_div').text();
                var b = parseFloat(total_sum_vat_div) + parseFloat($('#store_vou_vat_amount').val());
                $('#total_sum_vat_div_0').text(b);


                var total_sum_net_div = $('#total_sum_net_div').text();
                var c = parseFloat(total_sum_net_div) + parseFloat($('#store_vou_price_net').val());
                $('#total_sum_net_div_0').text(c.toFixed(2))

            });
        });

        function checkItemInput() {

            if (
                $('#item_id').val() == '' ||
                $('#store_vou_item_code').val() == '' ||
                $('#store_vou_qnt_i').val() == '' ||

                $('#store_vou_item_price_unit').val() == '' ||
                $('#store_vou_item_total_price').val() == '' ||
                $('#invoice_date_external').val() == '') {
                return false;
            }

            return true;

        }

        function saveItemRow(el) {
            var vat_rate = '{{$purchase->vendor->customer_vat_rate ? $purchase->vendor->customer_vat_rate : 15/100}}';
            console.log(vat_rate)
            if (el == 1) {
                console.log('a')
                if (!checkItemInput()) {
                    return toastr.warning('لا يوجد بيانات لاضافتها');
                } else {
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
                        'store_vou_qnt_i': parseFloat($('#store_vou_qnt_i').val()),
                        'store_vou_item_price_cost': parseFloat($('#last_price_cost').val()),
                        'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit').val()),
                        'store_vou_item_amount': parseFloat($('#store_vou_item_amount').val()),
                        'item_balance': parseFloat($('#item_balance').val()),
                        'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price').val()),
                        'last_price_cost': parseFloat($('#last_price_cost').val()),
                        'store_vou_vat_rate': vat_rate,
                        'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount').val()),
                        'store_vou_price_net': parseFloat($('#store_vou_price_net').val()),
                        'store_vou_desc': parseFloat($('#total_discount_div').val()),
                        'item_stor_dt_serial': $("input[name='item_stor_dt_serial[]']")
                            .map(function () {
                                return $(this).val();
                            }).get(),
                    };

                    url = '{{ route('store-item-purchase-new-receiving.store') }}';
                    var form = new FormData($('#item_data_form')[0]);
                    form.append('item_table_data', JSON.stringify(row_data));
                    form.append('item_type', 'request');

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
                            location.reload(true)
                        }
                        else {
                            toastr.warning(data.msg);
                        }
                    });
                }
            }

            if (el == 2) {

                row_data = {
                    'store_vou_vat_rate': vat_rate,
                    'store_vou_vat_amount': parseFloat($('#total_sum_vat_div').text()),
                    'store_vou_price_net': parseFloat($('#total_sum_net_div').text()),
                    'store_vou_desc': parseFloat($('#total_discount_div').val())
                };

                url = '{{ route('store-item-purchase-new-receiving.update') }}';

                var form = new FormData($('#item_data_form')[0]);

                form.append('item_table_data', JSON.stringify(row_data));

                var data = form;
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,

                }).done(function (data) {
                    // console.log(data)
                    window.open(data, "_self")
                    // if (data.success) {
                    //     toastr.success(data.msg);
                    //     updateItemTable(rowNo, row_data, data.uuid, data.total);
                    // }
                    // else {
                    //     toastr.warning(data.msg);
                    // }

                });

            }
        }


        function updateItemTable(rowNo, rowData, uuid, total) {
            uuid = "'" + uuid + "'";
            markup =
                '<tr id=' + uuid + '>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'count">' + rowData['count'] + '</td>' +
                '<td  class="ctd " id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_code">' + rowData['store_vou_item_code'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_qnt_i">' + rowData['store_vou_qnt_i'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_price">' + rowData['store_vou_item_price_unit'] + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'item_stor_dt_serial">' + '<i class="btn btn-danger fa fa-plus btn-block"></i>' + '</td>' +
                '<td  class="ctd" id="' + 'tr' + rowNo + 'store_vou_item_total_price">' + rowData['store_vou_item_total_price'] + '</td>' +

                // '<td  class="ctd" id="'+'tr'+rowNo+'store_voue_disc_value">'+ rowData['store_voue_disc_value'] +'</td>'+
                // '<td  class="ctd" id="'+'tr'+rowNo+'store_voue_disc_value">'+ rowData['store_voue_disc_value'] +'</td>'+
                // '<td  class="ctd" id="'+'tr'+rowNo+'store_voue_disc_value">'+ rowData['store_vou_disc_amount'] +'</td>'+

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
                url: "{{route('store-purchase.delete')}}",
                data: form,
                cache: false,
                contentType: false,
                processData: false,


            }).done(function (data) {
                if (data.success) {
                    toastr.success(data.msg);
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
            console.log(total['total_sum'])
            $('#total_sum_div').text(total['total_sum']);
            $('#total_sum').val(total['total_sum']);

            $('#total_sum_net_div').text(total['total_sum_net']);

            $('#total_sum').val(total['total_sum_net'])

            if ($('#total_sum').val() == 0) {
                $('#add_bond').attr('disabled', 'disabled')
            } else {
                $('#add_bond').removeAttr('disabled')
            }


            $('#total_sum_net').val(total['total_sum_net']);

            $('#total_sum_vat_div').text(total['total_sum_vat']);
            $('#total_sum_vat').val(total['total_sum_vat']);
            $('#total_discount_div').val(0);
            $('#vou_discount_rate').val(0);
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

    <!-- add item  -->
    <script type="text/javascript">
        $(document).ready(function () {
            $('#company_id_m').change(function () {
                if (!$('#company_id_m').val()) {
                    $('#company_id_m').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#company_id_m').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#item_category_m').change(function () {
                if (!$('#item_category_m').val()) {
                    $('#item_category_m').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#item_category_m').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });

            $('#branch_id_m').change(function () {
                if (!$('#branch_id_m').val()) {
                    $('#branch_id_m').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#branch_id_m').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });


            $('#item_code_m').keyup(function () {

                if ($('#item_code_m').val().length < 3) {
                    $('#item_code_m').addClass('is-invalid')
                } else {
                    $('#item_code_m').removeClass('is-invalid');
                }
            });

            $('#item_name_a_m').keyup(function () {
                if ($('#item_name_a_m').val().length < 3) {
                    $('#item_name_a_m').addClass('is-invalid')
                } else {
                    $('#item_name_a_m').removeClass('is-invalid');
                }
            });

            $('#item_name_e_m').keyup(function () {
                if ($('#item_name_e_m').val().length < 3) {
                    $('#item_name_e_m').addClass('is-invalid')
                } else {
                    $('#item_name_e_m').removeClass('is-invalid');
                }
            });

            $('#item_vendor_code_m').keyup(function () {
                if ($('#item_vendor_code_m').val().length < 3) {
                    $('#item_vendor_code_m').addClass('is-invalid')
                } else {
                    $('#item_vendor_code_m').removeClass('is-invalid');
                }
            });

            $('#item_location_m').keyup(function () {
                if ($('#item_location_m').val().length < 3) {
                    $('#item_location_m').addClass('is-invalid')
                } else {
                    $('#item_location_m').removeClass('is-invalid');
                }
            });

            $('#item_code_1_m').keyup(function () {
                if ($('#item_code_1_m').val().length < 3) {
                    $('#item_code_1_m').addClass('is-invalid')
                } else {
                    $('#item_code_1_m').removeClass('is-invalid');
                }
            });

            $('#item_code_2_m').keyup(function () {
                if ($('#item_code_2_m').val().length < 3) {
                    $('#item_code_2_m').addClass('is-invalid')
                } else {
                    $('#item_code_2_m').removeClass('is-invalid');
                }
            });

            // $('#item_price_sales').keyup(function () {
            //     if ($('#item_price_sales').val().length < 1) {
            //         $('#item_price_sales').addClass('is-invalid')
            //     } else {
            //         $('#item_price_sales').removeClass('is-invalid');
            //     }
            // });

            // $('#item_price_cost').keyup(function () {
            //     if ($('#item_price_cost').val().length < 1) {
            //         $('#item_price_cost').addClass('is-invalid')
            //     } else {
            //         $('#item_price_cost').removeClass('is-invalid');
            //     }
            // });

            // $('#item_balance').keyup(function () {
            //     if ($('#item_balance').val().length < 1) {
            //         $('#item_balance').addClass('is-invalid')
            //     } else {
            //         $('#item_balance').removeClass('is-invalid');
            //     }
            // });

            $('#item_unit_m').change(function () {
                if (!$('#item_unit_m').val()) {
                    $('#item_unit_m').addClass('is-invalid');
                    //$('.car').addClass("is-invalid");
                } else {
                    $('#item_unit_m').removeClass('is-invalid');
                    //$('.car').removeClass("is-invalid");
                }
            });


        });

        function saveItem() {
            if ($('.is-invalid').length > 0) {
                return toastr.warning('تاكد من ادخال كافة الحقول');
            }
            url = '{{ route('store-item.store') }}'
            var form = new FormData($('#add_item_data_form')[0]);
            form.append('company_id', $('#company_id').val());
            form.append('warehouses_type', $('#warehouses_type').val());
            form.append('branch_id', $('#branch_id').val());

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

                    closeAddItemModal();

                }
                else {
                    toastr.warning(data.msg);
                }
            });
        }

        function closeAddItemModal() {
            $('#add_item_modal').on('hidden.bs.modal', function () {
                //$('#add_item_modal .modal-body').html('');
            });
            $('#add_item_modal').modal('hide');
        }

    </script>


    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                item_serial: 0
            },
            computed: {
                item_serial_rows: function () {
                    return parseInt(this.item_serial);
                }
            }
        })
    </script>

@endsection

