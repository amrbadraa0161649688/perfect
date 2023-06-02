@extends('Layouts.master')
@section('content')

    <div class="section-body py-3">
        <div class="container-fluid">
            <div class="row clearfix">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" data-whatever="@mdo">
                                    {{__('Add Financial Form Sub Type')}}</button>

                            </h3>

                            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('financial-sub-types.store') }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="recipient-name"
                                                           class="col-form-label">{{__('form type')}}
                                                        :</label>
                                                    <select class="form-control" name="form_type_id" required>
                                                        <option value=""> @lang('home.choose')</option>
                                                        @foreach($form_types as $form_type)
                                                            <option value="{{$form_type->system_code_id}}">
                                                                {{app()->getLocale() == 'ar' ?
                                                            $form_type->system_code_name_ar :
                                                            $form_type->system_code_name_en}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="recipient-name" class="col-form-label">{{__('name ar')}}
                                                        :</label>
                                                    <input type="text" class="form-control" name="form_name_ar"
                                                           required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="recipient-name" class="col-form-label">{{__('name en')}}
                                                        :</label>
                                                    <input type="text" class="form-control" name="form_name_en"
                                                           required>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{__('close')}}
                                                    </button>
                                                    <button type="submit"
                                                            class="btn btn-primary">{{__('save')}}</button>
                                                </div>

                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('name ar')}}</th>
                                        <th>{{__('form type')}}</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($fin_sub_types as $k=>$fin_sub_type)
                                        <tr>
                                            <th scope="row">{{++$k}}</th>
                                            <td>{{app()->getLocale() == 'ar' ?
                                              $fin_sub_type->form_name_ar :$fin_sub_type->form_name_en}}</td>
                                            <td>{{app()->getLocale() == 'ar' ? $fin_sub_type->formType->system_code_name_ar :
                                             $fin_sub_type->formType->system_code_name_en}}</td>
                                            <td>
                                                <i class="btn btn-link fa fa-edit"
                                                   data-toggle="modal"
                                                   data-target="#editModal{{$fin_sub_type->fin_sub_type_id}}"
                                                   data-whatever="@mdo"></i>

                                                <button type="button" class="btn btn-link"
                                                        id="FormSub{{$fin_sub_type->fin_sub_type_id}}"
                                                        onclick="show(this)"><i class="fa fa-eye"></i></button>
                                            </td>
                                            <td>
                                                <form action="{{route('financial-sub-types.delete',$fin_sub_type->fin_sub_type_id)}}"
                                                      method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btn-link" type="submit">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="editModal{{$fin_sub_type->fin_sub_type_id}}"
                                             tabindex="-1" role="dialog"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('financial-sub-types.update',$fin_sub_type->fin_sub_type_id) }}"
                                                              method="post">
                                                            @csrf
                                                            @method('put')
                                                            <div class="form-group">
                                                                <label for="recipient-name"
                                                                       class="col-form-label">{{__('form type')}}
                                                                    :</label>
                                                                <select class="form-control" name="form_type_id"
                                                                        required>
                                                                    <option value=""> @lang('home.choose')</option>
                                                                    @foreach($form_types as $form_type)
                                                                        <option value="{{$form_type->system_code_id}}"
                                                                                @if($fin_sub_type->form_type_id == $form_type->system_code_id)
                                                                                selected @endif>
                                                                            {{app()->getLocale() == 'ar' ?
                                                                        $form_type->system_code_name_ar :
                                                                        $form_type->system_code_name_en}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="recipient-name"
                                                                       class="col-form-label">{{__('name ar')}}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                       name="form_name_ar"
                                                                       required value="{{$fin_sub_type->form_name_ar}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="recipient-name"
                                                                       class="col-form-label">{{__('name en')}}
                                                                    :</label>
                                                                <input type="text" class="form-control"
                                                                       name="form_name_en"
                                                                       required value="{{$fin_sub_type->form_name_en}}">
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">{{__('close')}}
                                                                </button>
                                                                <button type="submit"
                                                                        class="btn btn-primary">{{__('save')}}</button>
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

                        <div>
                            {{--////////////////sub types details table--}}
                            @foreach($fin_sub_types as $k=>$fin_sub_type)

                                <div class="card-body" style="display: none"
                                     id="app-FormSub{{$fin_sub_type->fin_sub_type_id}}">
                                    <h6 style="font-size: 20px">{{$fin_sub_type->form_name_ar}}</h6>
                                    <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#addSubDtModal{{$fin_sub_type->fin_sub_type_id}}"
                                            data-whatever="@mdo">
                                        {{__('Add Financial Sub Detail')}}
                                    </button>


                                    <div class="modal fade" id="addSubDtModal{{$fin_sub_type->fin_sub_type_id}}"
                                         tabindex="-1" role="dialog"
                                         aria-labelledby="addSubDtModal"
                                         aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{app()->getLocale()=='ar' ? $fin_sub_type->form_name_ar
                                                    : $fin_sub_type->form_name_en}}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{route('financial-sub-types-dt.store')}}"
                                                          method="post">
                                                        @csrf
                                                        <input type="hidden" name="fin_sub_type_id"
                                                               value="{{$fin_sub_type->fin_sub_type_id}}">
                                                        <div class="form-group">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">{{__('name ar')}}:</label>
                                                            <input type="text" class="form-control" id="recipient-name"
                                                                   name="form_dt_name_ar" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">{{__('name en')}}:</label>
                                                            <input type="text" class="form-control" id="recipient-name"
                                                                   name="form_dt_name_en" required>
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="recipient-name"
                                                                   class="col-form-label">{{__('sign')}}:</label>
                                                            <select class="form-control" name="sign">
                                                                <option value="">@lang('choose')</option>
                                                                <option value="+">+</option>
                                                                <option value="-">-</option>
                                                            </select>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{__('close')}}
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">{{__('save')}}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="bg-light-blue">
                                            <tr>
                                                <th>#</th>
                                                <th>{{__('name ar')}}</th>
                                                <th>{{__('form type dt')}}</th>
                                                <th>{{__('order')}}</th>
                                                <th>{{__('sign')}}</th>
                                                <th></th>
                                                <th>{{__('Action')}}</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($fin_sub_type->formSubTypeDts as $x=>$fin_sub_type_dt)
                                                <tr>
                                                    <th scope="row">{{++$k}}</th>
                                                    <td>{{app()->getLocale() == 'ar' ?
                                              $fin_sub_type_dt->form_dt_name_ar : $fin_sub_type_dt->form_dt_name_en}}</td>

                                                    <td>
                                                        {{app()->getLocale() == 'ar' ? $fin_sub_type_dt->formSubType->form_name_ar :
                                             $fin_sub_type_dt->formSubType->form_name_en}}
                                                    </td>
                                                    <td>{{$fin_sub_type_dt->acc_order}}</td>
                                                    <td>{{$fin_sub_type_dt->sign}}</td>
                                                    <td>
                                                        <form style="display: inline-block"
                                                              action="{{route('financial-sub-types-dt.addAccOrder')}}"
                                                              method="post">
                                                            @csrf
                                                            <input type="hidden" name="fin_sub_type_dt_id"
                                                                   value="{{$fin_sub_type_dt->fin_sub_type_dt_id}}">
                                                            <input type="hidden" name="order" value="up">
                                                            <button class="btn btn-link" type="submit">
                                                                <i class="fe fe-chevron-up"
                                                                   style="font-weight:bold"></i>
                                                            </button>

                                                        </form>

                                                        <form style="display: inline-block"
                                                              action="{{route('financial-sub-types-dt.addAccOrder')}}"
                                                              method="post">
                                                            @csrf
                                                            <input type="hidden" name="fin_sub_type_dt_id"
                                                                   value="{{$fin_sub_type_dt->fin_sub_type_dt_id}}">
                                                            <input type="hidden" name="order" value="down">
                                                            <button class="btn btn-link" type="submit">
                                                                <i class="fe fe-chevron-down"
                                                                   style="font-weight:bold"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-link"
                                                                data-toggle="modal"
                                                                data-target="#fin-subDt{{$fin_sub_type_dt->fin_sub_type_dt_id}}"
                                                                data-whatever="@mdo">
                                                            <i class="btn btn-link fa fa-edit"></i></button>

                                                        <a href="{{route('financial-account.index',$fin_sub_type_dt->fin_sub_type_dt_id)}}">
                                                            <i class="btn btn-link fa fa-eye"></i></a>

                                                    </td>
                                                    <td>
                                                        <a href="{{route('financial-account.create',$fin_sub_type_dt->fin_sub_type_dt_id)}}"
                                                           class="btn btn-success">{{__('add account')}}</a>
                                                    </td>
                                                    <td>
                                                        <form action="{{route('financial-sub-types-dt.delete',$fin_sub_type_dt->fin_sub_type_dt_id)}}"
                                                              method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <button class="btn btn-link" type="submit">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>


                                                <div class="modal fade"
                                                     id="fin-subDt{{$fin_sub_type_dt->fin_sub_type_dt_id}}"
                                                     tabindex="-1" role="dialog"
                                                     aria-labelledby=""
                                                     aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{app()->getLocale() == 'ar' ?
                                              $fin_sub_type_dt->form_dt_name_ar : $fin_sub_type_dt->form_dt_name_en}}</h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{route('financial-sub-types-dt.update',$fin_sub_type_dt->fin_sub_type_dt_id)}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @method('put')
                                                                    <div class="form-group">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label">{{__('name ar')}}
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               id="recipient-name" required
                                                                               name="form_dt_name_ar"
                                                                               value="{{$fin_sub_type_dt->form_dt_name_ar }}">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label">{{__('name en')}}
                                                                            :</label>
                                                                        <input type="text" class="form-control"
                                                                               id="recipient-name" required
                                                                               name="form_dt_name_en"
                                                                               value="{{$fin_sub_type_dt->form_dt_name_en}}">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="recipient-name"
                                                                               class="col-form-label">{{__('sign')}}
                                                                            :</label>
                                                                        <select class="form-control" name="sign">
                                                                            <option value="">@lang('choose')</option>
                                                                            <option value="+"
                                                                                    @if($fin_sub_type_dt->sign == '+') selected @endif>
                                                                                +
                                                                            </option>
                                                                            <option value="-"
                                                                                    @if($fin_sub_type_dt->sign == '-') selected @endif>
                                                                                -
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                                data-dismiss="modal">{{__('close')}}
                                                                        </button>
                                                                        <button type="submit"
                                                                                class="btn btn-primary">{{__('save')}}
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        //  let searchParams = new URLSearchParams(window.location.search)
        let param = '{{session('FormSubType')}}';

        let searchParams = new URLSearchParams(window.location.search)
        if (searchParams.has('FormSubType')) {
            param = searchParams.get('FormSubType');
        }

        if (param) {
            var y = 'FormSub' + param;
            console.log(y)
            $("#app-" + y).css("display", "block");
            $("#app-" + y).siblings().css('display', 'none')
        }

        function show(el) {
            var x = el.id;
            $("#app-" + x).css("display", "block");
            $("#app-" + x).siblings().css('display', 'none')
        }
    </script>
@endsection