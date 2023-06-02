<div class="page-header">


    <div class="left">

        @if(session('company'))

            <div class="avatar avatar-blue" data-toggle="tooltip"
                 data-placement="top" style="position:absolute;"
                 title="" data-original-title="Avatar Name">
                <img class="avatar avatar-blue" src="{{session('company')['company_logo']}}">
            </div>
            @if(app()->getLocale()=='ar')
                <p  style="font-size:18px;font-weight: bold;position:absolute;right:100px;">
                @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}
                @else {{ session('branch')['branch_name_en']  }} @endif
                </p>
            @else
                <p  style="font-size:18px;font-weight: bold;position:absolute;left:100px;">
                @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}
                @else {{ session('branch')['branch_name_en']  }} @endif
                </p>
            @endif
        @else
            <div class="avatar avatar-blue" data-toggle="tooltip"
                 data-placement="top" style="position:absolute;left:60px;"
                 title="" data-original-title="Avatar Name">
                <img class="avatar avatar-blue" src="{{auth()->user()->company->company_logo}}">
            </div>
            <p style="font-size:15px;font-weight: bold;margin-right:10px;position:absolute;right:250px;margin:0;">
                @if(app()->getLocale()=='ar'){{ auth()->user()->company->company_name_ar }}
                @else{{ auth()->user()->company->company_name_en }} @endif
            </p>
        @endif

        {{--  @if(auth()->user()->user_type_id != 1)--}}
        {{--<p style="font-size:15px;font-weight: bold;position:absolute;left:500px">--}}
        {{-- @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}--}}
        {{--  @else {{ session('branch')['branch_name_en']  }} @endif--}}
        {{--  </p>--}}
        {{-- @else--}}

            {{--<p style="font-size:15px;font-weight: bold;position:absolute;left:250px">--}}
            {{--@if(app()->getLocale()=='ar'){{ session('company_group')['company_group_ar'] }}--}}
            {{--@else {{ session('company_group')['company_group_en']  }} @endif--}}
            {{--</p>--}}

            {{-- <p style="font-size:15px;font-weight: bold;position:absolute;left:500px">--}}
            {{--   @if(app()->getLocale()=='ar'){{ session('branch')['branch_name_ar'] }}--}}
            {{--   @else {{ session('branch')['branch_name_en']  }} @endif--}}
            {{-- </p>--}}
            {{-- @endif--}}

           
        <div style="margin-right: 200px">
            <h1>{{ LaravelGmail::user() }}</h1>
            @if(LaravelGmail::check())
                <a href="{{ url('oauth/gmail/logout') }}">logout</a>
            @else
                <a href="{{ url('oauth/gmail') }}" target="_blank"></a>
            @endif
        </div>


    </div>
    <div class="right">
        <ul class="nav nav-pills">

        @if(app()->getLocale()=='ar')
                <p  style="font-size:16px;font-weight: bold;position:absolute;left:200px;">
                @if(app()->getLocale()=='ar'){{ auth()->user()->user_name_ar }}
                @else{{ auth()->user()->user_name_en }} @endif
                </p>
            @else
                <p  style="font-size:16px;font-weight: bold;position:absolute;right:200px;">
                @if(app()->getLocale()=='ar'){{ auth()->user()->user_name_ar }}
                @else{{ auth()->user()->user_name_en }} @endif
                </p>
            @endif

            {{--   <p style="font-size:10px;font-weight: bold;">--}}
                {{--    @if(app()->getLocale()=='ar'){{ auth()->user()->user_name_ar }}--}}
                {{--     @else{{ auth()->user()->user_name_en }} @endif--}}
                {{--  </p>--}}

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">{{ app()->getLocale() }}</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('locale','en') }}"><img class="w20 mr-2"
                                                                                    src="{{ asset('assets/images/flags/us.svg') }}">English</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('locale','ar') }}"><img class="w20 mr-2"
                                                                                    src="{{ asset('assets/images/flags/sa.svg') }}">عربي</a>
                </div>
            </li>
        </ul>
        <div class="notification d-flex">
            <div class="dropdown d-flex">
                <a class="nav-link icon d-none d-md-flex btn btn-default btn-icon ml-1" data-toggle="dropdown"><i
                            class="fa fa-envelope"></i><span class="badge badge-success nav-unread"></span></a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <ul class="right_chat list-unstyled w250 p-0">

                    </ul>
                    <div class="dropdown-divider"></div>
                    <a href="javascript:void(0)" class="dropdown-item text-center text-muted-dark readall">Mark all as
                        read</a>
                </div>
            </div>
            {{--$notifications=\App\Models\Notification::where('notification_status',0)->latest()->get()->take(10);--}}


            <div class="dropdown d-flex">
                <a class="nav-link icon d-none d-md-flex btn btn-default btn-icon ml-1"
                   data-toggle="dropdown"><i
                            class="fa fa-bell"></i><span class="badge badge-primary nav-unread"></span></a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                    <ul class="list-unstyled feeds_widget">
                        @foreach(auth()->user()->notifications as $notification)

                            <li>
                                <div class="feeds-left"><i class="fa fa-bell"></i></div>
                                <div class="feeds-body">
                                    @if($notification->notification_type == 'attachment')
                                        <a href="{{ url('/Api/notifications/seen?id='.$notification->notification_id) }}">
                                            <h4 class="title text-danger">
                                                {{app()->getLocale() == 'ar' ? 'إنتهاء مدة مستندات' : 'Expiry of the document'}}
                                                <small class="float-right text-muted">{{$notification->created_date}}</small>
                                            </h4>
                                        </a>
                                    @elseif($notification->notification_type == 'request')
                                        <a href="{{ url('/Api/notifications/seen?id='.$notification->notification_id) }}">
                                            {{--<a href="{{ route('employee-requests-edit',$notification->notifiable_id) }}">--}}
                                            <h4 class="title text-danger">
                                                {{app()->getLocale() == 'ar' ? 'طلبات موظفين' : 'Employee Requests'}}
                                                <small class="float-right text-muted">{{$notification->created_date}}</small>
                                            </h4>
                                            {{--</a>--}}
                                        </a>
                                    @elseif($notification->notification_type == 'contract')
                                        <a href="{{ url('/Api/notifications/seen?id='.$notification->notification_id) }}">
                                            {{--<a href="{{ route('employee-requests-edit',$notification->notifiable_id) }}">--}}
                                            <h4 class="title text-danger">
                                                {{app()->getLocale() == 'ar' ? 'عقود موظفين' : 'Employee Contracts'}}
                                                <small class="float-right text-muted">{{$notification->created_date}}</small>
                                            </h4>
                                            {{--</a>--}}
                                        </a>

                                    @elseif($notification->notification_type == 'waybill-car')
                                        <a href="{{ url('/Api/notifications/seen?id='.$notification->notification_id) }}">
                                            {{--<a href="{{ route('employee-requests-edit',$notification->notifiable_id) }}">--}}
                                            <h4 class="title text-danger">
                                                {{app()->getLocale() == 'ar' ? 'بوليصه شحن سياره' : 'Waybill Car'}}
                                                <small class="float-right text-muted">{{$notification->created_date}}</small>
                                            </h4>
                                            {{--</a>--}}
                                        </a>
                                    @endif

                                    <small> {{$notification->notification_data}} </small>
                                </div>
                            </li>

                        @endforeach
                    </ul>
                    <div class="dropdown-divider"></div>

                    @if(auth()->user()->notifications->count() > 0)
                        <a href="{{route('notifications-all-seen')}}"
                           class="dropdown-item text-center text-muted-dark readall">Mark
                            all as read</a>
                    @else
                        <a href="javascript:void(0)"
                           class="dropdown-item text-center text-muted-dark readall">
                            لا يوجد اي اشعارات جديده
                        </a>
                    @endif

                </div>
            </div>

            <div class="dropdown d-flex">
                <a class="nav-link icon d-none d-md-flex btn btn-default btn-icon ml-1" data-toggle="dropdown"><i
                            class="fa fa-user"></i></a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="dropdown-item">
                            <i class="dropdown-icon fe fe-log-out"></i>@lang('home.sign_out')</button>
                    </form>
                </div>
            </div>
        </div>

    </div>


</div>

