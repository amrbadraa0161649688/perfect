@extends('Layouts.master')
@section('content')

    <div class="section-body py-4">
        <div class="container-fluid">

            <div class="row mb-3">
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

            <div class="tab-content mt-6">
                {{-- Basic information --}}
                {{--<div class="tab-pane fade show active" id="data-grid" role="tabpanel">--}}
                <div class="card">
                    <div class="section-body">
                        <div class="container-fluid">
                            <div class="d-flex justify-content-between align-items-center">
                                <ul class="nav nav-tabs page-header-tab">
                                    <li class="nav-item">
                                        <a href="#form-grid" data-toggle="tab"
                                           style="font-size: 18px"
                                           class="nav-link active">التقييم</a>
                                    </li>


                                    <li class="nav-item"><a class="nav-link" href="#attachments-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab"> الصور</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#photo-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab"> اضافه صوره</a></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="tab-content mt-3">

                    <div class="tab-pane fade  show active" id="form-grid"
                         role="tabpanel">

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
                                                                            <input name="evaluation_result[]"
                                                                                   type="checkbox"
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
                                                                            <input name="evaluation_result[]"
                                                                                   type="checkbox"
                                                                                   value="0" disabled
                                                                                   @if($evaluation_file_d->evaluation_result == 0) checked
                                                                                   @endif
                                                                                   class="colorinput-input"/>
                                                                            <span class="colorinput-color"
                                                                                  style="background-color:#8b0000 !important;"></span>
                                                                        </label>

                                                                        <p class="font-15 bold d-inline-block"> غير
                                                                            جيد</p>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td><textarea class="form-control" name="evaluation_notes[]"
                                                                          readonly>
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


                    <div class="tab-pane fade" id="attachments-grid" role="tabpanel">

                        <div class="row row-cards">

                            @foreach($attachments as $attachment)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card p-3">
                                        <a href="javascript:void(0)" class="mb-3">
                                            <img class="rounded"
                                                 src="{{ asset('Uploads/'.$attachment->attachment_file_url) }}"
                                                 alt="">
                                        </a>
                                        <div class="d-flex align-items-center px-2">
                                            <img class="avatar avatar-md mr-3"
                                                 src="{{ asset('Uploads/'.$attachment->attachment_file_url) }}"
                                                 alt="">
                                            {{--<div>--}}
                                            {{--<div>Nathan Guerrero</div>--}}
                                            {{--<small class="d-block text-muted">12 days ago</small>--}}
                                            {{--</div>--}}
                                            <div class="ml-auto text-muted">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="tab-pane fade" id="photo-grid" role="tabpanel">

                        <div class="row row-cards">
                            <form class="card" action="{{route('store-photo')}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="evaluation_file_id"
                                       value="{{$evaluation_file->evaluation_file_id}}">
                                <div class="form-group m-2">
                                    <label for="files" class="btn" style="border:1px solid #000000 ; border-radius: 5px;
 padding:10px;display: block">Take Photo</label>
                                    <input id="files" type="file" style="visibility:hidden;" name="image" capture="user"
                                           accept="image/*">
                                </div>

                                <div class="form-group m-2">
                                    <button class="btn btn-info" type="submit">@lang('home.save')</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection
