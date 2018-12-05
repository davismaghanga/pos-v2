<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>VPS POS | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>VPS</b>POS</a>
    </div>
    <!-- /.login-logo -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="@if(!session('register')) active @endif">
                <a href="#login" data-toggle="tab">Login</a>
            </li>
            <li class="@if(session('register')) active @endif">
                <a href="#register" data-toggle="tab">Register</a>
            </li>
            <li class="pull-right"><a href="#" class="text-muted"> <span class="glyphicon glyphicon-question-sign"></span></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane @if(!session('register')) active @endif" id="login">
                <p class="login-box-msg">Sign in to start your session</p>
                @if(session('loginError'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{session('loginError')}}
                    </div>
                @endif
                <form action="{{url('verifyLogin')}}" method="post">
                    <div class="form-group has-feedback">
                        <input name="login_business_code" type="text" class="form-control" placeholder="Business Code" required>
                        <span class="glyphicon glyphicon-home form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="login_username" type="text" class="form-control" placeholder="Username" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="login_user_password" type="password" class="form-control" placeholder="Password" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    {{csrf_field()}}
                    <div class="row">
                        <div class="col-xs-8">

                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <div class="tab-pane @if(session('register')) active @endif" id="register">
                <p class="login-box-msg">Register a new business</p>
                <form action="{{url('registerBusiness')}}" method="post">
                    <div class="form-group has-feedback">
                        <input name="name" type="text" class="form-control" placeholder="Business name" value="{{old('name')}}" required>
                        <span class="glyphicon glyphicon-home form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif ">
                        <input name="phone" type="text" class="form-control" placeholder="Phone number" value="{{old('phone')}}" required>
                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                        <span class="help-block">{{$errors->first('phone')}}</span>
                    </div>
                    <div class="form-group has-feedback @if($errors->has('email')) has-error @endif">
                        <input name="email" type="text" class="form-control" placeholder="Email" value="{{old('email')}}" required>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        <span class="help-block">{{$errors->first('email')}}</span>
                    </div>
                    <div class="form-group has-feedback">
                        <input name="location" type="text" class="form-control" placeholder="Location" value="{{old('location')}}" required>
                        <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
                    </div>
                    <input type="hidden" name="stage" value="a">
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
            </div>
        </div>
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>
