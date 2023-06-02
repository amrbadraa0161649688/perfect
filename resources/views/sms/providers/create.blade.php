<div id="add_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:180%">
            <div class="modal-header">
                <h4 class="modal-title" style="text-align:right">
                    @lang('sms.add_new_provider')
                </h4>
                <div style="text-align:left">
                    <button type="button" class="close" data-dismiss="modal" onclick="closeItemModal()" >&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="providers_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4"> 
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.company')   </label>
                                    <select class="form-select form-control is-invalid" name="company_id_m" id="company_id_m">
                                        <option value="" selected> </option>    
                                        @foreach($companies as $company)
                                            <option value="{{$company->company_id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$company->company_name_ar}}
                                                @else
                                                    {{$company->company_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.provider_name') </label>
                                    <input type="text" class="form-control is-invalid" name="sms_provider_name" id="sms_provider_name" value=""  oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label "> @lang('sms.provider_phone')  </label>
                                    <input type="number" class="form-control is-invalid" name="sms_provider_phone" id="sms_provider_phone" 
                                    oninput="this.value=this.value.replace( /[^0-9]/g,'');" required>
                                </div>

                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.account_sid')   </label>
                                    <input type="text" class="form-control is-invalid" name="account_sid" id="account_sid" value="" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'');" required>
                                </div>

                               
                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.account_user_name')  </label>
                                    <input type="text" class="form-control is-invalid" name="account_user_name" id="account_user_name" oninput="this.value=this.value.replace(/[^A-Za-z-0-9\s]/g,'');" required>
                                </div>

                                <div class="col-md-4">
                                    <label for="recipient-name" class="col-form-label"> @lang('sms.account_password') </label>
                                    <input type="text" class="form-control is-invalid" name="account_password" id="account_password" required>
                                </div>
                                @if(false)
                                <div class="col-md-4 d-none">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.from_phone_no')   </label>
                                    <input type="text" class="form-control is-invalid" name="from_phone_no" id="from_phone_no" value=""  required>
                                </div>


                                <div class="col-md-4 d-none">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.account_auth_token')  </label>
                                    <input type="number" class="form-control is-invalid" name="account_auth_token" id="account_auth_token"  oninput="this.value=this.value.replace( /[^0.01-9.01]/g,'');" required>
                                </div>

                                
                                <div class="col-md-4 d-none">
                                    <label for="recipient-name" class="col-form-label ">  @lang('sms.sms_base_url')   </label>
                                    <input type="text" class="form-control" name="sms_base_url" id="sms_base_url" value="">
                                </div>

                                <div class="col-md-4 d-none">
                                    <label for="recipient-name" class="col-form-label">  @lang('sms.provider_type')  </label>
                                    <input type="number" class="form-control is-invalid" name="provider_type" id="provider_type"  oninput="this.value=this.value.replace( /[^0.01-9.01]/g,'');" required>
                                </div> 

                                <div class="col-md-4 d-none">
                                    <label for="recipient-name " class="col-form-label">   @lang('sms.provider_resource')  </label>
                                    <textarea rows="2" class="form-control" name="provider_resource" id="provider_resource" placeholder="Here can be your note" value=""></textarea>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block" onclick="closeItemModal()">  @lang('sms.cancel_button') </button>
                </div>
                <div class="col-sm-6 col-xs-6 col-md-6 col-lg-6">	
                    <button type="button" id="btnSave" class="btn btn-success forward btn-block" onclick="saveProvider()">  @lang('sms.add_button')  </button>
                </div>		
            </div>
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

        $('#sms_provider_name').keyup(function () {
            if ($('#sms_provider_name').val().length < 3) {
                $('#sms_provider_name').addClass('is-invalid')
            } else {
                $('#sms_provider_name').removeClass('is-invalid');
            }
        });

        $('#sms_provider_phone').keyup(function () {
            if ($('#sms_provider_phone').val().length < 12) {
                $('#sms_provider_phone').addClass('is-invalid')
            } else {
                $('#sms_provider_phone').removeClass('is-invalid');
            }
        });

        $('#account_sid').keyup(function () {
            
            if ($('#account_sid').val().length < 3) {
                $('#account_sid').addClass('is-invalid');
            } else {
                $('#account_sid').removeClass('is-invalid');
            }
        });

        $('#account_user_name').keyup(function () {
            if ($('#account_user_name').val().length < 3) {
                $('#account_user_name').addClass('is-invalid')
            } else {
                $('#account_user_name').removeClass('is-invalid');
            }
        });

        $('#account_password').keyup(function () {
            if ($('#account_password').val().length < 3) {
                $('#account_password').addClass('is-invalid')
            } else {
                $('#account_password').removeClass('is-invalid');
            }
        });

    });
</script>