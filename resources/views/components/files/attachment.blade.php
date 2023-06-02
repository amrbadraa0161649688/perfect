<div>
    <div class="card bg-none">
        <div class="card-header">
            <div class="card-options">
                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                        class="fe fe-chevron-up"></i></a>
                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                        class="fe fe-maximize"></i></a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive table_e2">

                <table class="table table-hover table-vcenter table-striped dataTable" style="overflow-x: scroll">
                    <thead>
                    <tr>
                        <th>@lang('home.attachment_type')</th>
                        <th>@lang('home.issue_date')</th>
                        <th>@lang('home.expire_date')</th>
                        <th>@lang('home.issue_date_hijri')</th>
                        <th>@lang('home.expire_date_hijri')</th>
                        <th>@lang('home.copy_no')</th>
                        <th>الملف</th>
                        <th>@lang('home.attachment_data')</th>
                        <th>@lang('home.created_user')</th>
                        <th>@lang('home.created_at')</th>
                        {{--<th>@lang('home.attachment_file_url')</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    {{ $slot }}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


