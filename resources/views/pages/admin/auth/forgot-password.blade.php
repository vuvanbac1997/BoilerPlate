@extends('pages.admin.' . config('view.admin') . '.layout.application', ['noFrame' => true, 'bodyClasses' => 'hold-transition login-page'])

@section('metadata')
@stop

@section('styles')
    <style>
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }
        .form-signin .form-control:focus {
            z-index: 2;
        }
        .form-signin input[type="email"] {
            margin-bottom: 10px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
@stop

@section('scripts')
@stop

@section('title')
@stop

@section('header')
Forgot Password
@stop

@section('content')
    <body class="login-page">
        <div id="login" class="container" style="margin-top:90px">
            <div class="row">
                <div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                    <div class="card text-center">
                        <div class="card-header bg-primary">
                            <a style="color: #000000;  text-decoration: none;" href="{!! action('Web\IndexController@index') !!}"><b>{{ config('site.name') }}</b> Admin</a>
                        </div>
                        <div class="card-body">
                            <form class="form-signin" action="{!! URL::action('Admin\PasswordController@postForgotPassword') !!}" method="post">
                                {!! csrf_field() !!}

                                <h1 class="h3 mb-3 font-weight-normal">@lang('admin.pages.auth.messages.forgot_password')</h1>
                                <label for="inputEmail" class="sr-only">Email address</label>
                                <input id="inputEmail" type="email" name="email" class="form-control" placeholder="Email"/>

                                <button class="btn btn-lg btn-primary btn-block" value="Sign in" type="submit">@lang('admin.pages.auth.buttons.forgot_password')</button>
                            </form>
                        </div>

                    </div><!-- /.login-box-body -->
                </div><!-- /.login-box -->
            </div>
        </div>
    </body>

@stop
