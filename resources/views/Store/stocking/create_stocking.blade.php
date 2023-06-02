<div id="create_stocking_modal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content" style="width:150%">
            <div class="modal-header">
               
                <h4 class="modal-title" style="text-align:right">
                    @lang('stocking.add_new') 
                </h4>
                <div style="text-align:left">
                <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="stocking_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> @lang('stocking.item_category') </label>
                                    <select class="form-select form-control is-invalid" name="store_category_type" id="store_category_type" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($warehouses_type_lits as $w_t)
                                        <option value="{{$w_t->system_code}}" data-syscodeid="{{ $w_t->system_code_id }}"> {{ $w_t->getSysCodeName() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label">  @lang('stocking.branch') </label>
                                    <select class="form-select form-control is-invalid" name="branch" id="branch" required>
                                        <option value="" selected> choose</option>  
                                        @foreach($branch_list as $branch)
                                        <option value="{{$branch->branch_id}}" data-vendorname="{{ $branch->getBranchName() }}" > {{ $branch->getBranchName() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> @lang('stocking.location') </label>
                                    <select class="form-select form-control car" name="store_location" id="store_location" data-live-search="true" required>
                                        <option value="" selected> choose</option>  
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="recipient-name" class="col-form-label "> @lang('stocking.stocking_date') </label>
                                    <input type="date" class="form-control is-invalid" name="store_vou_date" id="store_vou_date" value="" >
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="recipient-name" class="col-form-label">@lang('stocking.note') </label>
                                    <textarea rows="2" class="form-control" name="store_vou_notes" id="store_vou_notes" placeholder="Here can be your note" value=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()">الغاء</button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-primary forward btn-block" onclick="saveRequest()">اضافة </button>
                </div>		
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function () {

        $('#store_category_type').change(function () {
            if (!$('#store_category_type').val()) {
                $('#store_category_type').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_category_type').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });
        

        $('#branch').change(function () {
            if (!$('#branch').val()) {
                $('#branch').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#branch').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_vou_date').change(function () {
            if (!$('#store_vou_date').val()) {
                $('#store_vou_date').addClass('is-invalid');
                //$('.car').addClass("is-invalid");
            } else {
                $('#store_vou_date').removeClass('is-invalid');
                //$('.car').removeClass("is-invalid");
            }
        });

        $('#store_location').keyup(function () {
            if ($('#store_location').val().length < 3) {
                $('#store_location').addClass('is-invalid')
            } else {
                $('#store_location').removeClass('is-invalid');
            }
        });

        $('#store_category_type').on('change',function(){
            $("#store_location option").remove();
            $('#branch').val('').change();
           // $('#branch').selectpicker('refresh');
        });

        $('#branch').on('change',function(){
            $("#store_location option").remove();

            if($('#store_category_type').val() == '')
            {
                return toastr.warning('لابد من نحديد المستودع اولا');
            }
            
            $.ajax({
                url : '{{ route( 'store-stocking.get.item.location' ) }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "store_category_type":  $('#store_category_type :selected').data('syscodeid'),
                    "branch": $('#branch').val(),
                    },
                type: 'get',
                dataType: 'json',
                success: function( result )
                {
                    $('#store_location').append($('<option>', {value:'', text:'choose'}));
                    $.each( result.data, function(k) {
                        data = result.data;
                        
                        $('#store_location').append(
                            // $('<option>', {value:data[k].brand_dt_id, text: data[k].brand_dt_name_en })
                            $('<option>', 
                                    {
                                        value:data[k].item_location,
                                        text: data[k].item_location,
                                        data: { 'store_location' : data[k] ,'store_location' : data[k].item_location   }
                                    }
                                )
                            
                            );
                    });
                },
                error: function()
                {
                    //handle errors
                    alert('error...');
                }
            });
        });

    });

    function saveRequest()
    {
        if($('.is-invalid').length > 0){
            return toastr.warning('تاكد من ادخال كافة الحقول');
        }
        url = '{{ route('store-stocking.store') }}'
        var form = new FormData($('#stocking_data_form')[0]);
        form.append('company_id', $('#company_id').val());
        form.append('warehouses_type', $('#warehouses_type').val());
        form.append('branch_id', $('#branch_id').val());

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
                url =  '{{ route("store-stocking.edit", ":id") }}';
                url = url.replace(':id',data.uuid);
                window.location.href = url;
                
            }
            else
            {
                toastr.warning(data.msg);
            }
        });
    }
</script>