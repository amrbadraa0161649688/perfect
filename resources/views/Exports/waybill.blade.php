<table class="table table-hover table-striped table-vcenter  yajra-datatable">
    <thead style="background-color: #ece5e7">
    <tr class="red">
        <th>@lang('waybill.waybill_no')</th>
        <th>@lang('waybill.company_name')</th>
        <th>@lang('waybill.waybill_ticket_no')</th>
        <th>@lang('waybill.customer_name')</th>
        <th>@lang('waybill.waybill_type')</th>
        <th>@lang('waybill.waybill_date')</th>
        <th>@lang('waybill.waybill_load_date')</th>
        <th>@lang('waybill.waybill_expect')</th>
        <th>@lang('waybill.waybill_qut_receved')</th>
        <th>@lang('waybill.waybill_price')</th>
        <th>@lang('waybill.waybill_amount')</th>
        <th>@lang('waybill.waybill_vat_amount')</th>
        <th>@lang('waybill.total')</th>
        <th>@lang('waybill.waybill_status')</th>
        <th>@lang('waybill.loc_car_from')</th>
        <th>@lang('waybill.loc_car_to')</th>
        <th>@lang('waybill.car_chase')</th>

        <th>@lang('waybill.car_plate')</th>
        <th>@lang('waybill.waybill_user')</th>
        <th>@lang('waybill.waybill_trip_no')</th>
        <th>@lang('waybill.waybill_truck')</th>
        <th>@lang('waybill.waybill_driver')</th>


        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($way_pills as $way_pill)
        <tr>
            <td>{{ $way_pill->waybill_code }}</td>
            <td>{{app()->getLocale()=='ar' ? $way_pill->company->company_name_ar
                                : $way_pill->company->company_name_en}}</td>

            <td>{{ $way_pill->waybill_ticket_no }}</td>
            <td>
                @if($way_pill->customer)
                    {{app()->getLocale()=='ar' ? $way_pill->customer->customer_name_full_ar
                : $way_pill->customer->customer_name_full_en }}
                @endif
            </td>
            <td>{{ $way_pill->details->item ?  $way_pill->details->item->system_code_name_ar : ' '}}</td>
            <td>{{ $way_pill->created_date }}</td>
            <td>{{ $way_pill->waybill_load_date }}</td>
            <td>{{ $way_pill->waybill_delivery_expected }}</td>
            <td>{{ number_format($way_pill->details->waybill_item_quantity,3) }}</td>
            <td>{{ number_format($way_pill->details->waybill_item_price,3) }}</td>
            <td>{{ $way_pill->waybill_total_amount -  $way_pill->waybill_vat_amount }}</td>
            <td>{{ $way_pill->waybill_vat_amount }}</td>
            <td>{{ $way_pill->waybill_total_amount }}</td>
            <td>
                @if($way_pill->status)
                    {{app()->getLocale()=='ar' ?  $way_pill->status->system_code_name_ar :
                 $way_pill->status->system_code_name_en }}
                @endif
            </td>

            <td>
                @if($way_pill->locfrom)
                    {{app()->getLocale()=='ar' ?  $way_pill->locfrom->system_code_name_ar :
                 $way_pill->locfrom->system_code_name_en }}
                @endif
            </td>

            <td>
                @if($way_pill->locTo)
                    {{app()->getLocale()=='ar' ?  $way_pill->locTo->system_code_name_ar :
                 $way_pill->locTo->system_code_name_en }}
                @endif
            </td>
            <td>{{ $way_pill->details->waybill_car_chase }}</td>
            <td>{{ $way_pill->details->waybill_car_plate }}</td>

            <td>{{ $way_pill->userCreated->user_name_ar }}</td>

            <td>{{ ($way_pill->trip ? $way_pill->trip->trip_hd_code : ' ')  }}</td>
            <td>{{ ($way_pill->truck ? $way_pill->truck->truck_code : ' ')  }}</td>
            <td>{{ ($way_pill->driver ? $way_pill->driver->emp_name_full_ar : ' ')  }}</td>

            <td>{{ ($way_pill->trip ? $way_pill->trip->truck->truck_code : ' ')  }}</td>
            <td>{{ ($way_pill->trip && $way_pill->trip->driver ? $way_pill->trip->driver->emp_name_full_ar : ' ')  }}</td>

            @if(auth()->user()->user_type_id != 1)
                @foreach(session('job')->permissions as $job_permission)
                    @if($job_permission->app_menu_id == 70 && $job_permission->permission_add)
                        <td>
                            <a href="{{ route('Waybill.edit',$way_pill->waybill_id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="fa fa-edit"></i></a>
                        </td>
                    @endif
                @endforeach
            @else
                <td>
                    <a href="{{ route('Waybill.edit',$way_pill->waybill_id) }}"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-edit"></i></a>
                </td>
            @endif
        </tr>
    @endforeach

    </tbody>

</table>
