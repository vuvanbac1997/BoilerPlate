@extends('pages.admin.' . config('view.admin') . '.layout.application',['menu' => 'dashboard'] )

@section('metadata')
@stop

@section('styles')
@stop

@section('scripts')
@stop

@section('title')
    {{ config('site.name') }} | Admin | Update Me
@stop

@section('header')
    Me
@stop

@section('breadcrumb')
    <li class="breadcrumb-item active">Me</li>
@stop

@section('content')
    <form class="form-horizontal" action="{!! action('Admin\MeController@update') !!}" method="post">
        <input type="hidden" name="_method" value="put">
        {!! csrf_field() !!}
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Edit Your Information</h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                               value="{{ $authUser->name }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email Address</label>

                    <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email"
                               value="{{ $authUser->email }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" name="password" class="form-control" id="inputPassword3"
                               placeholder="Password" value="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary float-left">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop
