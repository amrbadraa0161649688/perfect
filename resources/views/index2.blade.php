@extends('Layouts.master2')

@section('content')

{{--الشاشه الرئيسيه لمبيعات الجوال   --}}

    <div class="section-body mt-3">



        <div class="container-fluid">



            <div class="row">

                

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('Invoices.sales.create95')}}"  class="my_sort_cut text-muted">
                                
                                <img class="w40 mr-2" src="{{ asset('assets/images/95.png') }}" >
                                <span>@lang('invoice.inv_95')</span>
                            </a>
                        </div>
                    </div>
                </div>





                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a  href="{{route('Invoices.sales.create91')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/91.png') }}" >
                                <span>@lang('invoice.inv_91')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('Invoices.sales.createDs')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/diesel.png') }}" >
                                <span>@lang('invoice.inv_ds')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('invoices.sales.index')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/INVS.JPG') }}" >
                                <span>@lang('invoice.inv_all')</span>
                            </a>
                        </div>
                    </div>
                </div>

     
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 150 && $job_permission->permission_add)
                                    <div class="col-6 col-md-4 col-xl-2">
                                        <div class="card">
                                            <div class="card-body ribbon">
                                                <a href="{{route('maintenanceCard.createM')}}" class="my_sort_cut text-muted">
                                                <img class="w40 mr-2" src="{{ asset('assets/images/PO.JPG') }}" >
                                                    <span>@lang('invoice.req_order')</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                @endforeach
                
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 151 && $job_permission->permission_add)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('maintenanceCard.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/TRUCK_FULE.JPG') }}" >
                                <span>@lang('invoice.ins_store')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 153 && $job_permission->permission_add)

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('maintenanceCard.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/PUMP.PNG') }}" >
                                <span>@lang('invoice.mntn_pumps')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 154 && $job_permission->permission_add)

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('maintenanceCardTanks.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/TANCK.PNG') }}" >
                                <span>@lang('invoice.mntn_tank')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 155 && $job_permission->permission_add)

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('maintenanceCardFireSystem.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/fire.png') }}" >
                                <span>@lang('invoice.firefighting_system')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach
                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 156 && $job_permission->permission_add)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('maintenanceCardServices.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/SERVICES.png') }}" >
                                <span>@lang('invoice.mntn_services')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach

                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 157 && $job_permission->permission_add)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('internal-inspection.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/AUDIT.png') }}" >
                                <span>@lang('invoice.internal_inspection')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach

                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 158 && $job_permission->permission_add)
                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('quality-evaluation.createM')}}" class="my_sort_cut text-muted">
                            <img class="w40 mr-2" src="{{ asset('assets/images/QUALITY.png') }}" >
                                <span>@lang('invoice.Quality_Management')</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                                @endforeach


            </div>
        </div>
    </div>
    
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                notifications: [],
                isLoaded: false,
                notification_status: 0,
                notification_data: ''
            },
            mounted() {
                this.getNotifications()
            },
            methods: {
                getNotifications() {
                    this.notifications = {}
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("notifications-index") }}'
                    }).then(response => {
                        this.notifications = response.data
                        this.isLoaded = true
                    })

                },
                markNotification(index, id) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ url('/Api/notifications/seen?id=') }}' + id,
                    }).then(response => {
                        window.location.reload()
                    })
                }
            },
            computed: {
                filteredNotificationsStatus: function () {
                    if (this.isLoaded) {
                        return this.notifications.filter(notification => {
                            return notification.notification_status_f.match(this.notification_status)
                        })
                    }
                },
                filteredNotificationsContent: function () {
                    if (this.isLoaded) {
                        return this.filteredNotificationsStatus.filter(notification => {
                            return notification.notification_data.match(this.notification_data)
                        })
                    }
                },
            }
        })

    </script>
@endsection
