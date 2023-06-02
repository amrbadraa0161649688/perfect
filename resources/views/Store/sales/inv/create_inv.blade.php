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


<div id="create_inv_modal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title" style="text-align:right">
                    @lang('sales.add_new_inv')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="inv_form" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-controls-stacked">
                                        <!-- <label class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" class="custom-control-input" name="create_tyep" id="create_tyep" value="new" >
                                            <span class="custom-control-label"> امر شراء جديد </span>
                                        </label> -->
                                        <label class="d-block custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="create_tyep"
                                                   id="create_tyep" value="new">
                                            <span class="custom-control-label">@lang('sales.add_inv_button')</span>
                                        </label>

                                        <label class="d-block custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="create_tyep"
                                                   id="create_tyep" value="from_request">
                                            <span class="custom-control-label">@lang('sales.import_qutation')</span>
                                        </label>

                                        <label class="d-block custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" name="create_tyep"
                                                   id="create_tyep" value="from_request_mntns">
                                            <span class="custom-control-label">@lang('sales.sales_inv_mntns')</span>
                                        </label>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="row" id="from_request" style="display:none;">
                            <div class="col-md-6">
                                <label for="recipient-name" class="col-form-label "> @lang('sales.quote_no')</label>
                                <input type="text" class="form-control" name="request_code" id="request_code" value=""
                                       onchange="getDataByCode(this)">
                            </div>
                            <div id="showResult"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" id="new" style="display:none;">
                <div class="modal-body">

                    <form id="quote_data_form" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label "> @lang('sales.warehouse_type') </label>
                                        <select class="form-select form-control is-invalid" name="store_category_type"
                                                id="store_category_type" required>
                                            <option value="" selected> choose</option>
                                            @foreach($warehouses_type_list as $w_t)
                                                <option value="{{$w_t->system_code}}"> {{ $w_t->getSysCodeName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('sales.customer_name') </label>

                                            <select class="selectpicker show-tick form-control is-invalid"
                                                    data-live-search="true" name="store_acc_no" id="store_acc_no"
                                                    required>


                                                <option value="" selected> choose</option>
                                                @foreach($customer as $cus)
                                                    <option value="{{$cus->customer_id}}"
                                                            data-vendorname="{{ $cus->getCustomerName() }}"
                                                            data-vendorvat="{{ $cus->customer_vat_no }}"> {{ $cus->getCustomerName() }}
                                                        - - {{ $cus->customer_mobile }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label ">  @lang('sales.customer_name') </label>
                                        <input type="text" class="form-control is-invalid" name="store_acc_name"
                                               id="store_acc_name" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label "> @lang('sales.tax_no') </label>
                                        <input type="number" class="form-control is-invalid" name="store_acc_tax_no"
                                               id="store_acc_tax_no" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label "> @lang('sales.mobile_no') </label>
                                        <input type="number" class="form-control " name="store_vou_ref_after"
                                               id="store_vou_ref_after" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('sales.payment_method') </label>
                                        <select class="form-select form-control is-invalid" name="store_vou_pay_type"
                                                id="store_vou_pay_type" required>
                                            <option value="" selected> choose</option>
                                            @foreach($payemnt_method_list as $p_method)
                                                <option value="{{$p_method->system_code}}"> {{ $p_method->getSysCodeName() }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-8">
                                        <label for="recipient-name"
                                               class="col-form-label">@lang('purchase.note') </label>
                                        <textarea rows="2" class="form-control" name="store_vou_notes"
                                                  id="store_vou_notes" placeholder="Here can be your note"
                                                  value=""></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('sales.sales_man') </label>
                                        <select class="form-control" data-live-search="true" name="store_vou_ref_4"
                                                id="store_vou_ref_4">
                                            <option value="" selected> choose</option>
                                            @foreach($employees as $p_employees)
                                                <option value="{{$p_employees->emp_id}}"> {{ $p_employees->emp_name_full_ar }} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="modal-footer" id="invadd">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <button type="button" id="btnCancel" class="btn btn-secondary btn-block"
                                        data-dismiss="modal" onclick="closeItemModal()">الغاء
                                </button>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">

                                <button type="button" id="btnSave" class="btn btn-success forward btn-block"
                                        onclick="saveRequestnew()">حفـظ
                                </button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>


            {{-- edit form --}}

            <div class="row" id="from_request_mntns" style="display:none;">
                <div class="modal-body">

                    <form id="mntns_data_form" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label "> @lang('sales.warehouse_type') </label>
                                        <select class="form-select form-control is-invalid" name="store_category_type"
                                                id="store_category_type_mnts" required>
                                            <option value="" selected> choose</option>
                                            @foreach($warehouses_type_list as $w_t)
                                                <option value="{{$w_t->system_code}}"> {{ $w_t->getSysCodeName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('sales.sales_inv_mntns_no') </label>

                                            <select class="selectpicker show-tick form-control is-invalid"
                                                    data-live-search="true" name="store_mntns_no" id="store_mntns_no"
                                                    required>

                                                <option value="" selected> choose</option>
                                                @foreach($mntns_card as $mntns_cards)
                                                    <option value="{{$mntns_cards->mntns_cards_id}}"
                                                            data-vendorname="{{ $mntns_cards->mntns_cards_no }}"
                                                            data-carid="{{$mntns_cards->car_cost_center}}"
                                                            data-car="{{$mntns_cards->car}}"
                                                            data-carbrand="{{$mntns_cards->car->brand->system_code_name_ar}}"
                                                            data-customername="{{$mntns_cards->customer->customer_name_full_ar}}"
                                                            data-vendorvat="{{ $mntns_cards->customer_id }}"> {{ $mntns_cards->mntns_cards_no }}
                                                        - - {{ $mntns_cards->car->mntns_cars_plate_no }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label ">  @lang('sales.sales_inv_mntns_name') </label>
                                        <input type="text" class="form-control" name="store_acc_name"
                                               id="store_acc_name_mnts" value="">
                                    </div>


                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('sales.sales_inv_mntns_tech') </label>
                                        <select class="selectpicker show-tick form-control is-invalid"
                                                data-live-search="true" name="store_mntns_tech" id="store_mntns_tech"
                                                required>
                                            <option value="" selected> choose</option>
                                            @foreach($employees_e as $employeess)
                                                <option value="{{$employeess->emp_id}}"
                                                        data-vendorname1="{{ $employeess->emp_name_full_ar }}"
                                                        data-vendorvat1="{{ $employeess->emp_code }}"> {{ $employeess->emp_name_full_ar }}
                                                    - - {{ $employeess->emp_code }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{--سيارات كروت الصيانه--}}
                                    <div class="col-md-4">
                                        <label for="recipient-name"
                                               class="col-form-label"> @lang('home.trucks') </label>
                                        <select class="form-control is-invalid"
                                                data-live-search="true" name="mntns_cars_id" id="mntns_cars_id"
                                                required>
                                            <option value="" selected> choose</option>
                                            @foreach($cars_list as $car_list)
                                                <option value="{{ $car_list->mntns_cars_id}}">
                                                    {{ $car_list->mntns_cars_vat_no }}
                                                    -- {{ $car_list->mntns_cars_plate_no }}
                                                    -- {{$car_list->brand->system_code_name_ar}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col-md-8">
                                        <label for="recipient-name"
                                               class="col-form-label">@lang('purchase.note') </label>
                                        <textarea rows="2" class="form-control" name="store_vou_notes"
                                                  id="store_vou_notes" placeholder="Here can be your note"
                                                  value=""></textarea>
                                    </div>


                                    {{--<div class="col-md-4">--}}
                                    {{--<label for="recipient-name"--}}
                                    {{--class="col-form-label "> @lang('sales.tax_no') </label>--}}
                                    {{--<input type="number" class="form-control is-invalid" name="store_acc_tax_no"--}}
                                    {{--id="store_acc_tax_no_mnts" value="">--}}
                                    {{--</div>--}}

                                </div>
                            </div>
                        </div>


                        <div class="modal-footer" id="invadd">
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                                <button type="button" id="btnCancel" class="btn btn-secondary btn-block"
                                        data-dismiss="modal" onclick="closeItemModal()">الغاء
                                </button>
                            </div>
                            <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">

                                <button type="button" id="btnSave" class="btn btn-success forward btn-block"
                                        onclick="saveRequestnew()">حفـظ
                                </button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>


            <div class="modal-footer" id="add">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" data-dismiss="modal"
                            onclick="closeItemModal()">الغاء
                    </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">

                    <button type="button" id="btnSave" class="btn btn-primary forward btn-block"
                            onclick="saveRequest()">اضافة
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>


<script type="text/javascript">

    $(document).ready(function () {

        $('#store_acc_no').selectpicker();

        $('#store_mntns_no').selectpicker();
        $('#store_mntns_tech').selectpicker();
        // $('#mntns_cars_id').selectpicker();

        $("input[name='create_tyep']").click(function () {

            if ($(this).val() == 'new') {
                $('#new').show();
                $('#from_request').hide();
                $('#from_request_mntns').hide();
                $('#mntns_data_form').hide();
                $('#add').hide();
                $('#invadd').show();
            }
            else if ($(this).val() == 'from_request_mntns') {
                $('#from_request_mntns').show();
                $('#mntns_data_form').show();

                $('#from_request').hide();
                $('#new').hide();
                $('#add').hide();
                $('#invadd').hide();
            }
            else {
                $('#new').hide();
                $('#from_request_mntns').hide();
                $('#mntns_data_form').hide();
                $('#from_request').show();
                $('#invadd').hide();
                $('#add').show();
            }

        });

        $('#store_acc_no').on('change', function () {
            $('#store_acc_name').val($('#store_acc_no :selected').data('vendorname'));
            $('#store_acc_name').removeClass('is-invalid');
            $('#store_acc_tax_no').val($('#store_acc_no :selected').data('vendorvat'));
            $('#store_acc_tax_no').removeClass('is-invalid');
        });

        $('#store_mntns_no').on('change', function () {
            $('#store_acc_name_mnts').val($('#store_mntns_no :selected').data('customername'));
            $('#store_acc_name_mnts').removeClass('is-invalid');

            $('#mntns_cars_id').val($('#store_mntns_no :selected').data('carid'))
            $car = $('#store_mntns_no :selected').data('car');
            $car_brand = $('#store_mntns_no :selected').data('carbrand');
            $('#mntns_cars_id').find('option').remove().end().append($('<option>', {
                value: $car.mntns_cars_id,
                text: $car.mntns_cars_plate_no + '-' + $car_brand
            }));

        });

        $('#store_mntns_no').on('change', function () {
            $('#store_mntns_no').removeClass('is-invalid');
            $('#store_mntns_no').parent().removeClass('is-invalid');
        });

        $('#store_acc_name_mnts').keyup(function () {
            if (!$('#store_acc_name_mnts').val()) {
                $('#store_acc_name_mnts').addClass('is-invalid');
            } else {
                $('#store_acc_name_mnts').removeClass('is-invalid');
            }
        });

        $('#store_mntns_tech').change(function () {
            if (!$('#store_mntns_tech').val()) {
                $('#store_mntns_tech').addClass('is-invalid');
            } else {
                $('#store_mntns_tech').removeClass('is-invalid');
                $('#store_mntns_tech').parent().removeClass('is-invalid');
            }
        });

        $('#store_acc_tax_no_mnts').change(function () {
            if (!$('#store_acc_tax_no_mnts').val()) {
                $('#store_acc_tax_no_mnts').addClass('is-invalid');
            } else {
                $('#store_acc_tax_no_mnts').removeClass('is-invalid');
            }
        });


        $('#store_acc_no').change(function () {
            if (!$('#store_acc_no').val()) {
                $('#store_acc_no').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_acc_no').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_category_type').change(function () {

            if (!$('#store_category_type').val()) {
                $('#store_category_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_category_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_category_type_mnts').change(function () {
            if (!$('#store_category_type_mnts').val()) {
                $('#store_category_type_mnts').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_category_type_mnts').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });


        $('#store_vou_pay_type').change(function () {
            if (!$('#store_vou_pay_type').val()) {
                $('#store_vou_pay_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_vou_pay_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_acc_name').keyup(function () {
            if ($('#store_acc_name').val().length < 3) {
                $('#store_acc_name').addClass('is-invalid')
            } else {
                $('#store_acc_name').removeClass('is-invalid');
            }
        });

        $('#store_acc_tax_no').keyup(function () {
            if ($('#store_acc_tax_no').val().length < 1) {
                $('#store_acc_tax_no').addClass('is-invalid')
            } else {
                $('#store_acc_tax_no').removeClass('is-invalid');
            }
        });


    });

    function selectItem(e) {
        id = '#store_vou_qnt_o_' + e.value;
        store_vou_item_price_unit = '#store_vou_item_price_unit_' + e.value;
        store_voue_disc_value = '#store_voue_disc_value_' + e.value;
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_' + e.value).val());
        if (store_vou_qnt_t_i_r == 0) {
            return toastr.warning('تم طلب جميع الكميات لا يمكن الطلب ');
            $(e).prop('checked', false);
        }
        if ($(e).prop('checked') == true) {
            $(id).prop('readonly', false);
            //$(store_voue_disc_value).prop('readonly', false);
            $(store_vou_item_price_unit).prop('readonly', false);
        }
        else {
            $(id).prop('readonly', true);
            //$(store_voue_disc_value).prop('readonly', true);
            $(store_vou_item_price_unit).prop('readonly', true);
        }
        calculateTotal(e);
        //console.log(e.value);
    }

    function calculateItem(e) {
        store_vou_qnt_q = parseFloat($('#store_vou_qnt_q_' + e.name).val());
        store_vou_qnt_o = parseFloat($('#store_vou_qnt_o_' + e.name).val());
        store_vou_qnt_t_i_r = parseFloat($('#store_vou_qnt_t_i_r_' + e.name).val());

        if (store_vou_qnt_o <= 0) {
            $('#store_vou_qnt_o_' + e.name).val(store_vou_qnt_q);
            $('#store_vou_qnt_t_i_r_' + e.name).val(0);
            return toastr.warning('لايمكن اضافة كيمة اقل من صفر ');
        }

        if (store_vou_qnt_o > store_vou_qnt_q) {
            $('#store_vou_qnt_o_' + e.name).val(store_vou_qnt_q);
            toastr.warning('لايمكن اضافة كيمة اكبر من كمية الطلب');
        }
        else {

            $('#store_vou_qnt_t_i_r_' + e.name).val(store_vou_qnt_q - store_vou_qnt_o);

        }

        calculateTotal(e);

    }

    function calculateTotal(e) {
        vat_rate = $('#store_acc_no :selected').data('customervatrate');
        //console.log(e.value);
        unit_price = parseFloat($('#store_vou_item_price_unit_' + e.name).val());
        store_vou_item_total_price = '#store_vou_item_total_price_' + e.name;
        store_vou_vat_amount = '#store_vou_vat_amount_' + e.name;
        store_vou_price_net = '#store_vou_price_net_' + e.name;

        store_vou_qnt_o = parseFloat($('#store_vou_qnt_o_' + e.name).val());

        store_vou_disc_type = parseFloat($('#store_vou_disc_type_' + e.name).val());
        //console.log('disc type id'+store_vou_disc_type );

        store_voue_disc_value = parseFloat($('#store_voue_disc_value_' + e.name).val());
        store_vou_disc_amount = '#store_vou_disc_amount_' + e.name;


        store_vou_item_total_price_amount = unit_price * parseFloat(store_vou_qnt_o);
        $(store_vou_item_total_price).val(store_vou_item_total_price_amount);
        //console.log('store_vou_item_total_price_amount = '+store_vou_item_total_price_amount );


        if (store_vou_disc_type == 533) {
            //console.log('fix');
            //console.log(parseFloat($(store_vou_item_total_price).val()));
            //console.log(store_voue_disc_value);

            if (store_voue_disc_value > 100) {
                $('#store_voue_disc_value_' + e.name).val(0)
                return alert('لايمكن تطبيق خصم اكثر من 100%');
            }
            $discount_amount = (unit_price * store_vou_qnt_o * store_voue_disc_value / 100);
            $(store_vou_disc_amount).val(parseFloat($discount_amount).toFixed(2));
        }
        else {
            //console.log('fix');
            //console.log(parseFloat($(store_vou_item_total_price).val()));
            //console.log(store_voue_disc_value);
            if (store_voue_disc_value > parseFloat($(store_vou_item_total_price).val())) {
                $('#store_voue_disc_value_' + e.name).val(0)
                return alert(' لا يمكن تطبيق خصم اكثر من القيمة');
            }
            $discount_amount = store_voue_disc_value;
            $(store_vou_disc_amount).val($discount_amount);
        }

        store_vou_item_total_price_amount = unit_price * parseFloat(store_vou_qnt_o) - $discount_amount;
        $(store_vou_item_total_price).val(parseFloat(store_vou_item_total_price_amount).toFixed(2));
        //console.log('store_vou_item_total_price_amount = '+store_vou_item_total_price_amount );

        vat_rate = vat_rate;
        $(store_vou_vat_amount).val(parseFloat(vat_rate * parseFloat($(store_vou_item_total_price).val())).toFixed(2));
        $(store_vou_price_net).val(parseFloat(parseFloat($(store_vou_item_total_price).val()) + parseFloat($(store_vou_vat_amount).val())).toFixed(2))

        calculateTotalOfAll();
        //console.log('cal');
    }

    function calculateTotalOfAll() {
        total_sum = 0;
        $('.total_sum').each(function () {
            selected = '#selected_item_' + $(this)[0].name;
            if ($(selected).prop('checked') == true) {
                total_sum = total_sum + parseFloat($(this).val());
            }
        });
        $('#total_sum_div').text(total_sum);

        total_disc = 0;
        $('.total_disc').each(function () {
            selected = '#selected_item_' + $(this)[0].name;
            if ($(selected).prop('checked') == true) {
                total_disc = total_disc + parseFloat($(this).val());
            }
        });
        $('#total_disc_div').text(total_disc);

        total_sum_vat = 0;
        $('.total_sum_vat').each(function () {
            selected = '#selected_item_' + $(this)[0].name;
            if ($(selected).prop('checked') == true) {
                total_sum_vat = total_sum_vat + parseFloat($(this).val());
            }
        });
        $('#total_sum_vat_div').text(total_sum_vat);

        total_sum_net = 0;
        $('.total_sum_net').each(function () {
            selected = '#selected_item_' + $(this)[0].name;
            if ($(selected).prop('checked') == true) {
                total_sum_net = total_sum_net + parseFloat($(this).val());
            }
        });
        $('#total_sum_net_div').text(total_sum_net);

    }

    function checkAll(ele) {
        $('input:checkbox').not(ele).prop('checked', ele.checked);
        calculateTotalOfAll();
    }


    function getDataByCode(ele) {
        url = '{{ route('get-store-purchase-by-code') }}'
        $('#showResult').html('');
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                "_token": "{{ csrf_token() }}",
                company_id: $('#company_id').val(),
                warehouses_type: $('#warehouses_type').val(),
                branch_id: $('#branch_id').val(),
                request_code: $('#request_code').val(),
                page: 'inv',
            },
            dataType: 'json',

        }).done(function (data) {
            if (data.success) {
                toastr.success(data.msg);
                $('#showResult').html(data.view);

            }
            else {
                toastr.warning(data.msg);
            }
        });
    }

    function saveRequest() {
        vat_rate = $('#store_acc_no :selected').data('customervatrate');
        item_data = [];
        $('.item-qty').each(function () {

            uuid = $(this)[0].name;
            checked_item = '#selected_item_' + uuid;
            console.log(checked_item);
            checked_item = $(checked_item).prop('checked');
            if (checked_item == true) {
                //console.log(uuid+ '='+checked_item);
                row = {
                    'uuid': uuid,
                    'store_vou_qnt_q': parseFloat($('#store_vou_qnt_q_' + uuid).val()),
                    'store_vou_qnt_o': parseFloat($('#store_vou_qnt_o_' + uuid).val()),
                    'store_vou_qnt_t_i_r': parseFloat($('#store_vou_qnt_t_i_r_' + uuid).val()),

                    'store_vou_item_price_unit': parseFloat($('#store_vou_item_price_unit_' + uuid).val()),
                    'store_vou_item_total_price': parseFloat($('#store_vou_item_total_price_' + uuid).val()),

                    'store_vou_disc_type': parseFloat($('#store_vou_disc_type_' + uuid).val()),
                    'store_voue_disc_value': parseFloat($('#store_voue_disc_value_' + uuid).val()),
                    'store_vou_disc_amount': parseFloat($('#store_vou_disc_amount_' + uuid).val()),

                    'store_vou_vat_rate': vat_rate,
                    'store_vou_vat_amount': parseFloat($('#store_vou_vat_amount_' + uuid).val()),

                    'store_vou_price_net': parseFloat($('#store_vou_price_net_' + uuid).val()),


                };
                item_data.push(row);
            }

        });

        if (item_data.length == 0) {
            return toastr.warning('لا بد من اختيار صنف واحد على الاقل');
        }


        url = '{{ route('store-sales-inv.store') }}'
        var form = new FormData($('#inv_form')[0]);
        form.append('company_id', $('#company_id').val());
        form.append('warehouses_type', $('#warehouses_type').val());
        form.append('branch_id', $('#branch_id').val());
        form.append('item_data', JSON.stringify(item_data));
        form.append('store_vou_ref_before', $('#store_vou_ref_before').val());

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
                //url =  '{{ route("store-purchase-order.edit", ":id") }}';
                //url = url.replace(':id',data.uuid);
                //window.location.href = url;
                closeItemModal();
                getData();

            }
            else {
                toastr.warning(data.msg);
            }
        });
    }

    function saveRequestnew() {

        // if ($('.is-invalid').length > 0) {
        //     return toastr.warning('تاكد من ادخال كافة الحقول');
        // }

        url = '{{ route('store-sales-inv.storenew') }}'
        if ($('#quote_data_form').parent().parent().css("display") != 'none') {
            var form = new FormData($('#quote_data_form')[0]);
            form.append('inv_type', 'inv_new');
        }

        if ($('#mntns_data_form').parent().parent().css("display") != 'none') {
            var form = new FormData($('#mntns_data_form')[0]);
            form.append('inv_type', 'card_mnts');
        }

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
                url = '{{ route("store-sales-invnew.edit", ":id") }}';
                url = url.replace(':id', data.uuid);
                window.location.href = url;

            }
            else {
                toastr.warning(data.msg);
            }
        });
    }
</script>

