@if(auth()->user()->user_type_id != 1)
    @foreach(session('job')->permissions as $job_permission)
        @if($job_permission->app_menu_id == 81 && $job_permission->permission_add)
            <div class="row">
                <div class="col-md-3">
                    <a class="btn btn-icon" href="{{ route('sales-car-receiving.edit',$row->uuid ) }}"
                       title="">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>
                <div class="col-md-3">
                    <a class="btn btn-icon"
                       href="{{config('app.telerik_server')}}?rpt={{$row->report_url_ins->report_url}}&id={{$row->uuid}}&lang=ar&skinName=bootstrap"
                       title="" target="_blank">
                        <i class="fa fa-print"></i>
                    </a>
                </div>
            <!-- <div class="col-md-6">
                <button class="btn btn-icon" onclick="deletItem('{{$row->uuid}}')" title="Delete Item">
                    <i class="fa fa-trash"></i>
                </button>
            </div> -->

                @if( $row->store_vou_total-$row->store_vou_payment != 0)
                    <div class="col-md-2">
                        <button type="button" class="btn btn-link btn-lg" data-toggle="modal"
                                data-target="#exampleModal{{ $row->uuid }}" data-whatever="@mdo">
                            @lang('home.add_bond')
                        </button>
                    </div>
                @endif

            </div>
        @endif
    @endforeach
@endif

@if(auth()->user()->user_type_id == 1)
    <div class="row">
        <div class="col-md-3">
            <a class="btn btn-icon" href="{{ route('sales-car-receiving.edit',$row->uuid ) }}"
               title="">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <div class="col-md-3">
            <a class="btn btn-icon"
               href="{{config('app.telerik_server')}}?rpt={{$row->report_url_ins->report_url}}&id={{$row->uuid}}&lang=ar&skinName=bootstrap"
               title="" target="_blank">
                <i class="fa fa-print"></i>
            </a>
        </div>

        @if( $row->store_vou_total-$row->store_vou_payment != 0)
            <div class="col-md-2">
                <button type="button" class="btn btn-link btn-lg" data-toggle="modal"
                        data-target="#exampleModal{{ $row->uuid }}" data-whatever="@mdo">
                    @lang('home.add_bond')
                </button>
            </div>
    @endif


    <!-- <div class="col-md-6">
            <button class="btn btn-icon" onclick="deletItem('{{$row->uuid}}')" title="Delete Item">
                <i class="fa fa-trash"></i>
            </button>
        </div> -->
    </div>
@endif

<div class="modal fade" id="exampleModal{{$row->uuid}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('add_cash_bond')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div class="modal-body">
                <form action="{{ route('store-sales-car-receiving-bondJournal') }}"
                      method="post">
                    @csrf
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.sub_company')</label>
                                @if(session('company'))
                                    <input type="text" class="form-control" disabled=""
                                           value="{{ app()->getLocale()=='ar' ? session('company')['company_name_ar']
                                                            : session('company')['company_name_en ']}}">
                                @else
                                    <input type="text" class="form-control" disabled=""
                                           value="{{ app()->getLocale()=='ar' ? auth()->user()->company->company_name_ar
                                                            :  auth()->user()->company->company_name_en }}">
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.branch')</label>
                                <input type="text" class="form-control" disabled=""
                                       value="{{ app()->getLocale()=='ar' ? session('branch')['branch_name_ar']
                                                            : session('branch')['branch_name_en'] }}">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.created_date')</label>
                                <input type="text" class="form-control date" disabled="">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.user')</label>
                                <input type="text" class="form-control" disabled=""
                                       placeholder="Company"
                                       value="{{ app()->getLocale()=='ar' ? auth()->user()->user_name_ar :
                                                auth()->user()->user_name_en }}">
                            </div>
                        </div>

                        <input type="hidden" name="transaction_id"
                               value="{{$row->store_hd_id}}">
                        {{--النشاط--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bonds_activity')</label>

                                <input type="text" disabled="" value="اذن دخول سيارات"
                                       class="form-control">
                                <input type="hidden" name="transaction_type" value="64">
                            </div>
                        </div>

                        {{--الرقم المرجعي--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.reference_number')</label>

                                <input type="text" class="form-control"
                                       name="bond_ref_no"
                                       value="{{ $row->store_hd_code }}" readonly>

                            </div>
                        </div>

                        {{--القيمه المستحقه--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.deserved_value')</label>
                                <input type="text" class="form-control" readonly
                                       id="deserved_value"
                                       value="{{ $row->store_vou_total - $row->store_vou_payment }}">
                            </div>
                        </div>

                        {{--نوع الحساب--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_type')</label>

                                <input type="text" class="form-control" readonly
                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56001)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56001)
                                                   ->first()->system_code_name_en}}">
                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56001)
                                            ->first()->system_code_id}}" name="account_type">
                            </div>
                        </div>


                        {{--نوع العميل--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="customer_type" value="supplier">

                                <label class="form-label">@lang('home.suppliers')</label>
                                @if($row->vendor)
                                    <input type="text" readonly class="form-control"
                                           value="{{app()->getLocale()=='ar' ? $row->vendor->customer_name_full_ar :
                                                              $row->vendor->customer_name_full_en  }}">
                                    <input type="hidden" name="customer_id"
                                           value="{{ $row->vendor->customer_id }}">
                                @endif
                            </div>
                        </div>

                        {{--{قم الحساب للمورد--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_code')</label>
                                @if($row->vendor)
                                    <input type="hidden" class="form-control"
                                           name="bond_acc_id"
                                           value="{{ $row->vendor->customer_account_id}}">
                                    <input type="text" readonly class="form-control"
                                           value="{{app()->getLocale() == 'ar' ?
                                                               $row->vendor->account->acc_name_ar :
                                                               $row->vendor->account->acc_name_en}} .
                                                                {{ $row->vendor->account->acc_code }}">
                                @endif

                            </div>
                        </div>

                        {{--انواع المصروفات--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('home.cash_types')</label>

                                @if(app()->getLocale()=='ar')
                                    <input type="text" class="form-control" readonly value="{{\App\Models\SystemCode::where('system_code',590007)
                                                            ->where('company_group_id',$row->company_group_id)->first()->system_code_name_ar}}">
                                @else
                                    <input type="text" class="form-control" readonly value="{{\App\Models\SystemCode::where('system_code',590007)
                                                            ->where('company_group_id',$row->company_group_id)->first()->system_code_name_en}}">
                                @endif

                                <input type="hidden" value="{{\App\Models\SystemCode::where('system_code',590007)
                                                            ->where('company_group_id',$row->company_group_id)->first()->system_code_id}}"
                                       name="bond_doc_type">

                            </div>
                        </div>

                        {{--طرق الدفع--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.payment_method')</label>
                                <select class="form-control" id="payment_method_code"
                                        onchange="validInputs($(this))" name="bond_method_type"
                                        required>
                                    <ooption value="">@lang('home.choose')</ooption>
                                    @foreach(App\Models\SystemCode::where('sys_category_id', 57)
                                    ->where('company_id',$row->company_id)->get() as $payment_method)
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
                                <input type="text" class="form-control" disabled
                                       name="process_number">
                            </div>
                        </div>

                        {{--البنك--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bank')</label>
                                <select class="form-control" name="bond_bank_id" id="bond_bank_id"
                                        disabled>
                                    <option value="">@lang('home.choose')</option>
                                    @foreach(App\Models\SystemCode::where('sys_category_id', 40)->get() as $bank)
                                        <option value="{{ $bank->system_code_id }}">
                                            {{ app()->getLocale()=='ar' ? $bank->system_code_name_ar :
                                             $bank->system_code_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{--القيمه--}}
                        {{--<div class="col-sm-6 col-md-4" hidden>--}}
                        {{--<div class="form-group">--}}
                        {{--<label class="form-label">@lang('home.value')</label>--}}
                        {{--<input type="number" class="form-control"--}}
                        {{--onkeyup="validPaid($(this))"--}}
                        {{--value="{{$row->store_vou_total - $row->store_vou_payment - $row->store_vou_vat_amount }}"--}}
                        {{--name="bond_amount_credit" required>--}}
                        {{--</div>--}}
                        {{--</div>--}}

                        {{--/////////////////--}}


                        {{--ااجمالي شامل الضريبه--}}
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group">
                                <label class="form-label">@lang('home.total_value')</label>
                                <input type="text" class="form-control"
                                       value="{{$row->store_vou_total - $row->store_vou_payment }}"
                                       name="bond_amount_total" required
                                       onkeyup="validPaid($(this),'{{$row->store_hd_id}}')">
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <label class="form-label">@lang('home.notes')</label>
                            <textarea class="form-control" name="bond_notes"
                                      placeholder="@lang('home.notes')"></textarea>
                        </div>


                        {{--نسبه الضريبه--}}

                        <input type="hidden" step=".0001" class="form-control"
                               name="bond_vat_rate" value="0">

                        <input type="hidden" class="form-control"
                               value="0"
                               name="bond_vat_amount" required>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm"
                                    id="submit_button{{$row->store_hd_id}}">
                                @lang('home.add_bond')
                            </button>
                        </div>


                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    var d = new Date();

    var month = d.getMonth() + 1;
    var day = d.getDate();

    var output = (day < 10 ? '0' : '') + day + '/' +
        (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
    ;
    $('.date').val(output)

    function validInputs(el) {
        //ماستر كارد و مدي و فيزا
        if (el.val() == 57002 || el.val() == 57003 || el.val() == 57004) {
            el.parent().parent().next().children().children().removeAttr('disabled')
            el.parent().parent().next().next().children().children().attr('disabled', 'disabled')
            el.parent().parent().next().next().children().children().val('')
        }
        //بنك
        if (el.val() == 57005) {
            el.parent().parent().next().next().children().children().removeAttr('disabled')
            el.parent().parent().next().children().children().removeAttr('disabled')
        }
        //نقدي
        if (el.val() == 57001) {
            el.parent().parent().next().next().children().children().attr('disabled', 'disabled')
            el.parent().parent().next().next().children().children().val('')
            el.parent().parent().next().children().children().attr('disabled', 'disabled')
            el.parent().parent().next().children().children().val('')
        }
    }

    function validPaid(el, id) {
        var deserved_value = el.parent().parent().prev().prev().prev().prev().prev().prev().prev()
            .prev().children().children().eq(1).val();
        if (parseFloat(el.val()) > parseFloat(deserved_value)) {
            $("#submit_button" + id).attr('disabled', 'disabled')
        } else {

            $("#submit_button" + id).removeAttr('disabled')
        }
    }

    function getTotal(el) {

        var deserved_value = el.parent().parent().prev().prev().prev().prev().prev().prev().prev()
            .prev().prev().children().children().eq(1).val()
        console.log(deserved_value)

        var value = el.parent().parent().prev().children().children().eq(1).val()
        el.parent().parent().next().children().children().eq(1).val(parseFloat(el.val() * value) + parseFloat(value))

        var total = parseFloat(el.val() * value) + parseFloat(value)
        console.log(total)
        if (total > parseFloat(deserved_value)) {
            console.log('fine')
            el.parent().parent().next().next().next().children().eq(0).attr('disabled', 'disabled')
        } else {
            el.parent().parent().next().next().next().children().eq(0).removeAttr('disabled', 'disabled')
        }
    }

    // function getVatAmount(el) {
    //     console.log(el.parent().parent().prev().children().children(2).val())
    //     el.parent().parent().next().children().children().val(parseFloat(el.parent().parent().prev().children().children().val()) *
    //         parseFloat(el.val()))
    // }

</script>

