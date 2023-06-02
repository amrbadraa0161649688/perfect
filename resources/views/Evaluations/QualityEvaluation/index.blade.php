@extends('Layouts.master')

@section('style')
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .bootstrap-select {
            width: 100% !important;
        }

        .fa-stack-1x {
            color: orange !important;
        }

        .fa-stack-0 {
            color: gray !important;
            /*border: 1px solid #000000 !important;*/
        }
    </style>
@endsection
@section('content')
<div class="col-md-12">
                            <div class="row clearfix p-4">
                                <div class="col-md-12">
                                    <div class="font-25" bold>
                                       اداره الجوده
                                    </div>
                                </div>
                            </div>
                        </div>

    <div class="section-body mt-3">

   
        <div class="container-fluid">

            @include('Includes.form-errors')

            <div class="card">
                <div class="row clearfix p-4">
                    <div hidden class="col-lg-4 col-md-4">
                        <input type="text" class="form-control" readonly
                               value="{{app()->getLocale() == 'ar' ? $company->companyGroup->company_group_ar :$company->companyGroup->company_group_en}}">
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <input type="text" class="form-control" readonly
                               value="{{app()->getLocale() == 'ar' ? $company->company_name_ar :$company->company_name_en}}">
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <input type="text" class="form-control" readonly
                               value="{{app()->getLocale() == 'ar' ? session('branch')['branch_name_ar'] : session('branch')['branch_name_en']}}">
                    </div>


                </div>


                <form action="">
                <div class="row clearfix p-4">
                        {{--الفروع--}}
                        <div class="col-md-4">
                            <label class="form-label">المحطه</label>
                            <select class="selectpicker" multiple data-live-search="true"
                                    name="branch_id[]" data-actions-box="true" required>
                                @foreach($branches as $branch)
                                    <option value="{{$branch->branch_id}}"
                                            @if(request()->branch_id) @foreach(request()->branch_id as
                                                     $branch_id) @if($branch_id == $branch->branch_id)
                                            selected @endif @endforeach @endif>
                                        {{app()->getLocale()=='ar' ? $branch->branch_name_ar :
                                        $branch->branch_name_en}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label>@lang('home.created_date_from')</label>
                            <input type="date" class="form-control" name="date_from"
                                   @if(request()->date_from) value="{{request()->date_from}}"
                                    @endif>
                        </div>

                        <div class="col-md-2">
                            <label>@lang('home.created_date_to')</label>
                            <input type="date" class="form-control" name="date_to"
                                   @if(request()->date_to) value="{{request()->date_to}}" @endif>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-primary mt-4"
                                    type="submit">@lang('home.search')
                                <i class="fa fa-search"></i></button>
                        </div>

                    </div>
                </form>
            </div>


            <div class="row clearfix">
                <div class="col-lg-12">
                    <a href="{{route('quality-evaluation.create')}}" class="btn btn-primary">اضافه تقييم</a>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المحطه</th>
                                        <th>الكود</th>
                                        <th>التقييم الكلي</th>
                                        <th>التاريخ</th>
                                        <th>الوقت</th>
                                        <th>القائم بالتفتيش</th>
                                        <th>تفاصيل</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($evaluation_files as $k=>$evaluation_file)
                                        <tr>
                                            <th scope="row">{{$k+1}}</th>
                                            <th>{{app()->getLocale() == 'ar' ? $evaluation_file->branch->branch_name_ar :
                                            $evaluation_file->branch->branch_name_en}}</th>
                                            <td>{{$evaluation_file->evaluation_file_code}}</td>
                                            <td>
                                                @php $rating=$evaluation_file->evaluation_result_total--; @endphp
                                                @foreach(range(1,5) as $i)
                                                    <span class="fa-stack" style="width:1em">
                  <i class="fa fa-star fa-stack-0"></i>

                                                        @if($rating >0)
                                                            @if($rating >0.5)
                                                                <i class="fa fa-star fa-stack-1x"></i>
                                                            @else
                                                                <i class="fa fa-star-half fa-stack-1x"></i>
                                                            @endif
                                                        @endif
                                                        @php $rating--; @endphp
                </span>
                                                @endforeach
                                            </td>
                                            <td>{{$evaluation_file->date}}</td>
                                            <td>{{$evaluation_file->time}}</td>
                                            <td>{{$evaluation_file->user->user_name_ar}}</td>
                                            <td>
                                                <a href="{{route('quality-evaluation.show',$evaluation_file->evaluation_file_id)}}"
                                                   class="btn btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
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

            <div class="row w-100 mt-3">
                <div class="col-12">
                    {{ $evaluation_files->appends($data)->links() }}
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
@endsection