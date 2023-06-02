@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}
@endsection

@section('content')


<div class="section-body mt-3" id="app">
    <div class="container-fluid">
        <div class="tab-content mt-3">
            
            <div class="tab-pane fade show active " id="data-grid" role="tabpanel">
                <form id="car_data_update_form">
                    @csrf  
                    <input type="hidden" class="form-control" name="uuid" id="uuid" value="{{ $car->uuid }}"> 
                    <div class="card-body">
                            <div class="row card">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> @lang('sales_car.company')   </label>
                                                <select class="form-select form-control" name="company_id_m" id="company_id_m" disabled>
                                                    <option value="auth()->user()->company->company_id" selected> {{ auth()->user()->company->getCompanyName()}}</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> @lang('sales_car.branch')  </label>
                                                <select class="form-select form-control" name="branch_id_m" id="branch_id_m" disabled>
                                                    @foreach($branch_list as $branch)
                                                        <option value="{{$branch->branch_id}}" {{($car->branch_id == $branch->branch_id ? 'selected' :  '' )}}>
                                                            {{ $branch->getBranchName() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> @lang('sales_car.car_brand')   </label>
                                                <select class="form-select form-control" name="sales_cars_brand_id" id="sales_cars_brand_id" disabled>
                                                    @foreach($car_brand_list as $brand)
                                                        <option value="{{$brand->brand_id}}"  {{($car->sales_cars_brand_id == $brand->brand_id ? 'selected' :  '' )}}>
                                                                {{ $brand->getName() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label "> @lang('sales_car.car_sales_cars_brand_dt_id')   </label>
                                                <select class="form-select form-control" name="sales_cars_brand_dt_id" id="sales_cars_brand_dt_id" disabled>
                                                    @foreach($car_brand_dt_list as $brand_dt)
                                                        <option value="{{$brand_dt->brand_dt_id}}"  {{($car->sales_cars_brand_dt_id == $brand_dt->brand_dt_id ? 'selected' :  '' )}}>
                                                                {{ $brand_dt->getBrandName() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_model') </label>
                                                <input type="number" class="form-control" name="sales_cars_model" id="sales_cars_model" value="{{$car->sales_cars_model}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_color') </label>
                                                <input type="text" class="form-control" name="sales_cars_color" id="sales_cars_color" value="{{$car->sales_cars_color}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_plate_no') </label>
                                                <input type="text" class="form-control" name="sales_cars_plate_no" id="sales_cars_plate_no" value="{{$car->sales_cars_plate_no}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_chasie_no') </label>
                                                <input type="number" class="form-control" name="sales_cars_chasie_no" id="sales_cars_chasie_no" value="{{$car->sales_cars_chasie_no}}" disabled>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_add_amount') </label>
                                                <input type="number" class="form-control" name="sales_cars_add_amount" id="sales_cars_add_amount" value="{{$car->sales_cars_add_amount}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_disc_amount') </label>
                                                <input type="number" class="form-control" name="sales_cars_disc_amount" id="sales_cars_disc_amount" value="{{$car->sales_cars_disc_amount}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_price_amount') </label>
                                                <input type="number" class="form-control" name="sales_cars_price_amount" id="sales_cars_price_amount" value="{{$car->sales_cars_price_amount}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_total_amount') </label>
                                                <input type="number" class="form-control" name="sales_cars_total_amount" id="sales_cars_total_amount" value="{{$car->sales_cars_total_amount}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_sales_amount') </label>
                                                <input type="number" class="form-control" name="sales_cars_sales_amount" id="sales_cars_sales_amount" value="{{$car->sales_cars_sales_amount}}" disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.car_status') </label>
                                                <select class="form-select form-control" name="sales_cars_status" id="sales_cars_status" disabled>
                                                    @foreach($car_status_list as $status)
                                                        <option value="{{$status->system_code}}"  {{($car->sales_cars_status == $status->system_code_id ? 'selected' :  '' )}}>
                                                                {{ $status->getSysCodeName() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="recipient-name" class="col-form-label"> @lang('sales_car.item_desc')  </label>
                                                <textarea rows="2" class="form-control" name="sales_cars_desc" id="sales_cars_desc" placeholder="Here can be your note" value="{{$car->sales_cars_desc}}">
                                                    {{$car->sales_cars_desc}}
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    @if($car_history->count())
                   
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row card">
                            <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>العملية</th>
                                                <th>الكود</th>
                                                <th>القيمة</th>
                                                <th>الاضافات</th>
                                                <th>الخصم</th>
                                                <th>االجمالي</th>
                                                <th>المستخدم</th>
                                                <th>التاريخ</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($car_history->get() as $his)
                                            <tr>
                                                <th scope="row">1</th>
                                                <td> {{ $his->storeVouType->getSysCodeName() }}</td>
                                                <td> {{ $his->sales->store_hd_code }}</td>
                                                <td> {{ $his->store_vou_item_total_price }}</td>
                                                <td> {{ $his->store_vou_item_total_price - $his->store_vou_item_price_unit }}  </td>
                                                <td> {{ $his->store_vou_disc_amount }} </td></td>
                                                <td> {{ $his->store_vou_price_net }} </td>
                                                <td> {{ $his->createdBy->getUserName() }} </td>
                                                <td> {{ $his->getVouDate() }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                <!-- <div class="timeline_item ">
                                    <span>
                                        <h6 class="float-right text-right"> {{ $his->getVouDate() }} </h6>
                                        <h6 class="font600"> {{ $his->storeVouType->getSysCodeName() }} رقم {{ $his->sales->store_hd_code }} </h6>
                                    </span>
                                </div> -->
                            </div>  
                        </div>
                    </div>
                    
                    @endif

                </form>
                <div class="card-footer">
                    <button class="btn btn-primary btn-block"  onclick="updateItem()" >  @lang('sales_car.update_button')   </button>
                    <a href="{{ route('sales-car.index') }}" class="btn btn-secondary btn-block"> @lang('sales_car.back_button') </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')


<script type="text/javascript">

    $(document).ready(function () {
        $('#company_id_m').change(function () {
            if (!$('#company_id_m').val()) {
                $('#company_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#company_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#item_category').change(function () {
            if (!$('#item_category').val()) {
                $('#item_category').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_category').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#branch_id_m').change(function () {
            if (!$('#branch_id_m').val()) {
                $('#branch_id_m').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#branch_id_m').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });


        $('#item_code').keyup(function () {
            console.log('111');
            if ($('#item_code').val().length < 3) {
                $('#item_code').addClass('is-invalid')
            } else {
                $('#item_code').removeClass('is-invalid');
            }
        });

        $('#item_name_a').keyup(function () {
            if ($('#item_name_a').val().length < 3) {
                $('#item_name_a').addClass('is-invalid')
            } else {
                $('#item_name_a').removeClass('is-invalid');
            }
        });

        $('#item_name_e').keyup(function () {
            if ($('#item_name_e').val().length < 3) {
                $('#item_name_e').addClass('is-invalid')
            } else {
                $('#item_name_e').removeClass('is-invalid');
            }
        });

        $('#item_vendor_code').keyup(function () {
            if ($('#item_vendor_code').val().length < 3) {
                $('#item_vendor_code').addClass('is-invalid')
            } else {
                $('#item_vendor_code').removeClass('is-invalid');
            }
        });

        $('#item_location').keyup(function () {
            if ($('#item_location').val().length < 3) {
                $('#item_location').addClass('is-invalid')
            } else {
                $('#item_location').removeClass('is-invalid');
            }
        });

        $('#item_code_1').keyup(function () {
            if ($('#item_code_1').val().length < 3) {
                $('#item_code_1').addClass('is-invalid')
            } else {
                $('#item_code_1').removeClass('is-invalid');
            }
        });

        $('#item_code_2').keyup(function () {
            if ($('#item_code_2').val().length < 3) {
                $('#item_code_2').addClass('is-invalid')
            } else {
                $('#item_code_2').removeClass('is-invalid');
            }
        });

        $('#item_price_sales').keyup(function () {
            if ($('#item_price_sales').val().length < 1) {
                $('#item_price_sales').addClass('is-invalid')
            } else {
                $('#item_price_sales').removeClass('is-invalid');
            }
        });

        $('#item_price_cost').keyup(function () {
            if ($('#item_price_cost').val().length < 1) {
                $('#item_price_cost').addClass('is-invalid')
            } else {
                $('#item_price_cost').removeClass('is-invalid');
            }
        });

        $('#item_balance').keyup(function () {
            if ($('#item_balance').val().length < 1) {
                $('#item_balance').addClass('is-invalid')
            } else {
                $('#item_balance').removeClass('is-invalid');
            }
        });

        $('#item_unit').change(function () {
            if (!$('#item_unit').val()) {
                $('#item_unit').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#item_unit').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

    });

    function updateItem()
    {
        if($('.is-invalid').length > 0){
            return toastr.warning('تاكد من ادخال كافة الحقول');
        }
        url = '{{ route('sales-car.update') }}'
        var form = new FormData($('#car_data_update_form')[0]);

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
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }
</script>
    
@endsection


