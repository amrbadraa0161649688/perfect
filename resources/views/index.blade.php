@extends('Layouts.master1')
@section('content')

@foreach($employees as $employee)

    @if($employee->user_type_id == 42000)
    <x-dashboard.links-component>

    </x-dashboard.links-component>
    
    @endif

    @if($employee->user_type_id == 42001)
    <x-dashboard.links1-component>

    </x-dashboard.links1-component>
    @endif

    @if($employee->user_type_id == 42002)
    <x-dashboard.links2-component>

    </x-dashboard.links2-component>
    @endif

    @if($employee->user_type_id == 42003)
    <x-dashboard.links3-component>

    </x-dashboard.links3-component>
    @endif

    @if($employee->user_type_id == 42004)
    <x-dashboard.links4-component>

    </x-dashboard.links4-component>
    @endif

    @if($employee->user_type_id == 42005)
    <x-dashboard.links5-component>

    </x-dashboard.links5-component>
    @endif

    @if($employee->user_type_id == 42006)
    <x-dashboard.links6-component>

    </x-dashboard.links6-component>
    @endif


@if($employee->user_type_id == 42007)
    <x-dashboard.links7-component>

    </x-dashboard.links7-component>
   
@endif

@if($employee->user_type_id == 1)
    <x-dashboard.links7-component>

    </x-dashboard.links7-component>
   
@endif

@endforeach

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
                                        <th>
                                            <input type="checkbox" style="margin: 3px"
                                                   @click="markAllAsRead()" id="select_all">
                                            <label>@lang('home.select_all')</label>


                                            <a href="javascript:void(0);"
                                               class="btn btn-info btn-sm">@lang('home.content')</a></th>
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

                                                <input type="checkbox" class="custom-control-input select-check"
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
    <script>
        $(document).ready(function () {
            $('#select_all').click(function () {
                $('#select_all').attr('disabled', 'disabled')
                $('.select-check').attr('checked', 'checked')
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                notifications: [],
                isLoaded: false,
                notification_status: 0,
                notification_data: '',
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
                },
                markAllAsRead() {
                    // notifications-all-seen
                    if (this.notification_status == 0 && this.filteredNotificationsContent.length > 0) {
                        $.ajax({
                            type: 'GET',
                            url: '{{ route('notifications-all-seen') }}',
                        }).then(response => {
                            window.location.reload()
                        })
                    }

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


