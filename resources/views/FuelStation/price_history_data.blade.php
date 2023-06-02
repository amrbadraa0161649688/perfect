<!-- PRICE WIDGET -->
<div class="row clearfix">
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #65A30D;">
        
            <div class="card-body ribbon text-light">
                @if(optional($benzine_91_data['price'])->status !='PENDING')
                    <h3> 
                        {{$benzine_91_data['fees']}} ريال
                    </h3>
                    <span>بنزين 91</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice('{{$benzine_91->system_code}}',{{$branch->station_id}})"></i>
                @else
                    <h3>
                        {{$benzine_91_data['fees']}} ريال 
                        <div class="ribbon-box orange" >  PENDING </div>
                    </h3>
                    <span>بنزين 91</span>
                    @if(auth()->user()->user_type_id != 1)
                        @foreach(session('job')->permissions as $job_permission)
                            @if($job_permission->app_menu_id == 136 && $job_permission->permission_update)
                                <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_91_data['id'] }}')"></i>
                                <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_91_data['id'] }}')"></i>
                            @endif
                        @endforeach
                    @else
                        <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_91_data['id'] }}')"></i>
                        <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_91_data['id'] }}')"></i>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #EF4444;">
            <div class="card-body ribbon text-light">
               
                @if(optional($benzine_95_data['price'])->status !='PENDING')
                    <h3>
                        {{$benzine_95_data['fees']}} ريال
                    </h3>
                    <span>بنزين 95</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice('{{$benzine_95->system_code}}',{{$branch->station_id}})"></i>
                @else
                    <h3>
                        {{$benzine_95_data['fees']}} ريال
                        <div class="ribbon-box orange" >  PENDING </div>
                       
                    </h3>
                    <span>بنزين 95</span>
                    <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $benzine_95_data['id'] }}')"></i>
                    <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $benzine_95_data['id'] }}')"></i>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <div class="card text-center" style="background: #eab308;">
            <div class="card-body ribbon text-light">
                @if(optional($diesel_data['price'])->status !='PENDING')
                    <h3> 
                        {{$diesel_data['fees']}} ريال
                        </h3>
                    <span>ديزل</span>
                    <i class="fa fa-rotate-left" data-toggle="tooltip" title="" data-original-title="" onclick="editPrice({{$diesel->system_code}},{{$branch->station_id}})"></i>
                @else
                    <h3> 
                        {{$diesel_data['fees']}} ريال
                        <div class="ribbon-box orange" >  PENDING </div>
                    </h3>
                    <span>ديزل</span>
                    <i class="fa fa-check-square" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('APPROVED','{{ $diesel_data['id'] }}')"></i>
                    <i class="fa fa-remove" data-toggle="tooltip" title="" data-original-title="" onclick=" confirmPrice('REJECTED','{{ $diesel_data['id'] }}')"></i>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row clearfix">
    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-status" style="background: #65A30D;"></div>
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body" style="height: 300px;overflow">
                <table class="table card-table">
                    <tbody>
                        @if(count($price_history['91']))
                            @foreach($price_history['91'] as $ph_91)
                            <tr>
                                <td>{{ $ph_91->created_at->format('Y-m-d')}}</td>
                                <td class="text-right">
                                    <span class="tag tag-success">
                                        {{ number_format($ph_91->max_fees,2)}}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td class="text-center">
                                <span class="tag tag-success">
                                    لا توجد سجلات
                                </span>
                            </td>
                            
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4">
        <div class="card">
            <div class="card-status" style="background: #EF4444;"></div>
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body" style="height: 300px;overflow">
                <table class="table card-table">
                    <tbody>
                    @if(count($price_history['95']))
                        @foreach($price_history['95'] as $ph_95)
                        <tr>
                            <td>{{ $ph_95->created_at->format('Y-m-d')}}</td>
                            <td class="text-right">
                                <span class="tag" style="background-color: #EF4444;color: white">
                                    {{ number_format($ph_95->max_fees,2)}}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td class="text-center">
                            <span class="tag" style="background-color: #EF4444;color: white">
                                لا توجد سجلات
                            </span>
                        </td>
                        
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-4" >
        <div class="card" >
            <div class="card-status" style="background: #eab308;"></div>
            <div class="card-header">
                <h3 class="card-title">Timeline</h3>
            </div>
            <div class="card-body" style="height: 300px;overflow">
                <table class="table card-table"  >
                    <tbody>
                    @if(count($price_history['disel']))
                        @foreach($price_history['disel'] as $ph_disel)
                        <tr>
                            <td>{{ $ph_disel->created_at->format('Y-m-d')}}</td>
                            <td class="text-right">
                                <span class="tag" style="background: #eab308;color:white">
                                    {{ number_format($ph_disel->max_fees,2)}}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td class="text-center">
                                <span class="tag" style="background: #eab308;color:white">
                                لا توجد سجلات
                                </span>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="update_price_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    </div>
</div> 


<script type="text/javascript">
    function editPrice(fuelType,station) {

        $.ajax({
            type: 'get',
            url : '{{ route('fuel-station.edit.price')}}',
            data:{
                _token : "{{ csrf_token() }}",
                fuel_type : fuelType,
                station_id : station,
            },
            beforeSend: function () {
                //App.startPageLoading({animate: true});
            }
        }).done(function(data){

            if(data.success==false){
                toastr.warning(data.msg);
            }
            $('#update_price_modal .modal-dialog').html('');
            $('#update_price_modal').modal("show").on('shown.bs.modal', function () {
                $('#update_price_modal .modal-dialog').html(data.view);
            });

        });

    }

    function updatePrice()
    {
        // if($('#update_price_modal .is-invalid').length > 0){
        //     return toastr.warning('تاكد من ادخال كافة الحقول');
        // }
        //console.log($('#update_price_modal .is-invalid').length);
        url = '{{ route('fuel-station.update.price') }}'
        var form = new FormData($('#fuel_price_form')[0]);
        form.append('company_id', $('#company_id').val());

        var data = form  ; 
        $.ajax({
            type: 'POST',
            url : url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            
        }).done(function(data){
            if(data.success)
            {
                toastr.success(data.msg);
                getData();
                closeItemModal();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function confirmPrice(status,priceDtId)
    {
        url = '{{ route('fuel-station.confirm.price') }}'
        $.ajax({
            type: 'POST',
            url : url,
            data: {
                "_token": "{{ csrf_token() }}",
                'status_m' : status,
                'price_list_dt_id_m' : priceDtId,
            },
            
            
        }).done(function(data){
            if(data.success)
            {
                toastr.success(data.msg);
                getData();
                closeItemModal();
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }

    function closeItemModal()
    {
        $('#update_price_modal').on('hidden.bs.modal', function () {
            //$('#update_price_modal .modal-body').html('');
        });
        $('#update_price_modal').modal('hide');
    }

</script> 









