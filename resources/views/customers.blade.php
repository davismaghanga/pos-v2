@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">


@endsection

@section('title')

    <h1>
        Customers
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Customers</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-3">
                        <form action="{{url('customers/search')}}" method="post" onsubmit="return getSearch()">
                            <div class="form-group has-feedback">
                                <div class="input-group" >
                                    <input name="search" type="search"  placeholder="Search Products" class="form-control" value="{{old('search')}}" required>
                                    <span class="input-group-btn">
                                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i> </button>
                                </span>
                                </div>
                                {{csrf_field()}}
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <a href="{{url('customers/exportexcel')}}" class="btn btn-primary btn-block btn-flat">Export contacts</a>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary btn-block btn-flat" onclick="newCustomer()">Add a customer</button>
                    </div>
                    <div class="col-md-3">
                        <form id="excel-import-form" action="{{url('customers/upload-excel')}}" enctype="multipart/form-data" method="post">
                            <div class="btn btn-primary btn-block btn-flat btn-file" >
                                <i class="fa fa-paperclip"></i> Import from Excel
                                <input id="excel-input" name="excel" type="file" accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            </div>
                            <span class="help-block" style="display: @if($errors->has('excel')) block @else gone @endif">{{$errors->first('excel')}}</span>
                            {{csrf_field()}}
                        </form>
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
                        <table id="customers_table" class="table table-responsive table-bordered">
                            <thead>
                                <tr>
                                    <th width="100px">#</th>
                                    <th>Full Names</th>
                                    <th>Phone</th>
                                    <th>Balance</th>
                                    <th>Loyalty Points</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            </tbody>



                            <?php $count = 1; ?>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{$count}}</td>
                                    <td class="mailbox-name">{{$customer->name}}</td>
                                    <td class="mailbox-subject">{{$customer->phone}}</td>
                                    <td class="mailbox-date">{{$customer->balance}}</td>
                                    <td class="mailbox-date">{{$customer->loyalty_points}}</td>
                                    <td>
                                        <div class="btn-group btn-flat">
                                            <a href="{{url('customers/delete/' . $customer->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-trash-o"></i></a>
                                            <a onclick="onEditAction('{{$customer->name}}','{{$customer->phone}}', '{{$customer->id}}')" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
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

    <!-- new customer dialog -->
    <div id="newCustomer" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Register customer</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('customers/create')}}" method="post">
                        <input type="hidden" name="id" id="customer_id" value="{{old('id')}}">
                        <div class="form-group has-feedback">
                            <input name="name" id="customer_name" type="text" class="form-control" placeholder="Customer name" value="{{old('name')}}" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif">
                            <input name="phone" id="customer_phone" type="text" class="form-control" placeholder="Phone" value="{{old('phone')}}" required>
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            <span class="help-block">{{$errors->first('phone')}}</span>
                        </div>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add Customer</button>
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

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

    <script>
        $(function () {
            $("#customers_table").DataTable();
        });


    </script>

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

            $("#excel-input").change(function (e) {
                $('#excel-import-form').submit();
            });

            @if(session('createCustomer'))
                $("#newCustomer").modal('show');
            @endif
        });
    </script>

    <script>

        function newCustomer() {

            $('#customer_id').val('');
            $('#customer_name').val('');
            $('#customer_phone').val('');
            $('#newCustomer').modal('show');

        }
        function onEditAction(name,number,id) {
            $('#customer_id').val(id);
            $('#customer_name').val(name);
            $('#customer_phone').val(number);
            $('#newCustomer').modal('show');
        }
        
        function getSearch() {
            
        }
    </script>

@endsection