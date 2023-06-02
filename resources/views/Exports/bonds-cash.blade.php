<div class="table-responsive table_e2">
    <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
        <thead>
        <tr>
            <th>@lang('home.bonds_number')</th>
            <th>@lang('home.bonds_date')</th>
            <th>@lang('home.sub_company')</th>
            <th>@lang('home.branch')</th>
            <th>@lang('home.bonds_activity')</th>
            <th>@lang('home.bonds_account')</th>
            <th>@lang('home.payment_method')</th>
            <th>@lang('home.value')</th>
            <th>@lang('home.user')</th>

        </tr>
        </thead>
        <tbody>
        @foreach($bonds as $bond)
            <tr>
                <td>{{ $bond->bond_code }}</td>
                <td>{{ $bond->bond_date }}</td>
                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->company->company_name_ar :
                                            $bond->company->company_name_en }}</td>

                <td>{{ app()->getLocale() == 'ar' ?
                                            $bond->branch->branch_name_ar :
                                            $bond->branch->branch_name_en }}</td>
                <td>
                    {{app()->getLocale()=='ar' ? $bond->transactionType->app_menu_name_ar :
                    $bond->transactionType->app_menu_name_en }}
                </td>
                <td>{{ $bond->bond_acc_id }}</td>
                <td>{{ app()->getLocale() == 'ar' ? $bond->paymentMethod->system_code_name_ar :
                                              $bond->paymentMethod->system_code_name_en }}</td>
                <td>{{ $bond->bond_amount_credit }}</td>
                <td>{{ app()->getLocale()=='ar' ? $bond->userCreated->user_name_ar :
                                            $bond->userCreated->user_name_en }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
