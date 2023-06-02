<div class="d-flex justify-content-between align-items-center">
    <button type="button" class="btn btn-primary mb-2" id="add_files">
        <i class="fe fe-plus"></i>
        @lang('home.add_files')
    </button>
</div>


<div class="card" id="add_files_form" style="display: none">
    <div class="card-body">
        <form action="{{ route('attachments.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">
                {{ $slot }}
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('home.issue_date')</label>
                        <input class="form-control" type="date" id="issue_date" @change="getIssueDate()"
                               v-model="issue_date"
                               name="issue_date" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('home.issue_date_hijri')</label>
                        <input class="form-control" type="text" id="issue_date_hijri" v-model="issue_date_hijri"
                               name="issue_date_hijri" required>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('home.copy_no')</label>
                        <input class="form-control" type="number" name="copy_no"
                               placeholder="@lang('home.copy_no')">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('home.expire_date')</label>
                        <input class="form-control" type="date" id="expire_date" @change="getExpireDate()"
                               v-model="expire_date" name="expire_date" required>

                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>@lang('home.expire_date_hijri')</label>
                        <input class="form-control" type="text" name="expire_date_hijri"
                               v-model="expire_date_hijri" id="expire_date_hijri" required>
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('home.attachment_data')</label>
                        <textarea class="form-control" placeholder="@lang('home.attachment_data')"
                                  name="attachment_data"></textarea>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <div class="card">
                            <div class="card-header">

                            </div>
                            <div class="card-body">
                                <input type="file" id="dropify-event" accept=".png, .jpg , .pdf"
                                       name="attachment_file_url" @if(isset($required) && !$required) @else required @endif>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary">@lang('home.save')</button>
                </div>
            </div>
        </form>
    </div>
</div>


