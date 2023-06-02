@if(auth()->user()->user_type_id != 1)
    @foreach(session('job')->permissions as $job_permission)
        @if($job_permission->app_menu_id == 8 && $job_permission->permission_add)
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-icon" href="{{ route('store-stocking.show',$row->uuid) }}"
                    title="">
                    <i class="fa fa-eye"></i>
                </a>
            </div>
        </div>
        @endif
    @endforeach
@endif

@if(auth()->user()->user_type_id == 1)
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-icon" href="{{ route('store-stocking.show',$row->uuid) }}"
            title="">
                <i class="fa fa-eye"></i>
            </a>
        </div>
    </div>
@endif
