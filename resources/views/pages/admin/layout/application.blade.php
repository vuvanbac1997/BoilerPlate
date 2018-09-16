<!DOCTYPE html>
<html>
<head>
    <!-------------------------------- Begin: Meta ----------------------------------->
    @include('pages.admin.' . config('view.admin') . '.layout.meta')
    @yield('metadata')
    <!-------------------------------- End: Meta ----------------------------------->

    <!-------------------------------- Begin: stylesheet ----------------------------------->
    @include('pages.admin.' . config('view.admin') . '.layout.styles')
    @yield('styles')
    <!-------------------------------- End: stylesheet ----------------------------------->

</head>
<body  id="page-top">
@if( isset($noFrame) && $noFrame == true )
    @yield('content')
@else
    <div class="body-wrapper">
        <!-------------------------------- Begin: Header ----------------------------------->
        @include('pages.admin.' . config('view.admin') . '.layout.header')
        <!-------------------------------- End: Header ----------------------------------->
        <div id="wrapper">
            <!-------------------------------- Begin: Left Navigation ----------------------------------->
            @include('pages.admin.' . config('view.admin') . '.layout.left_navigation')
            <!-------------------------------- End: Left Navigation ----------------------------------->

            <!-- Content Wrapper. Contains page content -->
            <div id="content-wrapper">
                <div class="container-fluid">
                    <!-- Content Header (Page header) -->
                        <section class="content-header">
                            {{--<h1>--}}
                                {{--@yield('header', 'Dashboard')--}}
                            {{--</h1>--}}
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{!! action('Admin\IndexController@index') !!}">Dashboard</a>
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </section>

                        <!-- Main content -->
                        <section class="content" style="min-height: 650px;">
                            @yield('content')
                        </section>
                        <!-- /.content -->
                </div>

                <!-------------------------------- Begin: Footer ----------------------------------->
                @include('pages.admin.' . config('view.admin') . '.layout.footer')
                <!-------------------------------- End: Footer ----------------------------------->
            </div>
        </div>
    </div>
@endif

<!-------------------------------- Begin: Script ----------------------------------->
@include('pages.admin.' . config('view.admin') . '.layout.scripts')
@yield('scripts')
<!-------------------------------- End: Script ----------------------------------->
</body>
</html>
