@extends('Layouts.master')

@section('content')

    <div class="section-body mt-6" id="app">
        <div class="container-fluid">

            <div class="tab-content mt-6">
                {{-- Basic information --}}
                {{--<div class="tab-pane fade show active" id="data-grid" role="tabpanel">--}}


                <form action="{{route('internal-inspection.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row clearfix">


                        @foreach($evaluation_hds as $evaluation_hd)

                            <div class="col-md-12">
                                <div class="card card-collapsed">
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            {{app()->getLocale() == 'ar' ? $evaluation_hd->evaluation_name_ar : $evaluation_hd->evaluation_name_en}}
                                        </h3>
                                        <div class="card-options">
                                            <a href="#" class="card-options-collapse"
                                               data-toggle="card-collapse"><i
                                                        class="fe fe-chevron-up"></i></a>
                                            <a href="#" class="card-options-fullscreen"
                                               data-toggle="card-fullscreen"><i
                                                        class="fe fe-maximize"></i></a>
                                            <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                        class="fe fe-x"></i></a>

                                        </div>
                                    </div>


                                    <div class="card-body">


                                        <div class="table-responsive">
                                            <table class="table text-nowrap mb-0">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>السؤال</th>
                                                    <th>الملاحظات</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <label for="files{{$evaluation_hd}}" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
                                                        padding:10px;display: block">Take Photo</label>
                                                        <input id="files{{$evaluation_hd}}" type="file" name="image[]"
                                                               capture="user" style="visibility:hidden;"
                                                               accept="image/*">
                                                    </td>
                                                    <td></td>

                                                </tr>


                                                @foreach($evaluation_hd->evaluationDts as $k=>$evaluation_dt)
                                                    <input type="hidden" name="evaluation_dt_id[]"
                                                           value="{{$evaluation_dt->evaluation_dt_id}}">

                                                    <input type="hidden" name="evaluation_id[]"
                                                           value="{{$evaluation_dt->evaluationHd->evaluation_id}}">
                                                    <tr>
                                                        <td>{{$k+1}}</td>
                                                        <td>
                                                            <p class="pb-2">
                                                                {{$evaluation_dt->evaluation_dt_name_ar}}
                                                            </p>

                                                            <div class="row gutters-lg">

                                                                <div class="col-md-4 col-sm-4">
                                                                    <label class="colorinput">
                                                                        <input name="evaluation_result[]"
                                                                               type="checkbox"
                                                                               value="1"
                                                                               onchange="checkOne('{{$k}}')"
                                                                               class="colorinput-input sev_check{{$k}}"/>
                                                                        <span class="colorinput-color"
                                                                              style="background-color:#00cc00 !important;"></span>

                                                                    </label>

                                                                    <p class="font-15 bold d-inline-block">
                                                                        جيد</p>

                                                                </div>

                                                                <div class="col-md-4 col-sm-4">
                                                                    <label class="colorinput">
                                                                        <input name="evaluation_result[]"
                                                                               type="checkbox"
                                                                               value="0"
                                                                               onchange="checkOne('{{$k}}')"
                                                                               class="colorinput-input sev_check{{$k}}"/>
                                                                        <span class="colorinput-color"
                                                                              style="background-color:#8b0000 !important;"></span>
                                                                    </label>

                                                                    <p class="font-15 bold d-inline-block"> غير
                                                                        جيد</p>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td><textarea class="form-control"
                                                                      name="evaluation_notes[]"></textarea></td>
                                                    </tr>

                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach


                    </div>

                    <div class="row">
                        <button class="btn btn-primary" type="submit">حفظ</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}
    <script>
        function checkOne(el) {
            console.log('.sev_check' + el);
            $('.sev_check' + el).change(function (e) {
                e.preventDefault();
                $('.sev_check' + el).not(this).prop('checked', false);
                $(this).prop('checked', true);
            });
        }
    </script>
@endsection