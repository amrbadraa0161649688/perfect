


                             @if(auth()->user()->user_type_id != 1)
                                @foreach(session('job')->permissions as $job_permission)
                                    @if($job_permission->app_menu_id == 105 && $job_permission->permission_update)
                                    <a class="btn btn-primary btn-sm" href="{{route('TripLine.edit',$row->trip_line_hd_id)}}"
                                        title="@lang('home.edit')">
                                            @lang('home.edit')
                                        </a>


                                    @endif
                                @endforeach
                            @else
                            <a class="btn btn-primary btn-sm" href="{{route('TripLine.edit',$row->trip_line_hd_id)}}"
                                title="@lang('home.edit')">
                                    @lang('home.edit')
                                </a>


                            @endif


                          