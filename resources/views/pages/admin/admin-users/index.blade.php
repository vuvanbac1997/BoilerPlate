@extends('pages.admin.' . config('view.admin') . '.layout.application', ['menu' => 'admin_users'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
    <script src="{!! \URLHelper::asset('js/delete_item.js', 'admin/adminlte') !!}"></script>
@stop

@section('title')
@stop

@section('header')
    @lang('admin.breadcrumb.admin_users')
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">@lang('admin.breadcrumb.admin_users')</li>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">

            <div class="row">
                <div class="col-sm-6">
                    <h3 class="box-title">
                        <p class="text-right">
                            <a href="{!! URL::action('Admin\AdminUserController@create') !!}" class="btn btn-block btn-primary btn-sm" style="width: 125px;">@lang('admin.pages.common.buttons.create')</a>
                        </p>
                    </h3>
                    <br>
                    <p style="display: inline-block;">@lang('admin.pages.common.label.search_results', ['count' => $count])</p>
                </div>
                <div class="col-sm-6 wrap-top-pagination">
                    <div class="heading-page-pagination">
                        {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], [], $count, 'shared.topPagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="card-body" style=" overflow-x: scroll; ">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 10px">ID</th>
                            <th>@lang('admin.pages.admin-users.columns.name')</th>
                            <th>@lang('admin.pages.admin-users.columns.email')</th>
                            <th>@lang('admin.pages.admin-users.columns.role')</th>
                            <th>@lang('admin.pages.admin-users.columns.locale')</th>

                            <th style="width: 40px">@lang('admin.pages.common.label.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach( $adminUsers as $adminUser )
                            <tr>
                                <td>{{ $adminUser->id }}</td>
                                <td>{{ $adminUser->name }}</td>
                                <td>{{ $adminUser->email }}</td>
                                <td>@if( count($adminUser->roles) ) {{ $adminUser->roles[0]->getRoleName() }} @endif</td>
                                <td>{{ trans('config.locale.languages.' . $adminUser->locale . '.name') }}</td>

                                <td>
                                    <a href="{!! URL::action('Admin\AdminUserController@show', $adminUser->id) !!}" class="btn btn-block btn-primary btn-xs">@lang('admin.pages.common.buttons.edit')</a>
                                    @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_ADMIN) && !$adminUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
                                        <a href="#" class="btn btn-block btn-danger btn-xs delete-button" data-delete-url="{!! URL::action('Admin\AdminUserController@destroy', [$adminUser->id]) !!}">@lang('admin.pages.common.buttons.delete')</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer">
            {!! \PaginationHelper::render($paginate['order'], $paginate['direction'], $paginate['offset'], $paginate['limit'], $count, $paginate['baseUrl'], []) !!}
        </div>
    </div>
@stop