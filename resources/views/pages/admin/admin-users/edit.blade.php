@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'admin_users'] )

@section('metadata')
@stop

@section('styles')
    <style>
        .button-checkbox button {
            padding: 3px 20px;
        }
    </style>
@stop

@section('scripts')
    <script src="{{ \URLHelper::asset('libs/moment/moment.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('libs/datetimepicker/js/bootstrap-datetimepicker.min.js', 'admin') }}"></script>
    <script src="{{ \URLHelper::asset('js/jquery_checkbox_btn.js', 'admin/adminlte') }}"></script>
    <script>
        $('.datetime-field').datetimepicker({'format': 'YYYY-MM-DD HH:mm:ss'});

        $(document).ready(function () {
            $('#profile-image').change(function (event) {
                $('#profile-image-preview').attr('src', URL.createObjectURL(event.target.files[0]));
            });
        });
    </script>
@stop

@section('title')
@stop

@section('header')
    AdminUsers
@stop

@section('breadcrumb')
    <li class="breadcrumb-item"><a  href="{!! action('Admin\AdminUserController@index') !!}"><i class="fa fa-files-o"></i> AdminUsers</a></li>
    @if( $isNew )
        <li class="breadcrumb-item">New</li>
    @else
        <li class="breadcrumb-item active">{{ $adminUser->id }}</li>
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

    <form action="@if($isNew) {!! action('Admin\AdminUserController@store') !!} @else {!! action('Admin\AdminUserController@update', [$adminUser->id]) !!} @endif" method="POST" enctype="multipart/form-data">
        @if( !$isNew ) <input type="hidden" name="_method" value="PUT"> @endif
        {!! csrf_field() !!}

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <a href="{!! URL::action('Admin\AdminUserController@index') !!}" class="btn btn-block btn-secondary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.back')</a>
                </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="form-group text-center">
                            @if( !empty($adminUser->present()->profileImage()) )
                                <img id="profile-image-preview" style="max-width: 500px; width: 100%;" src="{!! $adminUser->present()->profileImage()->present()->url !!}" alt="" class="margin"/>
                            @else
                                <img id="profile-image-preview" style="max-width: 500px; width: 100%;" src="{!! \URLHelper::asset('img/no_image.jpg', 'common') !!}" alt="" class="margin"/>
                            @endif
                            <input type="file" style="display: none;" id="profile-image" name="profile_image">
                            <p class="help-block" style="font-weight: bolder;">
                                @lang('admin.pages.admin-users.columns.profile_image_id')
                                <label for="profile-image" style="font-weight: 100; color: #549cca; margin-left: 10px; cursor: pointer;">@lang('admin.pages.common.buttons.edit')</label>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <table class="edit-user-profile">
                            <tr class="@if ($errors->has('name')) has-error @endif">
                                <td>
                                    <label for="name">@lang('admin.pages.admin-users.columns.name')</label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" id="name" name="name" required value="{{ old('name') ? old('name') : $adminUser->name }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('email')) has-error @endif">
                                <td>
                                    <label for="email">@lang('admin.pages.admin-users.columns.email')</label>
                                </td>
                                <td>
                                    <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') ? old('email') : $adminUser->email }}">
                                </td>
                            </tr>

                            <tr class="@if ($errors->has('password')) has-error @endif">
                                <td>
                                    <label for="password">@lang('admin.pages.users.columns.password')</label>
                                </td>
                                <td>
                                    <input type="password" class="form-control" id="password" name="password" required @if(!$isNew) disabled @endif value="{{ old('password') ? old('password') : $adminUser->password }}">
                                </td>
                            </tr>

                            @if($isNew)
                                <tr class="@if ($errors->has('re_password')) has-error @endif">
                                    <td>
                                        <label for="re_password">@lang('admin.pages.admin-users.columns.re_password')</label>
                                    </td>
                                    <td>
                                        <input type="password" class="form-control" id="re_password" name="re_password" required value="{{ old('password') ? old('password') : '' }}">
                                    </td>
                                </tr>
                            @endif

                            <tr class="@if ($errors->has('locale')) has-error @endif">
                                <td>
                                    <label for="locale">@lang('admin.pages.admin-users.columns.locale')</label>
                                </td>
                                <td>
                                    <select class="form-control" name="locale" id="locale" style="margin-bottom: 15px;" required>
                                        <option value="">@lang('admin.pages.common.label.select_locale')</option>
                                        @foreach( config('locale.languages') as $code => $locale )
                                            <option value="{!! $code !!}" @if( (old('locale') && old('locale') == $code) || ( $adminUser->locale === $code) ) selected @endif >
                                                {{ trans($locale['name']) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="role">@lang('admin.pages.admin-users.columns.permissions')</label>
                                </td>
                                <td>
                                    @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                                        <span class="button-checkbox">
                                            <button type="button" class="btn btn-xs" data-color="primary">@lang('admin.roles.super_user')</button>
                                            <input type="checkbox" name="role[]" value="{{ \App\Models\AdminUserRole::ROLE_SUPER_USER }}" class="hidden" @if( $adminUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER, false) ) checked @endif />
                                        </span>
                                    @endif

                                    @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_ADMIN) )
                                        <span class="button-checkbox" style="margin: 0 10px;">
                                            <button type="button" class="btn btn-xs" data-color="info">@lang('admin.roles.admin')</button>
                                            <input type="checkbox" name="role[]" value="{{ \App\Models\AdminUserRole::ROLE_ADMIN }}" class="hidden" @if( $adminUser->hasRole(\App\Models\AdminUserRole::ROLE_ADMIN, false) ) checked @endif/>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.save')</button>
            </div>
        </div>
    </form>
@stop
