@extends('Layouts.master')
@section('content')
    <div class="section-body py-3" id="app">
        <div class="container-fluid">

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
                                           class="nav-link active">@lang('home.data')</a>
                                    </li>

                                    <li class="nav-item"><a class="nav-link" href="#files-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab">@lang('home.files')</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#notes-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab">@lang('home.notes')</a></li>

                                    <li class="nav-item"><a class="nav-link" href="#photos-grid"
                                                            style="font-size: 18px"
                                                            data-toggle="tab">@lang('home.photos')</a></li>
                                </ul>
                                <div class="header-action"></div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--</div>--}}

                <div class="tab-content mt-3">
                    {{-- Form To Create Waybill--}}
                    <div class="tab-pane fade show active" id="form-grid"
                         role="tabpanel">
                        <form class="card">
                            <div class="card-body">
                                <h3 class="card-title">{{__('truck connect')}}</h3>
                                <div class="row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('company group')}}</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            $truck_to_trailer_hd->companyGroup->company_group_ar :  $truck_to_trailer_hd->companyGroup->company_group_en}}"
                                                   readonly>
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('company')}}</label>
                                            <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            $truck_to_trailer_hd->company->company_name_ar :   $truck_to_trailer_hd->company->company_name_en}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    @if( $truck_to_trailer_hd->user)
                                        <div class="col-sm-6 col-md-3">
                                            <div class="form-group">
                                                <label class="form-label">{{__('user')}}</label>

                                                <input type="text" class="form-control" value="{{app()->getLocale() == 'ar' ?
                            $truck_to_trailer_hd->user->user_name_ar :  $truck_to_trailer_hd->user->user_name_en}}"
                                                       readonly>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('date')}}</label>
                                            <input type="text" class="form-control"
                                                   value="{{$truck_to_trailer_hd->transaction_date}}"
                                                   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label">{{__('truck')}}</label>
                                            <input class="form-control" type="text" readonly
                                                   value="{{$truck_to_trailer_hd->truck->truck_name}}
                                                           ==> {{$truck_to_trailer_hd->truck->truck_plate_no}}">
                                        </div>
                                    </div>


                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">{{__('trailer')}}</label>
                                            <input type="text" class="form-control" readonly
                                                   @if($truck_to_trailer_hd->trailer)   value="{{$truck_to_trailer_hd->trailer->asset_name_ar}}
                                                           ==> {{$truck_to_trailer_hd->trailer->asset_serial}}" @endif>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">{{__('driver')}}</label>
                                            <input type="text" class="form-control" readonly
                                                   value="{{app()->getLocale() == 'ar' ? $truck_to_trailer_hd->driver->emp_name_full_ar
                                               : $truck_to_trailer_hd->driver->emp_name_full_en}}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="form-label">{{__('notes')}}</label>
                                            <textarea rows="5" class="form-control" readonly
                                                      name="notes_hd">{{$truck_to_trailer_hd->notes_hd}} </textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-dark">
                                            <tr>
                                                <th colspan="3">
                                                    {{__('check list')}}
                                                </th>
                                            </tr>

                                            </thead>
                                            <thead>
                                            <tr>
                                                <th class="pl-0">#</th>
                                                <th>{{__('title')}}</th>
                                                <th>{{__('notes')}}</th>
                                            </tr>
                                            </thead>

                                            @foreach($truck_to_trailer_hd->items as $k=>$item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" disabled
                                                                {{$item->check_list_status == 1 ? 'checked' :''}}>
                                                    </td>
                                                    <td>{{app()->getLocale() == 'ar' ?
                                                $item->itemType->system_code_name_ar : $item->itemType->system_code_name_en}}</td>
                                                    <td>
                                                    <textarea readonly
                                                              class="form-control">{{$item->check_list_notes_dt ?$item->check_list_notes_dt  : '' }}</textarea>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>


                    {{-- files part --}}
                    <div class="tab-pane fade" id="files-grid"
                         role="tabpanel">
                        <div class="col-lg-12">

                            <x-files.form>
                                <input type="hidden" name="transaction_id"
                                       value="{{ $truck_to_trailer_hd->id }}">
                                <input type="hidden" name="app_menu_id" value="134">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>@lang('home.attachment_type')</label>
                                        <select class="form-control" name="attachment_type" required>
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($attachment_types as $attachment_type)
                                                <option value="{{ $attachment_type->system_code_id }}">{{app()->getLocale()=='ar' ? $attachment_type->system_code_name_ar
                                                     : $attachment_type->system_code_name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </x-files.form>

                            <x-files.attachment>

                                @foreach($attachments as $attachment)
                                    <tr>
                                        <td>
                                            @if($attachment->attachmentType_2)
                                                {{ app()->getLocale()=='ar' ?
                                         $attachment->attachmentType_2->system_code_name_ar :
                                          $attachment->attachmentType_2->system_code_name_en}}
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->issue_date )) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($attachment->expire_date )) }}</td>
                                        <td>{{ $attachment->issue_date_hijri }}</td>
                                        <td>{{ $attachment->expire_date_hijri }}</td>
                                        <td>{{ $attachment->copy_no }}</td>
                                        <td>
                                            <a href="{{ url('/attachments/download-pdf?name=' . $attachment->attachment_file_url) }}">
                                                <i class="fa fa-download fa-2x"></i></a>
                                            <a href="{{ asset('Files/'.$attachment->attachment_file_url) }}"
                                               target="_blank" class="mr-1 ml-1"><i class="fa fa-eye text-info"
                                                                                    style="font-size:20px"></i></a>
                                        </td>
                                        <td>
                                            {{ $attachment->attachment_data }}
                                        </td>
                                        <td>{{ $attachment->userCreated->user_name_ar }}</td>
                                        <td>{{ $attachment->created_at }}</td>
                                    </tr>
                                @endforeach

                            </x-files.attachment>

                        </div>
                    </div>
                    {{--end files part--}}


                    <div class="tab-pane fade" id="notes-grid"
                         role="tabpanel">
                        <div class="row">
                            <div class="col-lg-12">
                                <x-files.form-notes>
                                    <input type="hidden" name="transaction_id"
                                           value="{{ $truck_to_trailer_hd->id }}">
                                    <input type="hidden" name="app_menu_id" value="134">
                                </x-files.form-notes>

                                <x-files.notes>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td> {{ $note->notes_data }}</td>
                                            <td>{{ date('d-m-Y', strtotime($note->notes_date )) }}</td>
                                            <td>{{ $note->user->user_name_ar }}</td>
                                            <td>{{ $note->notes_serial }}</td>
                                        </tr>
                                    @endforeach
                                </x-files.notes>
                            </div>
                        </div>
                    </div>

                    {{--   take photos  --}}
                    <div class="tab-pane fade" id="photos-grid" role="tabpanel">
                        <div class="card-body">
                            <div class="row row-cards">

                                @foreach($photos_attachments as $photo_attachment)
                                    <div class="col-sm-6 col-lg-4">
                                        <div class="card p-3">
                                            <a href="javascript:void(0)" class="mb-3">
                                                <img class="rounded"
                                                     src="{{ asset('Files/'.$photo_attachment->attachment_file_url) }}"
                                                     alt="">
                                            </a>
                                            <div class="d-flex align-items-center px-2">
                                                <img class="avatar avatar-md mr-3"
                                                     src="{{ asset('Files/'.$photo_attachment->attachment_file_url) }}"
                                                     alt="">
                                                <div>
                                                    <div>{{$photo_attachment->userCreated->user_name_ar}}</div>
                                                    <small class="d-block text-muted">{{$photo_attachment->issue_date}}</small>
                                                </div>
                                                <div class="ml-auto text-muted">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>

                        </div>
                    </div>

                </div>
            </div>


            <div class="row clearfix">
                <div class="col-lg-12">


                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        $(document).ready(function () {
            $('#add_files').click(function () {
                var display = $("#add_files_form").css("display");
                if (display == 'none') {
                    $('#add_files_form').css('display', 'block')
                } else {
                    $('#add_files_form').css('display', 'none')
                }

            });

            $('#add_note').click(function () {
                var display = $("#add_note_form").css("display");
                if (display == 'none') {
                    $('#add_note_form').css('display', 'block')
                } else {
                    $('#add_note_form').css('display', 'none')
                }
            });
        })

    </script>


    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                expire_date: '',
                issue_date: '',
                issue_date_hijri: '',
                expire_date_hijri: '',
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
                getExpireDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.expire_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.expire_date_hijri = response.data
                    })
                },
                getIssueDate() {
                    $.ajax({
                        type: 'GET',
                        data: {date: this.issue_date},
                        url: '{{ route("api.getDate") }}'
                    }).then(response => {
                        this.issue_date_hijri = response.data
                    })
                },
            }
        })
    </script>
@endsection
