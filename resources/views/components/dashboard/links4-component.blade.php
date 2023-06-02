<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

            <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('store-client.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-vcard"></i>
                                <span>@lang('home.add_customer')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('store-item.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-th"></i>
                                <span>@lang('storeitem.items')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('store-purchase-receiving.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-tasks"></i>
                                <span>@lang('stocking.add_receive_perm')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('store-sales-inv.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-wpforms"></i>
                                <span>@lang('invoice.inv')</span>
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
                            <a href="{{ route('main4.dashboard') }}"  class="my_sort_cut text-muted">
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