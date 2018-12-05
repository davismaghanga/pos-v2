@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('title')

    <h1>
        Suppliers
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Suppliers</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Suppliers">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newSupplier">Add a supplier</button>
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
                                <th>
                                    <button type="button" class="btn btn-default btn-sm btn-flat checkbox-toggle"><i class="fa fa-square-o"></i>
                                    </button></th>
                                <th>Name</th>
                                <th>Contact person</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Balance</th>
                            </tr>
                            @if($suppliers->isEmpty())
                                <tr>
                                    <th colspan="5">Sorry. No suppliers were found.</th>
                                </tr>
                            @endif
                            @foreach($suppliers as $supplier)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td class="mailbox-name">{{$supplier->name}}</td>
                                    <td class="mailbox-name">{{$supplier->handler}}</td>
                                    <td class="mailbox-subject">{{$supplier->address}}</td>
                                    <td class="mailbox-subject">{{$supplier->phone}}</td>
                                    <td class="mailbox-date">{{$supplier->balance}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- new supplier dialog -->
    <div id="newSupplier" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Register supplier</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('suppliers/create')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Supplier Name" value="{{old('name')}}" required>
                            <span class="glyphicon glyphicon-home form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name="handler" type="text" class="form-control" placeholder="Contact person name" value="{{old('handler')}}" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <input name="address" type="text" class="form-control" placeholder="Address" value="{{old('address')}}" required>
                            <span class="glyphicon glyphicon-map-marker form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif">
                            <input name="phone" type="text" class="form-control" placeholder="Phone" value="{{old('phone')}}" required>
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            <span class="help-block">{{$errors->first('phone')}}</span>
                        </div>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add Supplier</button>
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
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            @if(session('createSupplier'))
                $("#newSupplier").modal('show');
            @endif
        });
    </script>

@endsection