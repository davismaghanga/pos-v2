@extends('layouts.lone_form')

@section('form')

    <p class="register-box-msg">Add an administrator for the business</p>

    <form action="{{url('registerBusiness')}}" method="post">
        <div class="form-group has-feedback">
            <input name="name" type="text" class="form-control" placeholder="Full name">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input name="phone" type="text" class="form-control" placeholder="Phone">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input name="username" type="text" class="form-control" placeholder="Unique username">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input name="password" type="password" class="form-control" placeholder="Password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input name="repassword" type="password" class="form-control" placeholder="Retype password">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        <input name="business" type="hidden" value="{{$business}}">

        <input name="stage" type="hidden" value="b">
        {{csrf_field()}}
        <div class="row">
            <div class="col-xs-8">

            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Continue</button>
            </div>
            <!-- /.col -->
        </div>
    </form>


@endsection