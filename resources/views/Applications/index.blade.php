@extends('Layouts.master')

@section('content')

    <div class="section-body mt-3">
        <div class="container-fluid">

            <div class="header-action">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    <i class="fe fe-plus mr-2"></i>@lang('home.add_application')</button>
            </div>

            {{--pop up to create--}}
            <div class="modal fade" id="exampleModal" tabindex="-1"
                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">@lang('home.add_application')</h5>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('application.store') }}" method="post" id="submit_application_form">
                                @csrf
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_ar') </label>
                                            <input type="text" class="form-control"
                                                   name="app_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                   id="app_name_ar" placeholder="@lang('home.name_ar')">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="recipient-name"
                                                   class="col-form-label"> @lang('home.name_en') </label>
                                            <input type="text" class="form-control"
                                                   name="app_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                   id="app_name_en" placeholder="@lang('home.name_en')">
                                        </div>

                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col md 6">
                                            <label for="message-text"
                                                   class="col-form-label"> @lang('home.application_code')</label>
                                            <input type="text" class="form-control" name="app_code"
                                                   id="app_code" placeholder="@lang('home.application_code')">
                                        </div>
                                        <div class="col md 6">
                                            <label for="message-text" class="col-form-label"> @lang('home.icon')</label>
                                            <input type="text" class="form-control" name="app_icon" id="app_icon"
                                                   placeholder="@lang('home.icon')">
                                        </div>
                                    </div>
                                    <label for="message-text"
                                           class="col-form-label"> @lang('home.application_status')</label>
                                    <select class="form-select form-control" name="app_status"
                                            aria-label="Default select example" id="app_status">
                                        <option value="1" selected>On</option>
                                        <option value="0">Off</option>
                                    </select>


                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary mr-2"
                                            data-bs-dismiss="modal" id="create_application">@lang('home.save')</button>
                                    <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">@lang('home.close')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-options">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-vcenter table-hover mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>@lang('home.application_name')</th>
                                        <th>@lang('home.icon')</th>
                                        <th>@lang('home.application_code')</th>
                                        <th>@lang('home.status')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($applications as $k=>$application)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>
                                                <div class="font-15">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{ $application->app_name_ar }}
                                                    @else
                                                        {{ $application->app_name_en }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $application->app_icon }}</td>
                                            <td>{{ $application->app_code }}</td>
                                            <td>@if($application->app_status)<i class="fa fa-check"></i> @else
                                                    <i class="fa fa-remove"></i>  @endif</td>
                                            <td>
                                                <button type="button" class="btn btn-icon" data-toggle="modal"
                                                        data-target="#exampleModal{{ $application->app_id }}"
                                                        title="@lang('home.edit')">
                                                    <i class="fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-icon js-sweetalert"
                                                        id="application{{ $application->app_id }}"
                                                        title="@lang('home.show')" data-type="confirm"
                                                        onclick="show(this)">
                                                    <i class="fa fa-eye text-danger"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        {{--pop up to update--}}
                                        <div class="modal fade exampleApplication"
                                             id="exampleModal{{ $application->app_id }}" tabindex="-1"
                                             data-toggle="modal" data-target="#exampleModal{{ $application->app_id }}"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">@lang('home.add_application')</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('application.update' , $application->app_id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('put')
                                                            <div class="mb-3">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('home.name_ar') </label>
                                                                        <input type="text"
                                                                               class="form-control app_name_ar"
                                                                               name="app_name_ar" oninput="this.value=this.value.replace( /[^ء-ي]/g,' ');"
                                                                               id="recipient-name" required
                                                                               value="{{$application->app_name_ar}}">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label"> @lang('home.name_en') </label>
                                                                        <input type="text"
                                                                               class="form-control app_name_en"
                                                                               name="app_name_en" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,' ');"
                                                                               required
                                                                               id="recipient-name"
                                                                               value="{{$application->app_name_en}}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary mr-2"
                                                                        data-bs-dismiss="modal">@lang('home.save')</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">@lang('home.close')
                                                                </button>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        @foreach($applications as $application)
            <div class="section-body section-sub-application mt-3" id="app-application{{ $application->app_id }}"
                 style="display:none">
                <div class="container-fluid">
                    <div class="tab-content mt-3">
                        <div class="tab-pane fade show active" id="Departments-list" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-options">
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{route('applicationMenu.create', $application->app_id)}}"
                                       class="btn btn-primary">
                                        <i class="fe fe-plus mr-2"></i>
                                        @lang('home.add_application_menu')</a>
                                    <p>@lang('home.to')
                                       <span style="text-decoration: underline;font-size:20px">
                                            @if(app()->getLocale()=='ar') {{ $application->app_name_ar }} @else  {{ $application->app_name_en }} @endif
                                       </span></p>

                                    <div class="table-responsive">
                                        <table class="table table-striped table-vcenter table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>@lang('home.application_name')</th>
                                                <th>@lang('home.url')</th>
                                                <th>@lang('home.order')</th>
                                                <th>@lang('home.application_code')</th>
                                                <th>@lang('home.status')</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($application->applicationMenu as $k=>$application_menu)
                                                <tr>
                                                    <td>{{ $k+1 }}</td>
                                                    <td>
                                                        <div class="font-15">
                                                            @if(app()->getLocale() == 'ar')
                                                                {{ $application_menu->app_menu_name_ar }}
                                                            @else
                                                                {{ $application_menu->app_menu_name_en }}
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>{{ $application_menu->app_menu_url }}</td>
                                                    <td>{{ $application_menu->app_menu_order }}</td>
                                                    <td>{{ $application_menu->app_menu_code }}</td>
                                                    <td>@if($application_menu->app_menu_is_active) <i
                                                                class="fa fa-remove"></i> @else <i
                                                                class="fa fa-check"></i> @endif</td>
                                                    <td>
                                                        <a href="{{route('applicationMenu.show',$application_menu->app_menu_id)}}"
                                                           class="btn btn-icon"
                                                           title="@lang('home.edit')"><i
                                                                    class="fa fa-edit"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('scripts')

    <script>
        $(".modal form input").click(function (event) {
            event.stopPropagation();
        });

        $(".modal form select").click(function (event) {
            event.stopPropagation();
        });

        $(".exampleApplication").each(function () {
            $(this).click(function (event) {
                event.stopPropagation();
            })
        });

        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }

        $('.app_name_en').keyup(function () {
            if ($(this).val().length < 3) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        });

        $('.app_name_ar').keyup(function () {
            if ($(this).val().length < 3) {
                $(this).addClass('is-invalid')
            } else {
                $(this).removeClass('is-invalid')
            }
        });

        if ($('#app_name_ar').val().length == 0) {
            $('#app_name_ar').addClass('is-invalid')
            $('#create_application').attr('disabled', 'disabled')
        } else {
            $('#app_name_ar').removeClass('is-invalid');
            $('#create_application').removeAttr('disabled');
        }

        if ($('#app_name_en').val().length == 0) {
            $('#app_name_en').addClass('is-invalid')
            $('#create_application').attr('disabled', 'disabled')
        } else {
            $('#app_name_en').removeClass('is-invalid');
            $('#create_application').removeAttr('disabled');
        }

        if ($('#app_code').val().length == 0) {
            $('#app_code').addClass('is-invalid')
            $('#create_application').attr('disabled', 'disabled')
        } else {
            $('#app_code').removeClass('is-invalid');
            $('#create_application').removeAttr('disabled');
        }

        if ($('#app_icon').val().length == 0) {
            $('#app_icon').addClass('is-invalid')
            $('#create_application').attr('disabled', 'disabled')
        } else {
            $('#app_icon').removeClass('is-invalid');
            $('#create_application').removeAttr('disabled');
        }


        //    validation to create modal
        $('#app_name_ar').keyup(function () {
            if ($('#app_name_ar').val().length < 3) {
                $('#app_name_ar').addClass('is-invalid');
                $('#create_application').attr('disabled', 'disabled');
            } else {
                $('#app_name_ar').removeClass('is-invalid');
                $('#create_application').removeAttr('disabled');
            }
        });

        $('#app_name_en').keyup(function () {
            if ($('#app_name_en').val().length < 3) {
                $('#app_name_en').addClass('is-invalid');
                $('#create_application').attr('disabled', 'disabled');
            } else {
                $('#app_name_en').removeClass('is-invalid');
                $('#create_application').removeAttr('disabled');
            }
        });

        $('#app_code').keyup(function () {
            if ($('#app_code').val().length < 3) {
                $('#app_code').addClass('is-invalid');
                $('#create_application').attr('disabled', 'disabled');
            } else {
                $('#app_code').removeClass('is-invalid');
                $('#create_application').removeAttr('disabled');
            }
        });

        $('#app_icon').keyup(function () {
            if ($('#app_icon').val().length < 3) {
                $('#app_icon').addClass('is-invalid');
                $('#create_application').attr('disabled', 'disabled');
            } else {
                $('#app_icon').removeClass('is-invalid');
                $('#create_application').removeAttr('disabled');
            }
        });


    </script>

@endsection

