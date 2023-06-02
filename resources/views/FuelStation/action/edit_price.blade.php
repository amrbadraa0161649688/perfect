
<div class="modal-content" style="width:180%">
    <div class="modal-header">
        <h4 class="modal-title" style="text-align:right">
            تحديث اسعار 
        </h4>
        <div style="text-align:left">
            <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()" >&times;</button>
        </div>
    </div>

    <div class="modal-body">
        <form id="fuel_price_form"  enctype="multipart/form-data">
            @csrf  
            <div class="col-md-12">
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-6"> 
                            <label for="recipient-name" class="col-form-label "> @lang('sms.company')   </label>
                            <select class="form-select form-control " name="company_id_m" id="company_id_m" disabled>
                                    <option value="{{$company->company_id}}" >
                                            {{$company->getNameAttribute()}}
                                    </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="recipient-name" class="col-form-label "> المنطقة </label>
                            <select class="form-control " id="zone_id_m" name="zone_id_m" disabled>
                                <option value="">@lang('home.choose')</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="recipient-name" class="col-form-label "> المحطة </label>
                            <select class="form-control " id="station_id_m" name="station_id_m" readonly>
                                    <option value="{{$branch->branch_id}}">
                                            {{ $branch->getBranchName() }}
                                    </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="recipient-name" class="col-form-label "> نوع الوقود </label>
                            <select class="form-control " id="fuel_type_m" name="fuel_type_m" readonly>
                                    <option value="{{$fuel_type->system_code}}">
                                            {{ $fuel_type->getSysCodeName() }}
                                    </option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="recipient-name" class="col-form-label "> السعر  </label>
                            <input type="text" class="form-control is-invalid" name="price_m" id="price_m" 
                            oninput="this.value=this.value.replace( /[^0-9.\s]/g,'');" required>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
            <button type="button" id="btnCancel" class="btn btn-secondary btn-block " onclick="closeItemModal()">  الغاء </button>
        </div>
        <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
            <button type="button" id="btnSave" class="btn btn-success forward btn-block " onclick="updatePrice()">  حفظ  </button>
        </div>		
    </div>
</div>
    

<script type="text/javascript">
    
    $(document).ready(function () {
        $('#company_id_m').change(function () {
            if ($('#company_id_m').val() == '') {
                $('#company_id_m').addClass('is-invalid');
            } else {
                $('#company_id_m').removeClass('is-invalid');
            }
        });
    });

    $('#price_m').keyup(function () {
            
            if ($('#price_m').val().length < 1) {
                $('#price_m').addClass('is-invalid');
            } else {
                $('#price_m').removeClass('is-invalid');
            }
        });
</script>