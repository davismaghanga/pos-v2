@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">


@endsection

@section('title')

    <h1>
        Products
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Products</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <form action="{{url('products/search')}}" method="post">
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
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newProduct">Add a Product</button>
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
                                <th>Code</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th width="120px">Stock Left</th>
                                <th width="150px">Actions</th>
                            </tr>
                            @if($products->isEmpty())
                                <tr>
                                    <th colspan="5">No products were found. Add Products</th>
                                </tr>
                            @endif
                            <?php $count = 1; ?>
                            @foreach($products as $product)
                                <tr>
                                    <td class="mailbox-name">{{$count}}</td>
                                    <td class="mailbox-name">{{$product->code}}</td>
                                    <td class="mailbox-name">{{$product->name}}</td>
                                    <td class="mailbox-name">{{$product->Category->name}}</td>
                                    <td class="mailbox-name">{{$product->price}}</td>
                                    <td class="mailbox-name">
                                        <span>{{$product->stock}}</span>
                                    </td>
                                    <td>
                                        <a onclick="onAddStockAction('{{$product->id}}')" class="action btn btn-xs btn-flat btn-success">Add Stock</a>
                                        <div class="btn-group btn-flat">
                                            <a href="{{url('products/delete/' . $product->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-trash-o"></i></a>
                                            <a onclick="onEditAction('{{$product->name}}', '{{$product->id}}')" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-edit"></i></a>
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

    <!-- new product dialog -->
    <div id="newProduct" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add a product</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('products/create')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Product Name" value="{{old('name')}}" required>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('code')) has-error @endif">
                            <input name="code" type="text" class="form-control" placeholder="Code" value="{{old('code')}}">
                            <span class="help-block">{{$errors->first('code')}}</span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('category')) has-error @endif">
                            <select name="category" class="form-control select2" style="width: 100%" value="{{old('category')}}">
                                <option value="0" disabled selected> --Category -- </option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{{$errors->first('category')}}</span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('price')) has-error @endif">
                            <input name="price" type="text" class="form-control" placeholder="Selling Price" value="{{old('price')}}" required>
                            <span class="help-block">{{$errors->first('price')}}</span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('stock')) has-error @endif">
                            <input name="stock" type="text" class="form-control" placeholder="Quantity" value="{{old('stock')}}" required>
                            <span class="help-block">{{$errors->first('stock')}}</span>
                        </div>
                        <div class="form-group has-feedback">
                            <select name="supplier" class="form-control select2" style="width: 100%" value="{{old('supplier')}}">
                                <option value="0" selected> -- Supplier -- </option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add Product</button>
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

    <!-- edit product dialog -->
    <div id="editProduct" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Edit a product</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" action="{{url('products/edit')}}" method="post">
                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                                <input name="edit_name" type="text" class="form-control" placeholder="Product Name" value="{{old('name')}}" required>
                            </div>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('code')) has-error @endif">
                            <label class="col-sm-2 control-label">Code</label>
                            <div class="col-sm-10">
                                <input name="edit_code" type="text" class="form-control" placeholder="Code" value="{{old('code')}}">
                            </div>
                            <span class="help-block">{{$errors->first('code')}}</span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('category')) has-error @endif">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-10">
                                <select id="edit_category" name="edit_category" class="form-control select2" style="width: 100%" value="{{old('category')}}">
                                    <option value="0" disabled selected> --Category -- </option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="help-block">{{$errors->first('category')}}</span>

                        </div>
                        <div class="form-group has-feedback @if($errors->has('price')) has-error @endif">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-10">
                                <input name="edit_price" type="text" class="form-control" placeholder="Selling Price" value="{{old('price')}}" required>
                            </div>
                            <span class="help-block">{{$errors->first('price')}}</span>
                        </div>
                        <div class="form-group has-feedback @if($errors->has('stock')) has-error @endif">
                            <label class="col-sm-2 control-label">Stock</label>
                            <div class="col-sm-10">
                                <input name="edit_stock" type="text" class="form-control" placeholder="Quantity" value="{{old('stock')}}" required>
                            </div>
                            <span class="help-block">{{$errors->first('stock')}}</span>
                        </div>
                        <div class="form-group has-feedback">
                            <label class="col-sm-2 control-label">Supplier</label>
                            <div class="col-sm-10">
                                <select name="edit_supplier" class="form-control select2" style="width: 100%" value="{{old('supplier')}}">
                                    <option value="0" selected> -- Supplier -- </option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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

                    <form action="{{url('products/create-category')}}" method="post">
                        <div class="form-group has-feedback">
                            <input name="name" type="text" class="form-control" placeholder="Category Name" value="{{old('name')}}" required>
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

    <!-- add stock dialog -->
    <div id="addStock" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Add Stock</h4>
                </div>
                <div class="modal-body">

                    <form action="{{url('products/add-stock')}}" method="post">
                        <div class="form-group has-feedback @if($errors->has('add_stock_quantity')) has-error @endif">
                            <label>Quantity</label>
                            <input name="add_stock_quantity" type="text" class="form-control" placeholder="Enter quantity" value="{{old('add_stock_quantity')}}" required>
                            <span class="help-block">{{$errors->first('add_stock_quantity')}}</span>
                        </div>
                        <input name="add_stock_id" hidden>
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary btn-flat pull-right">Add</button>
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

            @if(session('createProduct'))
                $("#newProduct").modal('show');
            @endif

            @if(session('editProduct'))
                $("#editProduct").modal('show');
            @endif

            @if(session('addStock'))
                $("#addStock").modal('show');
            @endif
        });

        function onAddStockAction(id) {
            $("#addStock").modal('show');
            $("input[name='add_stock_id']").val(id);

        }

        function onEditAction(name, id) {
            $("#editProduct").modal('show');
            $("input[name='edit_name']").val(name);
            $("input[name='edit_id']").val(id);
            $.get('products/product/' + id, function (response) {
                $("input[name='edit_code']").val(response[0]);
                $("select[name='edit_category']").select2("val", "" + response[1]);
                $("input[name='edit_price']").val(response[2]);
                $("input[name='edit_stock']").val(response[3]);
            });
        }
    </script>

@endsection