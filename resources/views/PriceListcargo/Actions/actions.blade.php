@if(auth()->user()->user_type_id != 1)
    @foreach(session('job')->permissions as $job_permission)
        @if($job_permission->app_menu_id == 51 && $job_permission->permission_add)
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-icon" href="{{ route('PriceList-cargo.edit',$row->price_list_id ) }}"
                    title="">
                    <i class="fa fa-eye"></i>
                </a>
            
            </div>
                <div class="col-md-3">
                <a href="{{config('app.telerik_server')}}?rpt={{$price_list_report}}&id={{$row->price_list_id }}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fa fa-print"></i></a>
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
            <a class="btn btn-icon" href="{{ route('PriceList-cargo.edit',$row->price_list_id ) }}"
            title="">
                <i class="fa fa-eye"></i>
            </a>
        </div>
      
                <div class="col-md-3">
            <a href="{{config('app.telerik_server')}}?rpt={{$price_list_report}}&id={{$row->price_list_id }}&lang=ar&skinName=bootstrap"
                                                       class="btn btn-primary btn-sm" target="_blank">
                                                        <i class="fa fa-print"></i></a>
        </div>
        <!-- <div class="col-md-6">
            <button class="btn btn-icon" onclick="deletItem('{{$row->uuid}}')" title="Delete Item">
                <i class="fa fa-trash"></i>
            </button>
        </div> -->
    </div>
@endif
