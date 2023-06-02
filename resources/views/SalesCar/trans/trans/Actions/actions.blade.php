@if(auth()->user()->user_type_id != 1)
    @foreach(session('job')->permissions as $job_permission)
        @if($job_permission->app_menu_id == 82 && $job_permission->permission_add)
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-icon" href="{{ route('sales-car-transfer-trans.edit',$row->uuid ) }}"
                    title="">
                    <i class="fa fa-eye"></i>
                </a>
            </div>
            <!-- <div class="col-md-6">
                <button class="btn btn-icon" onclick="deletItem('{{$row->uuid}}')" title="Delete Item">
                    <i class="fa fa-trash"></i>
                </button>
            </div> -->
        </div>
        @endif
    @endforeach
@endif

@if(auth()->user()->user_type_id == 1)
    <div class="row">
        <div class="col-md-6">
            <a class="btn btn-icon" href="{{ route('sales-car-transfer-trans.edit',$row->uuid ) }}"
            title="">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <!-- <div class="col-md-6">
            <button class="btn btn-icon" onclick="deletItem('{{$row->uuid}}')" title="Delete Item">
                <i class="fa fa-trash"></i>
            </button>
        </div> -->
    </div>
@endif
