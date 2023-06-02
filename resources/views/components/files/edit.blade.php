@extends('Layouts.master')
<link rel="stylesheet" href="{{asset('assets/plugins/dropify/css/dropify.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-datetimepicker.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css"/>
<link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }
    </style>

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row clearfix">

                @include('Includes.form-errors')
                @foreach($attachments as $attachment)
                <form class="card"  action="{{ route('attachments.update',$attachment->attachment_id) }}"  method="post" id="submit">
                @csrf
                    @method('put')
                 
                    <div class="card-header bold"> تعديل بيانات المستند
                      
                        
                    </div>
                    <div class="card-body">
                   
                    <div class="row">
                   

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="{{$attachment->attachment_type}}">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code }}"
                                                @if($attachment->attachment_type == $attachment_type->system_code) selected @endif >
                                                {{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>@lang('home.issue_date')</label>
                                            <input class="form-control" type="date" id="issue_date" @change="getIssueDate()"
                                                v-model="issue_date" value="{{$attachment->issue_date}}"
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
                                                v-model="expire_date" name="expire_date"  required>

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
                                                        name="attachment_data" value="{{$attachment->attachment_data}}" ></textarea>

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                
                                            </div>

                                        </div>
                                    </div>

                                
                        

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-secondary mr-2">@lang('home.save')</button>
                    </div>

                    @endforeach

                </form>

            </div>
           
        </div>
    </div>

@endsection()
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
    <script src="{{asset('assets/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>
    <script type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

   
  <script src="{{asset('assets/plugins/dropify/js/dropify.min.js')}}"></script>
    <script src="{{asset('assets/js/form/form-advanced.js')}}"></script>
    <script>
        $(function () {
            $("#issue_date_hijri").hijriDatePicker();
            $("#expire_date_hijri").hijriDatePicker();
          
        });

new Vue({
    el: '#app',
    data: {
       
        expire_date: '',
        issue_date: '',
        issue_date_hijri: '',
        expire_date_hijri: ''
        

    },
    mounted() {
      

        

        $('#issue_date_hijri').on("dp.change", (e) => {
            this.issue_date_hijri = $('#issue_date_hijri').val()
            this.getGeorgianDate()
        });

        $('#expire_date_hijri').on("dp.change", (e) => {
            this.expire_date_hijri = $('#expire_date_hijri').val()
            this.getGeorgianDate2()
        });

    },  
    
    methods: {

        getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },

                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },

                getGeorgianDate() {
                    if (this.issue_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.issue_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.issue_date = response.data
                        })
                    }
                },

                getGeorgianDate2() {
                    if (this.expire_date_hijri) {
                        $.ajax({
                            type: 'GET',
                            data: {date: this.expire_date_hijri},
                            url: '{{ route("test-date") }}'
                        }).then(response => {
                            this.expire_date = response.data
                        })
                    }
                },


    }
});
</script>
@endsection
