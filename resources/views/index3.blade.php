@extends('Layouts.master')

@section('content')

{{--الشاشه الرئيسيه لمبيعات المستودعات   --}}

    <div class="section-body mt-3">



        <div class="container-fluid">



            <div class="row">

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
                                <i class="fa fa-users"></i>
                                <span>@lang('invoice.inv')</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-2">
                    <div class="card">
                        <div class="card-body ribbon">
                            <a href="{{route('store-sales-return.index')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-user-circle"></i>
                                <span>@lang('invoice.inv_return')</span>
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
                            <a href="{{route('Bonds-cash')}}" class="my_sort_cut text-muted">
                                <i class="fa fa-dollar"></i>
                                <span>@lang('invoice.bond_issue')</span>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-12">
                    <div class="card">
                        <div class="card-title">
                            <div class="row m-3">
                                <div class="col-md-6">
                                    <label>@lang('home.status')</label>
                                    <select name="notification_status" v-model="notification_status"
                                            class="form-control">
                                        <option value="1">@lang('home.read')</option>
                                        <option selected value="0">@lang('home.not_read')</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>@lang('home.content')</label>
                                    <input type="text" name="notification_content" class="form-control"
                                           placeholder="بحث........." v-model="notification_data">
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive todo_list">
                                <table class="table table-hover table-striped table-vcenter mb-0">
                                    <thead>
                                    <tr>
                                        <th><a href="javascript:void(0);"
                                               class="btn btn-success btn-sm">@lang('home.content')</a></th>
                                        <th class="w150 text-right">@lang('home.date')</th>
                                        <th class="w100">@lang('home.status')</th>
                                        <th class="w80"><i class="icon-user"></i></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="notification,index in filteredNotificationsContent">
                                        <td>
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       name="example-checkbox1" value="option1" checked
                                                       v-if="notification.notification_status_f == 1" disabled="">

                                                <input type="checkbox" class="custom-control-input"
                                                       name="example-checkbox1" value="option2"
                                                       v-else
                                                       @click="markNotification(index,notification.notification_id)">

                                                <span class="custom-control-label">@{{ notification.notification_data }}</span>
                                            </label>
                                        </td>

                                        <td class="text-right">@{{ notification.notification_date }}</td>
                                        <td><span class="tag tag-success m2-0 mr-0">@{{ notification.notification_status }}</span>
                                        </td>
                                        <td>
                                                        <span class="avatar avatar-pink" data-toggle="tooltip"
                                                              data-placement="top" title=""
                                                              data-original-title="Avatar Name">N</span>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


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

