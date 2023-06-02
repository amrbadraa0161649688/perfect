<div id="left-sidebar" class="sidebar ">

    <h6 class="brand-name">
        <a href=" {{ route('home') }} ">@if(app()->getLocale()=='ar'){{ session('company')['company_name_ar'] }}
            @else{{ session('company')['company_name_en'] }} @endif </a>
        <a href="javascript:void(0)" class="menu_option float-right">
            <i class="icon-grid font-20" data-toggle="tooltip" data-placement="left" title="Grid & List Toggle"></i>
        </a>
    </h6>
    <nav id="left-sidebar-nav" class="sidebar-nav">
        <ul class="metismenu">
            {{--<li class="g_heading">@lang('home.home')</li>--}}
            <li class="">
                <a href="{{ route('home') }}" class="has-arrow arrow-c">
                    <i class="fa fa-home"></i>
                    <span>@lang('home.home')</span>
                </a>
            </li>

            {{-- active class to active link --}}

            @if(auth()->user()->user_type_id == 1)
                @php $applications=\App\Models\Application::where('app_status',1)->get() @endphp
                @foreach($applications as $application)
                    <li class="">
                        <a href="javascript:void(0)" class="has-arrow arrow-c">
                            <i class="{{$application->app_icon}}" style="font-size:20px;font: bold"></i>
                            <span>
                            @if(app()->getLocale() == 'ar')
                                    {{ $application->app_name_ar }}
                                @else
                                    {{ $application->app_name_en }}
                                @endif
                        </span>
                        </a>
                        <ul>
                            @foreach($application->applicationMenuActive as $application_menu)
                                <li class="" style="font-size:20px;font: bold"><a @if($application_menu->app_menu_url == 'mainCompanies'
                            || $application_menu->app_menu_url == 'administrativeStructures'
                            || $application_menu->app_menu_url == 'users'
                            || $application_menu->app_menu_url == 'customers'
                            || $application_menu->app_menu_url == 'PriceList'
                            || $application_menu->app_menu_url == 'PriceList-int'
                            || $application_menu->app_menu_url == 'PriceList-cargo'
                            || $application_menu->app_menu_url == 'suppliers'
                            || $application_menu->app_menu_url == 'Trucks'
                            || $application_menu->app_menu_url == 'Trips'
                            || $application_menu->app_menu_url == 'TripLine'
                            || $application_menu->app_menu_url == 'Bonds-capture'
                            || $application_menu->app_menu_url == 'Bonds-cash'
                            || $application_menu->app_menu_url == 'bonds-cash-detailed'
                            || $application_menu->app_menu_url == 'Waybills'
                            || $application_menu->app_menu_url == 'WaybillCargo'
                            || $application_menu->app_menu_url == 'WaybillsCargo2'
                            || $application_menu->app_menu_url == 'WaybillCar'
                            || $application_menu->app_menu_url == 'invoices'
                            || $application_menu->app_menu_url == 'invoices-acc'
                            || $application_menu->app_menu_url == 'invoices-rent'
                            || $application_menu->app_menu_url == 'invoices-credit'
                            || $application_menu->app_menu_url == 'invoices-debit'
                            || $application_menu->app_menu_url == 'invoicesCargo2'
                            || $application_menu->app_menu_url == 'invoicesCars'
                            || $application_menu->app_menu_url == 'Returned-invoices'
                            || $application_menu->app_menu_url == 'invoices.sales.index'
                            || $application_menu->app_menu_url == 'jobPermissions'
                            || $application_menu->app_menu_url == 'systemCodeCategories'
                            || $application_menu->app_menu_url == 'systemCodelocations'
                            || $application_menu->app_menu_url == 'Products'
                            || $application_menu->app_menu_url == 'employees'
                            || $application_menu->app_menu_url == 'employees-variables-setting.add_ons'
                            || $application_menu->app_menu_url == 'employee-reports'
                            || $application_menu->app_menu_url == 'monthly-additions'
                            || $application_menu->app_menu_url == 'monthly-deductions'
                            || $application_menu->app_menu_url == 'monthly-salaries'
                            || $application_menu->app_menu_url == 'maintenance-type.index'
                            || $application_menu->app_menu_url == 'maintenance-car.index'
                            || $application_menu->app_menu_url == 'maintenance-card.index'
                            || $application_menu->app_menu_url == 'store-item.index'
                            || $application_menu->app_menu_url == 'store-purchase-request.index'
                            || $application_menu->app_menu_url == 'store-purchase-order.index'
                            || $application_menu->app_menu_url == 'store-purchase-receiving.index'
                            || $application_menu->app_menu_url == 'store-purchase-return.index'
                            || $application_menu->app_menu_url == 'store-sales-quote.index'
                            || $application_menu->app_menu_url == 'store-sales-inv.index'
                            || $application_menu->app_menu_url == 'store-sales-return.index'
                            || $application_menu->app_menu_url == 'store-transfer-trans.index'
                            || $application_menu->app_menu_url == 'store-stocking.index'
                            || $application_menu->app_menu_url == 'store-client.index'
                            || $application_menu->app_menu_url == 'sales-car.index'
                            || $application_menu->app_menu_url =='sales-car-request.index'
                            || $application_menu->app_menu_url =='sales-car-order.index'
                            || $application_menu->app_menu_url == 'sales-car-receiving.index'
                            || $application_menu->app_menu_url == 'sales-car-quote.index'
                            || $application_menu->app_menu_url == 'sales-car-inv.index'
                            || $application_menu->app_menu_url == 'sales-car-return.index'
                            || $application_menu->app_menu_url == 'sales-car-transfer-trans.index'
                            || $application_menu->app_menu_url == 'employee-requests'
                            || $application_menu->app_menu_url == 'accountTree'
                            || $application_menu->app_menu_url == 'accounts.indexMain'
                            || $application_menu->app_menu_url == 'accounts.periods'
                            || $application_menu->app_menu_url == 'acc-reports'
                            || $application_menu->app_menu_url == 'bond-reports'
                            || $application_menu->app_menu_url == 'store-reports'
                            || $application_menu->app_menu_url == 'sales-car-reports'
                            || $application_menu->app_menu_url == 'mntns-reports'
                            || $application_menu->app_menu_url == 'rent-reports'
                            || $application_menu->app_menu_url == 'waybill-reports'
                            || $application_menu->app_menu_url == 'car-rent.customers.index'
                            || $application_menu->app_menu_url == 'brands.index'
                            || $application_menu->app_menu_url == 'movements.index'
                            || $application_menu->app_menu_url == 'CarRentModel'
                            || $application_menu->app_menu_url == 'CarRentCars'
                            || $application_menu->app_menu_url == 'CarRentPriceList'
                            || $application_menu->app_menu_url == 'journal-entries'
                            || $application_menu->app_menu_url == 'CarRentContract'
                            || $application_menu->app_menu_url == 'invoices-purchase'
                            || $application_menu->app_menu_url == 'car-rent.index'
                            || $application_menu->app_menu_url == 'journal-entries'
                            || $application_menu->app_menu_url == 'employees.dashboard'
                            || $application_menu->app_menu_url == 'car-rent.invoices'
                            || $application_menu->app_menu_url == 'notifications-index'
                            || $application_menu->app_menu_url == 'car-accident.index'
                            || $application_menu->app_menu_url == 'truck-accident.index'
                            || $application_menu->app_menu_url == 'bonds-capture-detailed'
                            || $application_menu->app_menu_url == 'financial-sub-types'
                            || $application_menu->app_menu_url == 'bonds.cash.safe'
                            || $application_menu->app_menu_url == 'fuel-station.index'
                            || $application_menu->app_menu_url == 'fuel-station.price.history'
                            || $application_menu->app_menu_url == 'bonds.capture.safe'
                            || $application_menu->app_menu_url == 'maintenanceCard'
                            || $application_menu->app_menu_url == 'PriceListFuel'
                            || $application_menu->app_menu_url == 'internal-inspection'
                            || $application_menu->app_menu_url == 'maintenanceCardCheck'
                            || $application_menu->app_menu_url == 'maintenanceCardTanks'
                             || $application_menu->app_menu_url == 'maintenanceCardFireSystem'
                             || $application_menu->app_menu_url == 'maintenanceCardServices'
                              || $application_menu->app_menu_url == 'quality-evaluation'
                              || $application_menu->app_menu_url == 'Trailers'
                              || $application_menu->app_menu_url == 'TrucksTrailers'

                            )
                                                                                  {{--                            || $application_menu->app_menu_url == 'CarRentContract')--}}
                                                                                  href="{{ route($application_menu->app_menu_url) }}" @endif>
                                        {{--<i :class="application_menu.app_menu_icon"></i>--}}
                                        <span>
                                        @if(app()->getLocale() == 'ar')
                                                {{ $application_menu->app_menu_name_ar }}
                                            @else
                                                {{ $application_menu->app_menu_name_en }}
                                            @endif
                                    </span></a>
                                </li>

                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @else

                @foreach(auth()->user()->company->appsActive as $application)
                    <li class="">
                        <a href="javascript:void(0)" class="has-arrow arrow-c">
                            <i class="{{$application->app_icon}}" style="font-size:20px;font-weight: bold"></i>
                            <span>
                            @if(app()->getLocale() == 'ar')
                                    {{ $application->app_name_ar }}
                                @else
                                    {{ $application->app_name_en }}
                                @endif
                        </span>
                        </a>
                        <ul>
                            @foreach($application->applicationMenuActive as $application_menu)

                                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == $application_menu->app_menu_id && $job_permission->permission_view)

                                        <li class="" style="font-size:25px;font: bold"><a @if($application_menu->app_menu_url == 'mainCompanies'
                            || $application_menu->app_menu_url == 'administrativeStructures'
                            || $application_menu->app_menu_url == 'users'
                            || $application_menu->app_menu_url == 'customers'
                            || $application_menu->app_menu_url == 'PriceList'
                            || $application_menu->app_menu_url == 'PriceList-int'
                            || $application_menu->app_menu_url == 'PriceList-cargo'
                            || $application_menu->app_menu_url == 'suppliers'
                            || $application_menu->app_menu_url == 'Trucks'
                            || $application_menu->app_menu_url == 'Trips'
                            || $application_menu->app_menu_url == 'TripLine'
                            || $application_menu->app_menu_url == 'Bonds-capture'
                            || $application_menu->app_menu_url == 'Bonds-cash'
                            || $application_menu->app_menu_url == 'bonds-cash-detailed'
                            || $application_menu->app_menu_url == 'Waybills'
                            || $application_menu->app_menu_url == 'WaybillCargo'
                            || $application_menu->app_menu_url == 'WaybillsCargo2'
                            || $application_menu->app_menu_url == 'WaybillCar'
                            || $application_menu->app_menu_url == 'invoices'
                            || $application_menu->app_menu_url == 'invoices-acc'
                            || $application_menu->app_menu_url == 'invoices-rent'
                            || $application_menu->app_menu_url == 'invoices-credit'
                            || $application_menu->app_menu_url == 'invoices-debit'
                            || $application_menu->app_menu_url == 'invoicesCargo2'
                            || $application_menu->app_menu_url == 'invoicesCars'
                            || $application_menu->app_menu_url == 'Invoices.create2'
                            || $application_menu->app_menu_url == 'Returned-invoices'
                            || $application_menu->app_menu_url == 'jobPermissions'
                            || $application_menu->app_menu_url == 'systemCodeCategories'
                            || $application_menu->app_menu_url == 'systemCodelocations'
                            || $application_menu->app_menu_url == 'Products'
                            || $application_menu->app_menu_url == 'employees'
                            || $application_menu->app_menu_url == 'employees-variables-setting.add_ons'
                            || $application_menu->app_menu_url == 'employee-reports'
                            || $application_menu->app_menu_url == 'monthly-additions'
                            || $application_menu->app_menu_url == 'monthly-deductions'
                            || $application_menu->app_menu_url == 'monthly-salaries'
                            || $application_menu->app_menu_url == 'maintenance-type.index'
                            || $application_menu->app_menu_url == 'maintenance-car.index'
                            || $application_menu->app_menu_url == 'store-item.index'
                            || $application_menu->app_menu_url == 'store-purchase-request.index'
                            || $application_menu->app_menu_url == 'store-purchase-order.index'
                            || $application_menu->app_menu_url == 'store-purchase-receiving.index'
                            || $application_menu->app_menu_url == 'store-purchase-return.index'
                            || $application_menu->app_menu_url == 'store-sales-quote.index'
                            || $application_menu->app_menu_url == 'store-sales-inv.index'
                            || $application_menu->app_menu_url == 'store-sales-return.index'
                            || $application_menu->app_menu_url == 'store-transfer-trans.index'
                            || $application_menu->app_menu_url == 'store-stocking.index'
                            || $application_menu->app_menu_url == 'store-client.index'
                            || $application_menu->app_menu_url == 'sales-car.index'
                            || $application_menu->app_menu_url =='sales-car-request.index'
                            || $application_menu->app_menu_url =='sales-car-order.index'
                            || $application_menu->app_menu_url == 'sales-car-receiving.index'
                            || $application_menu->app_menu_url == 'sales-car-quote.index'
                            || $application_menu->app_menu_url == 'sales-car-inv.index'
                            || $application_menu->app_menu_url == 'sales-car-return.index'
                            || $application_menu->app_menu_url == 'sales-car-transfer-trans.index'
                            || $application_menu->app_menu_url == 'employee-requests'
                            || $application_menu->app_menu_url == 'maintenance-card.index'
                            
                            || $application_menu->app_menu_url == 'accounts.indexMain'
                            || $application_menu->app_menu_url == 'accountTree'
                            || $application_menu->app_menu_url == 'accounts.periods'
                            || $application_menu->app_menu_url == 'acc-reports'
                            || $application_menu->app_menu_url == 'bond-reports'
                            || $application_menu->app_menu_url == 'store-reports'
                            || $application_menu->app_menu_url == 'sales-car-reports'
                            || $application_menu->app_menu_url == 'mntns-reports'
                            || $application_menu->app_menu_url == 'rent-reports'
                            || $application_menu->app_menu_url == 'waybill-reports'
                            || $application_menu->app_menu_url == 'car-rent.customers.index'
                            || $application_menu->app_menu_url == 'brands.index'
                            || $application_menu->app_menu_url == 'movements.index'
                            || $application_menu->app_menu_url == 'CarRentModel'
                            || $application_menu->app_menu_url == 'CarRentCars'
                            || $application_menu->app_menu_url == 'CarRentPriceList'
                            || $application_menu->app_menu_url == 'journal-entries'
                            || $application_menu->app_menu_url == 'CarRentContract'
                             || $application_menu->app_menu_url == 'invoices-purchase'

                            || $application_menu->app_menu_url == 'car-rent.index'
                         || $application_menu->app_menu_url == 'journal-entries'
                         || $application_menu->app_menu_url == 'employees.dashboard'
                          || $application_menu->app_menu_url == 'car-rent.invoices'
                          || $application_menu->app_menu_url == 'notifications-index'
                          || $application_menu->app_menu_url == 'car-accident.index'
                          || $application_menu->app_menu_url == 'truck-accident.index'
                          || $application_menu->app_menu_url == 'bonds-capture-detailed'
                          || $application_menu->app_menu_url == 'financial-sub-types'
                              || $application_menu->app_menu_url == 'bonds.cash.safe'
                              || $application_menu->app_menu_url == 'fuel-station.index'
                              || $application_menu->app_menu_url == 'fuel-station.price.history'
                            || $application_menu->app_menu_url == 'bonds.capture.safe'
                             || $application_menu->app_menu_url == 'maintenanceCard'
                             || $application_menu->app_menu_url == 'PriceListFuel'
                             || $application_menu->app_menu_url == 'internal-inspection'
                             || $application_menu->app_menu_url == 'maintenanceCardCheck'
                              || $application_menu->app_menu_url == 'maintenanceCardTanks'
                              || $application_menu->app_menu_url == 'maintenanceCardFireSystem'
                                || $application_menu->app_menu_url == 'maintenanceCardServices'
                                || $application_menu->app_menu_url == 'quality-evaluation'
                                 || $application_menu->app_menu_url == 'Trailers'
                                 || $application_menu->app_menu_url == 'TrucksTrailers'
                         )
                                                                                          {{--                            || $application_menu->app_menu_url == 'CarRentContract'--}}

                                                                                          href="{{ route($application_menu->app_menu_url) }}" @endif>
                                                {{--<i :class="application_menu.app_menu_icon"></i>--}}
                                                <span>
                                                        @if(app()->getLocale() == 'ar')
                                                        {{ $application_menu->app_menu_name_ar }}
                                                    @else
                                                        {{ $application_menu->app_menu_name_en }}
                                                    @endif
                                    </span></a>
                                        </li>

                                    @endif
                                @endforeach

                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endif

            <li class="">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="has-arrow arrow-c" style="background : #113f50 ; color : white">
                        <i class=" fe fe-log-out"></i>@lang('home.sign_out')</button>
                </form>


            </li>


        </ul>
    </nav>
</div>

