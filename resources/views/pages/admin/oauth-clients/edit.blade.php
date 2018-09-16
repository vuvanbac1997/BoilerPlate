@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'oauth_clients'] )

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
            
        });
    </script>
@stop

@section('title')
@stop

@section('header')
    OauthClients
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active"><a href="{!! action('Admin\OauthClientController@index') !!}"><i class="fa fa-files-o"></i> OauthClients</a></li>
    @if( $isNew )
        <li class="breadcrumb-item active"">New</li>
    @else
        <li class="breadcrumb-item active">{{ $oauthClient->id }}</li>
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

    <form action="@if($isNew) {!! action('Admin\OauthClientController@store') !!} @else {!! action('Admin\OauthClientController@update', [$oauthClient->id]) !!} @endif" method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\OauthClientController@index') !!}" class="btn btn-block btn-secondary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.back')</a>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="@if( $isNew ) col-md-12 @else col-md-6 @endif">
                        <div class="form-group @if ($errors->has('name')) has-error @endif">
                            <label for="name">@lang('admin.pages.oauth-clients.columns.name')</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                   value="{{ old('name') ? old('name') : $oauthClient->name }}">
                        </div>
                    </div>

                    @if( !$isNew )
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="secret">@lang('admin.pages.oauth-clients.columns.secret')</label>
                                <input type="text" class="form-control" id="secret" name="secret" disabled
                                       value="{{ $oauthClient->secret }}">
                            </div>
                        </div>
                    @endif
                </div>
                @if( !$isNew )
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('redirect')) has-error @endif">
                                <label for="redirect">@lang('admin.pages.oauth-clients.columns.redirect')</label>
                            <textarea name="redirect" class="form-control" rows="5" required
                                      placeholder="@lang('admin.pages.oauth-clients.columns.redirect')">{{ old('redirect') ? old('redirect') : $oauthClient->redirect }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('admin.pages.oauth-clients.columns.personal_access_client')</label>
                                <div class="switch">
                                    <input id="personal_access_client" name="personal_access_client" value="1"
                                           @if( $oauthClient->personal_access_client) checked
                                           @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                    <label for="personal_access_client"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('admin.pages.oauth-clients.columns.password_client')</label>
                                <div class="switch">
                                    <input id="password_client" name="password_client" value="1"
                                           @if( $oauthClient->password_client) checked
                                           @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                    <label for="password_client"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('admin.pages.oauth-clients.columns.revoked')</label>
                                <div class="switch">
                                    <input id="revoked" name="revoked" value="1" @if( $oauthClient->revoked) checked
                                           @endif class="cmn-toggle cmn-toggle-round-flat" type="checkbox">
                                    <label for="revoked"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
