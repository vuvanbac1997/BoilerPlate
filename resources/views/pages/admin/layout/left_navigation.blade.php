<ul class="sidebar navbar-nav">
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <li class="nav-item">
        <a class="nav-link" href="{!! \URL::action('Admin\IndexController@index') !!}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item" @if( $menu=='articles') class="active" @endif >
        <a class="nav-link" href="{!! \URL::action('Admin\ArticleController@index') !!}">
            <i class="fa fa-file"></i>
            <span>@lang('admin.menu.articles')</span>
        </a>
    </li>
    @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_ADMIN) )
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>Users Management</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <h6 class="dropdown-header">User Managerment</h6>
            <a class="dropdown-item" href="{!! \URL::action('Admin\AdminUserController@index') !!}"><span>@lang('admin.menu.admin_users')</span></a>
            <a class="dropdown-item" href="{!! \URL::action('Admin\AdminUserNotificationController@index') !!}"><span>@lang('admin.menu.admin_user_notifications')</span></a>
            <a class="dropdown-item" href="{!! \URL::action('Admin\UserController@index') !!}"><span>@lang('admin.menu.users')</span></a>
            <a class="dropdown-item"  href="{!! \URL::action('Admin\UserNotificationController@index') !!}"><span>@lang('admin.menu.user_notifications')</span></a>

        </div>
    </li>
    @endif
    @if( $authUser->hasRole(\App\Models\AdminUserRole::ROLE_SUPER_USER) )
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-fw fa-folder"></i>
            <span>Backend</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
            <h6 class="dropdown-header">Backend</h6>
            <a class="dropdown-item" href="{!! \URL::action('Admin\OauthClientController@index') !!}"><span>OauthClients</span></a>
            <a class="dropdown-item" href="{!! \URL::action('Admin\ImageController@index') !!}"><span>@lang('admin.menu.images')</span></a>
            <a class="dropdown-item" href="{!! \URL::action('Admin\LogController@index') !!}"><span>@lang('admin.menu.log_system')</span></a>
        </div>
    </li>
    @endif

            <li class="nav-item" @if( $menu=='tests') class="active" @endif ><a class="nav-link" href="{!! \URL::action('Admin\TestController@index') !!}"><i class="fa fa-users"></i> <span> @lang('admin.menu.tests') </span></a></li>
            <!-- %%SIDEMENU%% -->
    <!-- /.sidebar -->
</ul>