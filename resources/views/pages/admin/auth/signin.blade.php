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
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }
        .form-signin .checkbox {
            font-weight: 400;
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
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
@stop

@section('scripts')
@stop

@section('title')
    Sign In
@stop

@section('header')
    Sign In
@stop

@section('content')
    <div id="login" class="container" style="margin-top:90px">
        <div class="row">
            <div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                <div class="card text-center">
                    <form class="form-signin" action="{!! action('Admin\AuthController@postSignIn') !!}" method="post">
                        {!! csrf_field() !!}
                        <fieldset>
                            <img class="mb-4" src="{{ \URLHelper::asset('img/user_avatar.png', 'common') }}" alt="" width="72" height="72">
                            <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
                            <label for="inputEmail" class="sr-only">Email address</label>
                            <input id="inputEmail" type="email" name="email" class="form-control" placeholder="Email" required autofocus>
                            <label for="inputPassword" class="sr-only">Password</label>
                            <input id="inputPassword" type="password" name="password" class="form-control" placeholder="Password" required>

                            <div class="checkbox mb-3">
                                <label>
                                    <input id="remember_me" type="checkbox" name="remember_me" value="1"> @lang('admin.pages.auth.messages.remember_me')

                                </label>
                            </div>
                            <button class="btn btn-lg btn-primary btn-block" value="Sign in" type="submit">Sign in</button>
                            <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y") ?></p>

                            <div class="form-group">
                                <div class="col-md-12 control" style="padding: 0; margin-top: 15px;">
                                    <div style="border-top: 1px solid#888; padding-top:10px; font-size:12px;" >
                                        Forgot your password!
                                        <a href="{!! action('Admin\PasswordController@getForgotPassword') !!}">Click here</a><br>
                                    </div>
                                </div>
                            </div>

                        </fieldset>
                    </form>
            </div>
        </div>
    </div>
@stop
