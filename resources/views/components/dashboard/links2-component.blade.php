<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('employees') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-users"></i>
                                <span>@lang('home.human_resources')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="journal-entries" class="my_sort_cut text-muted">
                                <i class="fa fa-share-alt"></i>
                                <span>@lang('home.public_accounts')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('WaybillCar') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-truck"></i>
                                <span>@lang('home.transportation')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a  href="{{ route('maintenance-card.index') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-gears"></i>
                                <span>@lang('home.maintenance')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div  class="card-body ribbon">
                            <a href="{{ route('store-sales-inv.index') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-cubes"></i>
                                <span>@lang('home.warehouses')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('main2.dashboard') }}"  class="my_sort_cut text-muted">
                                <i class="fa fa-usd"></i>
                                <span>@lang('home.home')</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>