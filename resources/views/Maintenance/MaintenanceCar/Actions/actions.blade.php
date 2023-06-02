@if(auth()->user()->user_type_id != 1)
    @foreach(session('job')->permissions as $job_permission)
        @if($job_permission->app_menu_id == 59 && $job_permission->permission_add)
            <a class="btn btn-icon" href="{{ route('maintenance-car.edit', $row->uuid ) }}"
               title="">
                <i class="fa fa-eye"></i>
            </a>
        @endif
    @endforeach
@endif

@if(auth()->user()->user_type_id == 1)
    <a class="btn btn-icon" href="{{ route('maintenance-car.edit', $row->uuid ) }}"
       title="">
        <i class="fa fa-eye"></i>
    </a>
@endif
