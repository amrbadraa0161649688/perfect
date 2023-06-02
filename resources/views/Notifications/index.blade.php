@extends('Layouts.master')

@section('content')

    <div class="section-gray py-4" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-body">

                        <div class="row">
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
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card bg-none">

                        <div class="card-body pt-0">
                            <div class="table-responsive table_e2">
                                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                                    <thead>
                                    <tr>
                                        <th>@lang('home.content')</th>
                                        <th>@lang('home.date')</th>
                                        <th>@lang('home.status')</th>
                                        <th>@lang('home.notification_type')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="notification in filteredNotificationsContent">
                                        <td>@{{ notification.notification_data }}</td>
                                        <td>@{{ notification.notification_date }}</td>
                                        <td>@{{ notification.notification_status }}</td>
                                        <td>@{{ notification.notification_type }}</td>
                                        <td></td>
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