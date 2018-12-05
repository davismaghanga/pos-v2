@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('title')

    <h1>
        Services
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Services</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <form action="{{url('services/search')}}" method="post">
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
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newService">Add a Service</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newCategory">Add a Category</button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                            @if($services->isEmpty())
                                <tr>
                                    <th colspan="5">No services were found. Add Services</th>
                                </tr>
                            @endif
                            <?php $count = 1; ?>
                            @foreach($services as $service)
                                <tr>
                                    <td class="mailbox-name">{{$count}}</td>
                                    <td class="mailbox-name">{{$service->name}}</td>
                                    <td class="mailbox-name">{{$service->Category->name}}</td>
                                    <td class="mailbox-name">{{$service->stock}}</td>
                                    <td class="mailbox-name">{{$service->charge}}</td>
                                    <td width="100px">
                                        <div class="btn-group btn-flat">
                                            <a href="{{url('services/delete/' . $service->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-trash-o"></i></a>
                                            <a onclick="onEditAction('{{$service->name}}', '{{$service->id}}')" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-edit"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php $count++ ?>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- new service dialog -->
    <div id="newService" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add a Service</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('services/create')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Service Name" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group has-feedback">
                            <select name="category" class="form-control select2" style="width: 100%" value="{{old('category')}}">
                                <option value="NULL" disabled selected> --Category -- </option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('charge')) has-error @endif">
                            <input name="charge" type="text" class="form-control" placeholder="Price" value="{{old('charge')}}" required>
                            <span class="help-block">{{$errors->first('charge')}}</span>
                        </div>

                        <div class="form-group has-feedback @if($errors->has('stock')) has-error @endif">
                            <input name="stock" type="text" class="form-control" placeholder="Qty" value="{{old('stock')}}" required>
                            <span class="help-block">{{$errors->first('stock')}}</span>
                        </div>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add Service</button>
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

    <!-- edit service dialog -->
    <div id="editService" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Edit Service</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="{{url('services/edit')}}" method="post">
                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                            <input name="edit_name" type="text" class="form-control" placeholder="Service Name" value="{{old('edit_name')}}" required>
                                </div>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                            <select name="edit_category" id="edit_category" class="form-control select2" style="width: 100%" value="{{old('edit_category')}}" required>
                                <option value="NULL" disabled selected> --Category -- </option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                                </div>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('edit_charge')) has-error @endif">
                            <label class="col-sm-2 control-label">Charge</label>
                            <div class="col-sm-10">
                            <input name="edit_charge" type="text" class="form-control" placeholder="Price" value="{{old('edit_charge')}}" required>
                                </div>
                            <span class="help-block">{{$errors->first('edit_charge')}}</span>
                        </div>

                        <div class="form-group has-feedback @if($errors->has('edit_stock')) has-error @endif">
                            <label class="col-sm-2 control-label">Stock</label>
                            <div class="col-sm-10">
                                <input name="edit_stock" type="text" class="form-control" placeholder="Qty" value="{{old('edit_stock')}}" required>
                            </div>
                            <span class="help-block">{{$errors->first('edit_stock')}}</span>
                        </div>


                        <input name="edit_id" hidden>
                        {{csrf_field()}}
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

    <!-- new category dialog -->
    <div id="newCategory" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add a category</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('services/create-category')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Category Name" value="{{old('name')}}" required>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add Category</button>
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

            @if(session('createCategory'))
                $("#newCategory").modal('show');
            @endif
            @if(session('createService'))
                $("#newService").modal('show');
            @endif

            @if(session('editService'))
                $("#editService").modal('show');
            @endif
        });

        function onEditAction(name, id) {
            $("#editService").modal('show');
            $("input[name='edit_name']").val(name);
            $("input[name='edit_id']").val(id);
            $.get('services/service/' + id, function (response) {
                $("#edit_category").select2().val(""+response[0]).trigger("change");
                $("input[name='edit_charge']").val(response[1]);
                $("input[name='edit_stock']").val(response[2]);
            });
        }
    </script>

@endsection