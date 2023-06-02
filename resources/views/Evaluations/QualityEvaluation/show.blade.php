@extends('Layouts.master')
@section('content')

    <div class="section-body py-4">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-3">
                    <label>الكود</label>
                    <input type="text" readonly value="{{$evaluation_file->evaluation_file_code}}" class="form-control">

                </div>
                <div class="col-md-3">
                    <label>التاريخ</label>
                    <input type="text" readonly value="{{$evaluation_file->date}}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>الوقت</label>
                    <input type="text" readonly value="{{$evaluation_file->time}}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>الموظف</label>
                    <input type="text" readonly value="{{$evaluation_file->user->user_name_ar}}" class="form-control">
                </div>
            </div>

            <div class="row clearfix">

                @foreach($evaluation_file_dts as $k=>$evaluation_file_dt)

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{app()->getLocale() == 'ar' ? \App\Models\EvaluationHd::find($k)->evaluation_name_ar :
                                    \App\Models\EvaluationHd::find($k)->evaluation_name_en}}
                                </h3>
                                <div class="card-options">
                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                                class="fe fe-chevron-up"></i></a>
                                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
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
                                            <th>الحاله</th>
                                            <th>الملاحظات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($evaluation_file_dt as $k=>$evaluation_file_d)
                                            <input type="hidden" name="evaluation_dt_id[]"
                                                   value="{{$evaluation_file_d->evaluationTypeDt->evaluation_dt_id}}">

                                            <input type="hidden" name="evaluation_id[]"
                                                   value="{{$evaluation_file_d->evaluationTypeDt->evaluation_id}}">
                                            <tr>
                                                <td>{{$k+1}}</td>
                                                <td>
                                                    <span>
                                                            {{$evaluation_file_d->evaluationTypeDt->evaluation_dt_name_ar}}
                                                        </span>
                                                </td>
                                                <td>
                                                    <div class="row gutters-lg">

                                                        <div class="col-md-4">
                                                            <label class="colorinput">
                                                                <input name="evaluation_result[]" type="checkbox"
                                                                       value="1" disabled
                                                                       @if($evaluation_file_d->evaluation_result == 1) checked
                                                                       @endif
                                                                       class="colorinput-input"/>
                                                                <span class="colorinput-color"
                                                                      style="background-color:#00cc00 !important;"></span>

                                                            </label>

                                                            <p class="font-15 bold d-inline-block"> جيد</p>

                                                        </div>

                                                        <div class="col-md-4">
                                                            <label class="colorinput">
                                                                <input name="evaluation_result[]" type="checkbox"
                                                                       value="0" disabled
                                                                       @if($evaluation_file_d->evaluation_result == 0) checked
                                                                       @endif
                                                                       class="colorinput-input"/>
                                                                <span class="colorinput-color"
                                                                      style="background-color:#8b0000 !important;"></span>
                                                            </label>

                                                            <p class="font-15 bold d-inline-block"> غير جيد</p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td><textarea class="form-control" name="evaluation_notes[]" readonly>
                                                        {{$evaluation_file_d->evaluation_notes}}</textarea>
                                                </td>
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

        </div>
    </div>

@endsection