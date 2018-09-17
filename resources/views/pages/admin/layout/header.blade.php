<nav class="navbar navbar-expand navbar-dark bg-dark static-top">
    <a class="navbar-brand mr-1" href="{!! \URL::action('Admin\IndexController@index') !!}">Admin</a>

    <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <span class="badge badge-danger">{{$unreadNotificationCount}}</span>

            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">{{'You have '.$unreadNotificationCount.' messages'}}</h6>
                <h6 class="dropdown-header">System Messages</h6>

                    @foreach($notifications as $notification)
                        <a class="dropdown-item" href="{!! action('Admin\AdminUserNotificationController@show', $notification->id) !!}" style="white-space: inherit;">
                            <p>{{ substr($notification->content, 0, 180) }}@if( strlen($notification->content) > 180 )...@endif</p>
                        </a>
                    @endforeach
            </div>
        </li>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user-circle fa-fw"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <h6 class="dropdown-header">Name: @if($authUser->name) {{ $authUser->name }} @else {{ $authUser->email }}@endif</h6>
                <h6 class="dropdown-header">Role: @if( count($authUser->roles) ) {{ $authUser->roles[0]->getRoleName() }} @endif</h6>
                <a class="dropdown-item" href="{{ action('Admin\MeController@index') }}">Profile</a>
                <div class="dropdown-divider"></div>
                <form id="signout" method="post" action="{!! URL::action('Admin\AuthController@postSignOut') !!}">{!! csrf_field() !!}</form>
                <a class="dropdown-item" href="#" class="btn btn-default btn-flat" onclick="$('#signout').submit(); return false;">Sign out</a>
            </div>
        </li>
    </ul>

</nav>