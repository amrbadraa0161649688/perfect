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
                <table class="table table-hover table-vcenter table_custom text-nowrap spacing5 text-nowrap mb-0">
                    <thead>
                    <tr>
                        <th class="text-center">@lang('home.notes_data')</th>
                        <th>@lang('home.notes_date')</th>
                        <th>@lang('home.created_user')</th>
                        <th>@lang('home.notes_serial')</th>
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
