@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'tests'] )

@section('metadata')
@stop

@section('styles')
    <link rel="stylesheet" href="{!! \URLHelper::asset('libs/datetimepicker/css/bootstrap-datetimepicker.min.css', 'admin') !!}">
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss', 'defaultDate': new Date()});

        $(document).ready(function () {
            $('#cover-image').change(function (event) {
                $('#cover-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
    Test | Admin | {{ config('site.name') }}
@stop

@section('header')
    Test
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{!! action('Admin\TestController@index') !!}"><i class="fa fa-files-o"></i> Test</a></li>
    @if( $isNew )
        <li class="breadcrumb-item">New</li>
    @else
        <li class="breadcrumb-item active">{{ $test->id }}</li>
    @endif
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="@if($isNew) {!! action('Admin\TestController@store') !!} @else {!! action('Admin\TestController@update', [$test->id]) !!} @endif" method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\TestController@index') !!}" class="btn btn-block btn-secondary btn-sm" style="width: 125px;">
                        @lang('admin.pages.common.buttons.back')
                    </a>
                </h3>
            </div>

            <div class="box-body">
                                                            <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">@lang('admin.pages.tests.columns.title')</label>
                                    <input type="text" class="form-control m-input" name="title" id="title" required placeholder="@lang('admin.pages.tests.columns.title')" value="{{ old('title') ? old('title') : $test->title }}">
                                </div>
                            </div>
                        </div>
                                                                                <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">@lang('admin.pages.tests.columns.content')</label>
                                    <input type="text" class="form-control m-input" name="content" id="content" required placeholder="@lang('admin.pages.tests.columns.content')" value="{{ old('content') ? old('content') : $test->content }}">
                                </div>
                            </div>
                        </div>
                                                </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
