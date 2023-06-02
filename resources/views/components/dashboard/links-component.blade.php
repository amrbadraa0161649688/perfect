<div>
    <!-- Simplicity is the consequence of refined emotions. - Jean D'Alembert -->

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('employees') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-address-card"></i>
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
                            <a  href="{{ route('invoices-acc') }}" class="my_sort_cut text-muted">
                                <i class="fa fa-files-o"></i>
                                <span>@lang('home.invoices')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div  class="card-body ribbon">
                            <a href="{{ route('users') }}" class="my_sort_cut text-muted">
                                <i class="icon-users"></i>
                                <span>@lang('home.users')</span>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{ route('main.dashboard') }}"  class="my_sort_cut text-muted">
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