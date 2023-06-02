@extends('Layouts.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('themes/default/style.min.css') }}"/>
@endsection

@section('content')

    <div class="section-body mt-3" id="app">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body pt-5 pb-5">
                            {{--الشركات--}}
                            <form action="">
                                <div class="row align-items-end mb-4">

                                    

                                    <div class="col-md-3">
                                        <label>@lang('home.companies')</label>
                                        <select class="form-control" name="company_id" v-model="company_id">
                                            <option value="">@lang('home.choose')</option>
                                            @foreach($companies as $company)
                                                <option value="{{$company->company_id}}" @if(request()->company_id)
                                                @if($company->company_id == request()->company_id) selected @endif @endif>
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$company->company_name_ar}}
                                                    @else
                                                        {{$company->company_name_en}}
                                                    @endif
                                                </option>

                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary w-100">@lang('home.filter')</button>
                                    </div>

                                    

                                    <div class="col-md-3">
                                        <a href="{{ route('accounts.create').'?account_id=0' }}"
                                           class="btn btn-primary w-100">@lang('home.add_account')</a>
                                    </div>
                                    <div class="col-md-3">
                                  
                                        <a href="{{config('app.telerik_server')}}?rpt={{$company_group->report_url_tree->report_url}}&company_id={{$company_group->company_group_id}}&lang=ar&skinName=bootstrap"
                                           class="btn btn-primary w-100" target="_blank">@lang('home.print')</a>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                {{-- tree --}}
                                <div class="col-md-4">
                                <div class="">
                                        @lang('home.accounts_tree')
                                    </div>
                                    <div id="jstree">
                                        <!-- in this example the tree is populated from inline HTML -->
                                        <ul>

                                            {{--filter--}}
                                            @if(request()->company_id)
                                                @foreach($accounts as $account)
                                                    <li data-jstree='{"icon":"fa fa-tree font-green-jungle","type":"{{($account->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                        data-id="{{$account->acc_id}}">{{ $account->acc_code  }}
                                                        - {{ app()->getLocale()=='ar' ? $account->acc_name_ar :
                                 $account->acc_name_en }}

                                                        {{-- level 2 --}}
                                                        <ul>
                                                            @if(count($account->childrenAccounts->where('company_id',request()->company_id)) > 0)
                                                                @foreach($account->childrenAccounts->where('company_id',request()->company_id) as $child1)
                                                                    <li data-jstree='{"icon":"fa fa-server font-red","type":"{{($child1->accountChildren->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                        data-id="{{$child1->accountChildren->acc_id}}">{{ $child1->accountChildren->acc_code  }}
                                                                        - {{app()->getLocale()=='ar' ?
                                                        $child1->accountChildren->acc_name_ar
                                                        : $child1->accountChildren->acc_name_en }}

                                                                        {{--level 3 --}}
                                                                        <ul>
                                                                            @if(count($child1->accountChildren->childrenAccounts->where('company_id',request()->company_id)) > 0)
                                                                                @foreach($child1->accountChildren->childrenAccounts->where('company_id',request()->company_id) as $child2)

                                                                                    <li data-jstree='{"icon":"fa fa-briefcase font-grey-mint","type":"{{($child2->accountChildren->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                        data-id="{{$child2->accountChildren->acc_id}}">

                                                                                        {{ $child2->accountChildren->acc_code  }}
                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                $child2->accountChildren->acc_name_ar
                                                                                : $child2->accountChildren->acc_name_en }}

                                                                                        {{-- level 4 --}}
                                                                                        <ul>
                                                                                            @if(count($child2->accountChildren->childrenAccounts->where('company_id',request()->company_id)) > 0)
                                                                                                @foreach($child2->accountChildren->childrenAccounts->where('company_id',request()->company_id) as $child3)

                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child3->accountChildren->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                        data-id="{{$child3->accountChildren->acc_id}}">
                                                                                                        {{ $child3->accountChildren->acc_code  }}
                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                            $child3->accountChildren->acc_name_ar
                                                                                            : $child3->accountChildren->acc_name_en }}

                                                                                                        {{-- level 5--}}
                                                                                                        <ul>
                                                                                                            @if(count($child3->accountChildren->childrenAccounts->where('company_id',request()->company_id)) > 0)
                                                                                                                @foreach($child3->accountChildren->childrenAccounts->where('company_id',request()->company_id) as $child4)

                                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child4->accountChildren->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                                        data-id="{{$child4->accountChildren->acc_id}}">
                                                                                                                        {{ $child4->accountChildren->acc_code  }}
                                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                                                        $child4->accountChildren->acc_name_ar
                                                                                                                        : $child4->accountChildren->acc_name_en }}

                                                                                                                        {{--level 6--}}
                                                                                                                        <ul>
                                                                                                                            @if(count($child4->accountChildren->childrenAccounts->where('company_id',request()->company_id)) > 0)
                                                                                                                                @foreach($child4->accountChildren->childrenAccounts->where('company_id',request()->company_id) as $child5)

                                                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child5->accountChildren->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                                                        data-id="{{$child5->accountChildren->acc_id}}">
                                                                                                                                        {{ $child5->accountChildren->acc_code  }}
                                                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                                                                            $child5->accountChildren->acc_name_ar
                                                                                                                                            : $child5->accountChildren->acc_name_en }}
                                                                                                                                    </li>

                                                                                                                                @endforeach
                                                                                                                            @endif
                                                                                                                        </ul>

                                                                                                                    </li>

                                                                                                                @endforeach
                                                                                                            @endif
                                                                                                        </ul>

                                                                                                    </li>

                                                                                                @endforeach
                                                                                            @endif
                                                                                        </ul>

                                                                                    </li>

                                                                                @endforeach
                                                                            @endif
                                                                        </ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </li>
                                                @endforeach
                                                {{--end filter--}}
                                            @else
                                                @foreach($accounts as $account)
                                                    <li data-jstree='{"icon":"fa fa-tree font-green-jungle","type":"{{($account->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                        data-id="{{$account->acc_id}}">{{ $account->acc_code  }}
                                                        - {{ app()->getLocale()=='ar' ? $account->acc_name_ar :
                                                         $account->acc_name_en }}

                                                        {{--level 2--}}
                                                        <ul>
                                                            @if(count($account->accounts) > 0)
                                                                @foreach($account->accounts as $child1)
                                                                    <li data-jstree='{"icon":"fa fa-server font-red","type":"{{($child1->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                        data-id="{{$child1->acc_id}}">{{ $child1->acc_code  }}
                                                                        - {{app()->getLocale()=='ar' ?
                                                        $child1->acc_name_ar
                                                        : $child1->acc_name_en }}

                                                                        {{-- level 3 --}}
                                                                        <ul>
                                                                            @if(count($child1->accounts) > 0)
                                                                                @foreach($child1->accounts as $child2)

                                                                                    <li data-jstree='{"icon":"fa fa-briefcase font-grey-mint","type":"{{($child2->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                        data-id="{{$child2->acc_id}}">
                                                                                        {{ $child2->acc_code  }}
                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                $child2->acc_name_ar
                                                                                : $child2->acc_name_en }}

                                                                                        {{-- level 4 --}}
                                                                                        <ul>
                                                                                            @if(count($child2->accounts) > 0)
                                                                                                @foreach($child2->accounts as $child3)

                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child3->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                        data-id="{{$child3->acc_id}}">
                                                                                                        {{ $child3->acc_code  }}
                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                            $child3->acc_name_ar
                                                                                            : $child3->acc_name_en }}

                                                                                                        {{-- level 5 --}}
                                                                                                        <ul>
                                                                                                            @if(count($child3->accounts) > 0)
                                                                                                                @foreach($child3->accounts as $child4)

                                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child4->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                                        data-id="{{$child4->acc_id}}">
                                                                                                                        {{ $child4->acc_code  }}
                                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                                                            $child4->acc_name_ar
                                                                                                                            : $child4->acc_name_en }}

                                                                                                                        {{-- level 6--}}
                                                                                                                        <ul>
                                                                                                                            @if(count($child4->accounts) > 0)
                                                                                                                                @foreach($child4->accounts as $child5)

                                                                                                                                    <li data-jstree='{"icon":"fa fa-folder-open","type":"{{($child5->acc_level < $level) ? 'child' : 'leaf'}}"}'
                                                                                                                                        data-id="{{$child5->acc_id}}">
                                                                                                                                        {{ $child5->acc_code  }}
                                                                                                                                        - {{app()->getLocale()=='ar' ?
                                                                                                                                        $child5->acc_name_ar
                                                                                                                                        : $child5->acc_name_en }}

                                                                                                                                    </li>

                                                                                                                                @endforeach
                                                                                                                            @endif
                                                                                                                        </ul>


                                                                                                                    </li>

                                                                                                                @endforeach
                                                                                                            @endif
                                                                                                        </ul>

                                                                                                    </li>

                                                                                                @endforeach
                                                                                            @endif
                                                                                        </ul>

                                                                                    </li>

                                                                                @endforeach
                                                                            @endif
                                                                        </ul>
                                                                    </li>
                                                                @endforeach
                                                            @endif
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            @endif

                                        </ul>

                                    </div>
                                </div>
                                {{-- end tree --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color: firebrick">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title" align="center" style="color: whitesmoke;"><i
                                class="fa fa-warning"
                                style="color: yellow;"></i> @lang('home.confirm_delete')</h3>
                </div>
                <div class="modal-body">
                    <b align="center">@lang('home.confirm_delete')</b>
                    <form id="modal_form" action="" method="post">
                        @method('delete')
                        @csrf()

                        <button type="submit" class="btn btn-danger yes">@lang('home.yes')</button>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">@lang('home.no')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/dist/jstree.min.js') }}"></script>
    <script>
        $().ready(function () {

            $('form').submit(function () {
                $('#submit').css('display', 'none')
                $('.spinner-border').css('display', 'block')

            })

            $('#jstree').jstree({
                "plugins": ["contextmenu"],
                "contextmenu": {
                    "items": function ($node) {
                        var type;
                        if ($node.data.jstree) {
                            type = $node.data.jstree.type;
                        } else {
                            type = $node.data.type;
                        }

                        switch (type) {
                            case 'child': {
                                return {
                                    "edit": {
                                        "label": "@lang('home.edit_account')",
                                        'separator_after': false,
                                        "action": function () {
                                            window.location.href = '{{route('accountTree')}}/' + $node.data.id + '/edit';
                                        }
                                    },
                                    "delete": {
                                        "label": "@lang('home.delete_account')",
                                        'separator_after': false,
                                        "action": function () {
                                            $('#myModal').modal('show');
                                            $('#modal_form').attr('action', '{{route('accountTree')}}/' + $node.data.id + '/delete');
                                        },
                                    },
                                    "create": {
                                        "label": "@lang('home.create_account')",
                                        'separator_after': false,
                                        "action": function () {
                                            window.location.href = '{{route('accountTree')}}/create?account_id=' + $node.data.id;
                                        }
                                    }
                                }
                            }
                            case 'leaf': {
                                return {
                                    "edit": {
                                        "label": "@lang('home.edit_account')",
                                        'separator_after': false,
                                        "action": function () {
                                            window.location.href = '{{route('accountTree')}}/' + $node.data.id + '/edit';
                                        }
                                    },
                                    "delete": {
                                        "label": "@lang('home.delete_account')",
                                        'separator_after': false,
                                        "action": function () {
                                            $('#myModal').modal('show');
                                            $('#modal_form').attr('action', '{{route('accountTree')}}/' + $node.data.id + '/delete');
                                        },
                                    },
                                }
                            }
                        }
                    }
                }
            })
        });

    </script>
@endsection
