@extends('Layouts.master')

@section('content')

    <div class="section-body mt-3">
        <div class="container-fluid">
            <div class="row clearfix">

                @include('Includes.form-errors')

                <form class="card" action="{{ route('user.branches.update' , $userBranch->branch_id) }}" method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" value="{{$userBranch->user_id}}" name="user_id">
                    <div class="card-header bold"> @lang('home.update_branch') </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="message-text"
                                           class="col-form-label"> @lang('home.branches')</label>
                                    <select class="form-select form-control" name="branch_id"
                                            aria-label="Default select example">

                                        @foreach($branch_all as $branch)
                                            <option value="{{$branch->branch_id}}"
                                                    @if($branch->branch_id == $userBranch->branch_id) selected @endif >
                                                @if(app()->getLocale()== 'ar')
                                                    {{$branch->branch_name_ar}}
                                                @else
                                                    {{$branch->branch_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="message-text"
                                           class="col-form-label"> @lang('home.jobs')</label>
                                    <select class="form-select form-control" name="job_id"
                                            aria-label="Default select example">
                                        @foreach($user->company->jobs as $job)
                                            <option value="{{$job->job_id}}"
                                                    @if($job->job_id == $userBranch->job_id) selected @endif>
                                                @if(app()->getLocale()== 'ar')
                                                    {{$job->job_name_ar}}
                                                @else
                                                    {{$job->job_name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.user_start_date') </label>
                                    <input type="date" class="form-control" name="start_date"
                                           id="recipient-name" value="{{$userBranch->start_date}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.user_end_date') </label>
                                    <input type="date" class="form-control" name="end_date"
                                           id="recipient-name" value="{{$userBranch->end_date}}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.start_time') </label>
                                    <input type="time" class="form-control" name="start_time"
                                           id="recipient-name" value="{{$userBranch->start_time}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="recipient-name"
                                           class="col-form-label"> @lang('home.end_time') </label>
                                    <input type="time" class="form-control" name="end_time"
                                           id="recipient-name" value="{{$userBranch->end_time}}">
                                </div>

                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <label for="message-text"
                                       class="col-form-label"> @lang('home.user_branch_is_defaul')</label>
                                <select class="form-select form-control" name="user_branch_is_defaul"
                                        aria-label="Default select example">
                                    <option value="1" @if($userBranch->user_branch_is_defaul) selected @endif>True
                                    </option>
                                    <option value="0" @if(!$userBranch->user_branch_is_defaul) selected @endif>False
                                    </option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-secondary mr-2">@lang('home.save')</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

