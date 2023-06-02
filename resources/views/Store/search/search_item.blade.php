<div id="search_item_modal" class="modal fade full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width:230%">
            <div class="modal-header">
               
                <h4 class="modal-title" style="text-align:right">
                     @lang('purchase.items')
                </h4>
                <div style="text-align:left">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>

            <div class="modal-body">
                <form id="search_data_form"  enctype="multipart/form-data">
                    @csrf  
                    <div class="col-md-12">
                        <div class="mb-3">
                            <div class="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label "> كود الصنف </label>
                                        <input type="text" class="form-control" name="item_code" id="item_code" value="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label"> الاسم عربي </label>
                                        <input type="text" class="form-control" name="item_name_a" id="item_name_a" value="">
                                    </div>
                                
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label "> الاسم انجليزي </label>
                                        <input type="text" class="form-control" name="item_name_e" id="item_name_e" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label "> كود المورد </label>
                                        <input type="text" class="form-control" name="item_vendor_code" id="item_vendor_code" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label "> الصنف البديل 1 </label>
                                        <input type="text" class="form-control" name="item_code_1" id="item_code_1" value="">
                                    </div>

                                    <div class="col-md-4">
                                        <label for="recipient-name" class="col-form-label "> الصنف البديل 2  </label>
                                        <input type="text" class="form-control" name="item_code_2" id="item_code_2" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-primary btn-block" onclick="getSearchResult()">
                                            @lang('storeItem.search_button')
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-warning btn-block" onclick="$('#search_data_form')[0].reset();">
                                            @lang('storeItem.clear_button')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div id="searchData"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
                    <button type="button" id="btnCancel" class="btn btn-secondary btn-block"  data-dismiss="modal">الغاء</button>
                </div>
            </div>
        </div>
    </div>
</div>

