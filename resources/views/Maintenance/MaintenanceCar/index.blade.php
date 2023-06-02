@extends('Layouts.master')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    {{-- datatable styles --}}

@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <form action="">
                        @if(session('company_group'))
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ session('company_group')['company_group_ar'] }} @else
                            {{ session('company_group')['company_group_en'] }} @endif" readonly>
                        @else
                            <input type="text" class="form-control" value="@if(app()->getLocale()=='ar')
                            {{ auth()->user()->companyGroup->company_group_ar }} @else
                            {{ auth()->user()->companyGroup->company_group_en }} @endif" readonly>
                        @endif
                    </form>
                </div>
                <div class="col-md-6">
                    <form action="">
                        <select class="form-control" id="company_id" name="company_id" onchange="getData( $('#company_id').val())">
                            <option value="">@lang('home.choose')</option>
                            @foreach($companies as $company)
                                <option value="{{$company->company_id}}"
                                        @if(request()->company_id == $company->company_id) selected @endif>
                                    @if(app()->getLocale() == 'ar')
                                        {{$company->company_name_ar}}
                                    @else
                                        {{$company->company_name_en}}
                                    @endif
                                </option>

                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
            <div id="showData"></div>
        </div>
    </div>

    
@endsection

@section('scripts')


    <script type="text/javascript">
        @if(session('company'))
            var company_id = {{ request()->company_id ? request()->company_id : session('company')['company_id'] }}
        @else
            var company_id ={{ request()->company_id ? request()->company_id : auth()->user()->company_id }}
        @endif

        $(function () {
            getData();
        });

        function getData(companyId){
            //console.log($('#company_id').val());
            company_id = ($('#company_id').val() ? $('#company_id').val() : {{ auth()->user()->company_id }});
            $.ajax({
                type: 'get',
                url :"{{route('maintenance-car.data')}}",
                data:{
                    _token : "{{ csrf_token() }}",
                    company_id : companyId,
                },
                beforeSend: function () {
                    //App.startPageLoading({animate: true});
                }
            }).done(function(data){
                //App.stopPageLoading();

                if(data.success==false){
                    toastr.warning(data.msg);
                }
                $('#showData').html(data.view);
            });
        }
    </script>

    <script>
        
        $(document).ready(function () {
            function show(el) {
                var x = el.id;
                $("#app-" + x).css("display", "block");
                $("#app-" + x).siblings().css('display', 'none')
            }

            $('#user_mobile_search').keyup(function () {
                if ($('#user_mobile_search').val().length >= 10) {
                    $('#search_form').submit()
                }
            });


            //    validation to create modal
            $('#user_name_ar').keyup(function () {
                if ($('#user_name_ar').val().length < 3) {
                    $('#user_name_ar').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_ar').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_name_en').keyup(function () {
                if ($('#user_name_en').val().length < 3) {
                    $('#user_name_en').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_name_en').removeClass('is-invalid')
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_email').keyup(function () {
                if (!validEmail($('#user_email').val())) {
                    $('#user_email').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_email').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });


            $('#user_mobile').keyup(function () {
                if ($('#user_mobile').val().length < 11) {
                    $('#user_mobile').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_mobile').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_code').keyup(function () {
                if ($('#user_code').val().length < 3) {
                    $('#user_code').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_code').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_password').keyup(function () {
                if ($('#user_password').val().length < 6) {
                    $('#user_password').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#user_password').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#user_start_date').change(function () {
                $('#user_start_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#user_end_date').change(function () {
                $('#user_end_date').removeClass('is-invalid')
                $('#create_user').removeAttr('disabled');
            });

            $('#company_group_id').change(function () {
                if (!$('#company_group_id').val()) {
                    $('#company_group_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_group_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            $('#company_id').change(function () {
                if (!$('#company_id').val()) {
                    $('#company_id').addClass('is-invalid')
                    $('#create_user').attr('disabled', 'disabled')
                } else {
                    $('#company_id').removeClass('is-invalid');
                    $('#create_user').removeAttr('disabled');
                }
            });

            function validEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        })
    </script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    {{--<script>--}}

    {{--new Vue({--}}
    {{--el: '#app',--}}
    {{--data: {--}}
    {{--companies: {},--}}
    {{--company_group_id: ""--}}
    {{--},--}}
    {{--methods: {--}}
    {{--getCompanies() {--}}
    {{--$.ajax({--}}
    {{--type: 'GET',--}}
    {{--data: {id: this.company_group_id},--}}
    {{--url: '{{ route("api.company-group.companies") }}'--}}
    {{--}).then(response => {--}}
    {{--this.companies = response.data--}}
    {{--})--}}

    {{--}--}}
    {{--}--}}
    {{--})--}}

    {{--</script>--}}
@endsection

