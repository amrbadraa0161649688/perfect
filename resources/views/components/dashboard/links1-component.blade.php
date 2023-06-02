<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

            <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('customers') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-users"></i>
                                <span>@lang('home.customers')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('Trucks') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-truck"></i>
                                <span>@lang('home.trucks')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('WaybillCar') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-file-text"></i>
                                <span>@lang('home.waybills')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('Trips') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-chain"></i>
                                <span>@lang('home.trips')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('Bonds-capture')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-money"></i>
                                <span>@lang('invoice.bond_payment')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('main1.dashboard') }}"  class="my_sort_cut text-muted">
                                <i class="fa fa-bar-chart"></i>
                                <span>@lang('home.home')</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>