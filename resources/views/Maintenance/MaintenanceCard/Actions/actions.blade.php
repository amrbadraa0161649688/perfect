<div class="row">

    @if(auth()->user()->user_type_id != 1)
        @foreach(session('job')->permissions as $job_permission)
            @if($job_permission->app_menu_id == 71 && $job_permission->permission_add)
                <div class="col-md-2">
                    <a class="btn btn-icon" href=" {{ route('maintenance-card.edit' , $row->uuid) }}"
                       title="">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>
            @endif
        @endforeach
    @endif

    @if(auth()->user()->user_type_id == 1)
        <div class="col-md-2">
            <a class="btn btn-icon" href=" {{ route('maintenance-card.edit' , $row->uuid) }}"
               title="">
                <i class="fa fa-eye"></i>
            </a>
        </div>
    @endif


    @if($row->mntns_cards_due_amount != 0)
        <div class="col-md-2">
            <button type="button" class="btn btn-icon btn-lg text-danger" data-toggle="modal"
                    data-target="#exampleModal{{$row->uuid}}" data-whatever="@mdo">
                @lang('home.add_bond')
            </button>
        </div>
    @endif
</div>

{{--سند القبض--}}
<div class="modal fade" id="exampleModal{{$row->uuid}}" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">@lang('home.add_capture_bond')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form action="{{ route('maintenance-card.addBondWithJournal') }}" method="post">
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
                                <input type="text" id="date" class="form-control" disabled="">
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
                               value="{{$row->mntns_cards_id}}">

                        {{--النشاط--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bonds_activity')</label>

                                <input type="text" disabled="" value=" كارت الصيانه"
                                       class="form-control">
                                <input type="hidden" name="transaction_type" value="58">

                            </div>
                        </div>

                        {{--الرقم المرجعي--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.reference_number')</label>

                                <input type="text" class="form-control"
                                       name="bond_ref_no"
                                       value="{{ $row->mntns_cards_no }}" readonly>
                            </div>
                        </div>

                        {{--القيمه المستحقه--}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.deserved_value')</label>
                                <input type="text" class="form-control" readonly
                                       id="deserved_value"
                                       value="{{ $row->mntns_cards_due_amount }}">

                            </div>
                        </div>

                        {{--نوع الحساب--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_type')</label>

                                <input type="text" class="form-control" readonly
                                       value="{{app()->getLocale()=='ar' ? \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_ar : \App\Models\SystemCode::where('system_code',56002)
                                                   ->first()->system_code_name_en}}">
                                <input type="hidden" value="{{ \App\Models\SystemCode::where('system_code',56002)
                                            ->first()->system_code_id}}" name="account_type">
                            </div>
                        </div>


                        {{--نوع العميل--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <input type="hidden" name="customer_type" value="customer">

                                <label class="form-label">@lang('home.customer')</label>

                                <input type="text" readonly class="form-control"
                                       value="{{app()->getLocale()=='ar' ? $row->customer_name_full_ar :
                                                              $row->customer_name_full_en  }}">
                                <input type="hidden" name="customer_id"
                                       value="{{ $row->customer_id }}">
                            </div>
                        </div>

                        {{-- قم الحساب للعميل--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.account_code')</label>
                                <input type="hidden" class="form-control"
                                       name="bond_acc_id"
                                       value="{{ $row->customer_account_id}}">
                                @if($row->acc_name_ar)
                                    <input type="text" readonly class="form-control"
                                           value="{{app()->getLocale() == 'ar' ?
                                                               $row->acc_name_ar :
                                                               $row->acc_name_en}} . {{ $row->acc_code }}">
                                @else
                                    <input type="text" readonly class="form-control" value="لا يوجد حساب للعميل">
                                @endif
                            </div>
                        </div>

                        {{--انواع الايرادات--}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">@lang('home.revenue_types')</label>
                                <select class="form-control" data-live-search="true"
                                        name="bond_doc_type" required>
                                    <option value="">@lang('home.choose')</option>
                                    @foreach(App\Models\SystemCode::where('sys_category_id', 58)
            ->where('company_group_id', $row->company_group_id)->get() as $system_code)
                                        <option value="{{$system_code->system_code_id}}">
                                            {{ app()->getLocale()=='ar' ?
                                        $system_code->system_code_name_ar :  $system_code->system_code_name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{--طرق الدفع--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.payment_method')</label>
                                <select class="form-control"
                                        onchange="validInputs($(this))" name="bond_method_type"
                                        required>
                                    <ooption value="">@lang('home.choose')</ooption>
                                    @foreach( App\Models\SystemCode::where('sys_category_id', 57)
            ->where('company_group_id', $row->company_group_id)->get() as $payment_method)
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
                                <input type="text" class="form-control" name="process_number" disabled="">
                            </div>
                        </div>

                        {{--البنك--}}
                        <div class="col-sm-6 col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('home.bank')</label>
                                <select class="form-control" name="bond_bank_id" disabled="">
                                    <option value="">@lang('home.choose')</option>
                                    @foreach(App\Models\SystemCode::where('sys_category_id', 40)
            ->where('company_group_id', $row->company_group_id)->get() as $bank)
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
                                <input type="text" class="form-control"
                                       id="value" value="{{$row->mntns_cards_due_amount }}"
                                       name="bond_amount_credit" required
                                       onkeyup="validPaid($(this))">
                                <small class="text-danger" id="error_message">
                                </small>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <label class="form-label">@lang('home.notes')</label>
                            <textarea class="form-control" name="bond_notes"
                                      placeholder="@lang('home.notes')"></textarea>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm"
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


<script>
    var d = new Date();

    var month = d.getMonth() + 1;
    var day = d.getDate();

    var output = (day < 10 ? '0' : '') + day + '/' +
        (month < 10 ? '0' : '') + month + '/' + d.getFullYear()
    ;
    $('.date').val(output)

    function validInputs(el) {
        console.log(el.val())
        //فيزا
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

        if (el.val() == 57001) {
            el.parent().parent().next().next().children().children().attr('disabled', 'disabled')
            el.parent().parent().next().next().children().children().val('')
            el.parent().parent().next().children().children().attr('disabled', 'disabled')
            el.parent().parent().next().children().children().val('')
        }
    }

    function validPaid(el) {
        var deserved_value = el.parent().parent().prev().prev().prev().prev().prev().prev().prev()
            .prev().children().children().eq(1).val()

        //     console.log(deserved_value)
        if (parseFloat(el.val()) > parseFloat(deserved_value)) {
            el.parent().parent().next().next().children().attr('disabled', 'disabled')
        } else {
            el.parent().parent().next().next().children().removeAttr('disabled')
        }
    }

    // function getVatAmount(el) {
    //     console.log(el.parent().parent().prev().children().children(2).val())
    //     el.parent().parent().next().children().children().val(parseFloat(el.parent().parent().prev().children().children().val()) *
    //         parseFloat(el.val()))
    // }

</script>