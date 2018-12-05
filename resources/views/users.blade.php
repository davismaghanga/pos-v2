@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('title')

    <h1>
        Users
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Users</li>
    </ol>

@endsection

@section('content')


    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Users">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newUser">Add a new User</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <div class="btn-group btn-flat">
                            <button type="button" class="btn btn-default btn-flat"><i class="fa fa-trash-o"></i></button>
                            <button type="button" class="btn btn-default btn-flat"><i class="fa fa-edit"></i></button>
                        </div>
                        <!-- /.btn-group -->
                    </div>

                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th width="100px">#</th>
                                <th>Full Names</th>
                                <th>Username</th>
                                <th>Phone</th>
                                <th>Level</th>
                                <th>Action</th>
                            </tr>
                            @if($users->isEmpty())
                                <tr>
                                    <th colspan="5">Sorry. No users were found.</th>
                                </tr>
                            @endif
                            <?php $count = 1; ?>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td class="mailbox-name">{{$user->name}}</td>
                                    <td class="mailbox-subject">{{$user->username}}</td>
                                    <td class="mailbox-subject">{{$user->phone}}</td>
                                    <td class="mailbox-date">{{$user->level == 1 ? "Admin" : $user->level == 2 ? "Manager" : "Cashier"}}</td>
                                    <td><button id="change_password" class="action btn btn-xs btn-flat btn-success" onclick="openModal({{$user->id}})" type="submit">Change Password</button></td>

                                </tr>
                                <?php $count++; ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- new user dialog -->
    <div id="newUser" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Register User</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('users/register')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Full name" value="{{old('name')}}" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif">
                            <input name="phone" type="text" class="form-control" placeholder="Phone" value="{{old('phone')}}" required>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            <span class="help-block">{{$errors->first('phone')}}</span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name="username" type="text" class="form-control" placeholder="Unique username" value="{{old('username')}}" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name="password" type="password" class="form-control" placeholder="Password" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('repassword')) has-error @endif">
                            <input name="repassword" type="password" class="form-control" placeholder="Retype password" required>
                            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            <span class="help-block">{{$errors->first('repassword')}}</span>
                        </div>
                        <div class="form-group has-feedback">
                            <select name="level" class="form-control select2" style="width: 100%" value="{{old('level')}}">
                                <option value="3" selected>Cashier</option>
                                <option value="2">Manager</option>
                                <option value="1">Administrator</option>
                            </select>
                        </div>
                        <input name="business" type="hidden" value="{{$user->business}}">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add User</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- message dialog -->
    <div id="message" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Welcome</h4>
                </div>
                <div class="modal-body">
                    @if(session('message'))
                        <p>{{session('message')}}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- /.password modal -->
    <div id="password_modal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">

                    @if($errors->has('new_password'))
                        <div class="alert alert-danger">
                            {{$errors->first('new_password')}}
                        </div>
                    @endif

                    @if($errors->has('done'))
                        <div class="alert alert-success">
                            {{$errors->first('done')}}
                        </div>
                    @endif


                    <form class="form-horizontal" action="{{url('reset_password')}}" method="post">
                        <input type="hidden" value="{{old('user_id')}}" id="user_id" name="user_id">
                        {{csrf_field()}}
                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">New Password</label>
                            <div class="col-sm-10">
                                <input name="new_password" type="text" class="form-control" placeholder="New Password" required>
                            </div>
                        </div>

                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input name="new_password_confirmation" type="text" class="form-control" placeholder="Confirm Password" required>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <div class="col-sm-4"></div>
                            <div class="form-group has-feedback col-sm-4 " >
                                <label class="col-sm-4 control-label">  SMS Admin  </label>
                                <div class="col-sm-2">
                                    </br>
                                    <input name="admin_send" type="checkbox" class="" >
                                </div>
                            </div>

                            <div class="form-group has-feedback col-sm-4">
                                <label class="col-sm-4 control-label">  SMS User</label>
                                <div class="col-sm-2">
                                    </br>
                                    <input name="user_send" type="checkbox" class="" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <!-- /.modal -->

@endsection

@section('script')

    @if($errors->has('new_password'))
        <script>
            $('#password_modal').modal('show');
        </script>
    @endif

    @if($errors->has('done'))
        <script>
            $('#password_modal').modal('show');
            //            alert('complete')
        </script>
    @endif
    {{--@if($errors->has('sent'))--}}
    {{--<script>--}}
    {{--$('#password_modal').modal('show');--}}
    {{--//            alert('complete')--}}
    {{--</script>--}}
    {{--@endif--}}

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>

    <script>
        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });
    </script>


    <script>

        function openModal(id) {
//            alert(id);
            $('#user_id').val(id);
            $("#password_modal").modal('show');

        }



    </script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            @if(session('register'))
                $("#newUser").modal('show');
            @endif

            @if(session('message'))
                $("#message").modal('show');
            @endif
        });
    </script>

@endsection