@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

@endsection

@section('title')

    <h1>
        Sales
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Sales</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <form action="{{url('sales/add-product')}}" method="post">
                        <div class="col-md-6">
                            <div class="form-group has-feedback">
                                <select name="product" class="form-control select2" style="width: 100%">
                                    <option value="NULL" disabled selected> -- Select a Product -- </option>
                                    @foreach($products as $product)
                                        <option value="{{$product->id . '-product'}}">{{$product->name}}</option>
                                    @endforeach
                                    @foreach($services as $service)
                                        <option value="{{$service->id . '-service'}}">{{$service->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group has-feedback">
                                <input name="quantity" type="number" class="form-control" placeholder="Quantity" value="1">
                            </div>
                        </div>
                        <div class="col-md-3 pull-right">
                            <button type="submit" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newUser">Add Product</button>
                        </div>
                        {{csrf_field()}}
                    </form>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">

                    <div class="table-responsive mailbox-messages">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th width="100px">#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th width="100px">Price</th>
                                <th width="100px">Quantity</th>
                                <th width="100px">Amount</th>
                                <th width="100px">Actions</th>
                            </tr>
                            <?php
                            $sub_total = 0;
                            $discount = 0;
                            $tax = 0;
                            ?>
                            @if(!session('current_sale_id') || session('current_sale_id') == 0 || $sale_items->isEmpty())
                                <tr>
                                    <td class="mailbox-name">Start a sale</td>
                                </tr>
                            @else
                                <?php $count = 1; ?>
                                @foreach($sale_items as $sale_item)
                                    <tr>
                                        <td>{{$count}}</td>
                                        <td class="mailbox-name">{{$sale_item->Product->name}}</td>
                                        <td class="mailbox-subject">{{$sale_item->Product->Category->name}}</td>
                                        <td class="mailbox-subject">{{max($sale_item->Product->price, $sale_item->Product->charge)}}</td>
                                        <td class="mailbox-date">{{$sale_item->quantity}}</td>
                                        <td class="mailbox-date">{{max($sale_item->Product->price, $sale_item->Product->charge) * $sale_item->quantity}}</td>
                                        <td width="100px">
                                            <a href="{{url('sales/delete-entry/' . $sale_item->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-trash-o"></i></a>
                                            <div class="btn-group btn-flat">
                                                <a href="{{url('sales/increase-entry/' . $sale_item->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-plus"></i></a>
                                                <a href="{{url('sales/reduce-entry/' . $sale_item->id)}}" type="button" class="btn btn-default btn-xs btn-flat"><i class="fa fa-minus"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php $count++ ?>
                                @endforeach
                                <?php
                                $sale = \App\Sale::find(session('current_sale_id'));
                                $sub_total = $sale->amount;
                                $discount = $sale->discount;
                                $tax_in = $sale->tax_in;
                                $tax_ex = $sale->tax_ex;
                                ?>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('current_sale_id') && session('current_sale_id') != 0 && !$sale_items->isEmpty())
        <div class="box box-widget">
            <div class="row">
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <span class="description-text">SUBTOTAL</span>
                        <h5 class="description-header">{{$sub_total}}</h5>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <span class="description-text">DISCOUNT</span>
                        <h5 class="description-header">{{$discount}}</h5>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3 border-right">
                    <div class="description-block">
                        <span class="description-text">TAX</span>
                        <h5 class="description-header">{{$tax_in + $tax_ex}}</h5>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-3">
                    <div class="description-block">
                        <span class="description-text">TOTAL</span>
                        <h5 class="description-header">{{$sub_total - $discount + $tax_ex}}</h5>
                    </div>
                    <!-- /.description-block -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.box -->
    @endif

    <div class="row">
        {{csrf_field()}}
        <div class="col-md-3">
            <button onclick="cancelItemsForm()" class="btn btn-default btn-block btn-flat">Cancel</button>
            @if(session('current_receipt')!="")
                <button onclick="getReceiptView()" class="btn btn-default btn-block btn-flat">Show Previous receipit</button>

            @endif
        </div>
        <div class="col-md-3 pull-right">
            <button onclick="setCustomer()" class="btn btn-success btn-block btn-flat">Complete Sale</button>
        </div>
    </div>


    <div id="receiptview" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-body" style="width:100%">

                @if(session('current_receipt')!="")
                    @include('receipt')

                @else
                @endif

                </div>
            </div>
        </div>



    <!-- setCustomer dialog -->
    <div id="setCustomer" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Pay</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form action="{{url('sales/set-customer')}}" method="post">
                                <div class="col-sm-4">

                                        <div class="alert alert-info">
                                           <span style="font-size: 30px"> <i class="fa fa-info"></i></span>
                                            Sheduled operation! Please set the number of hours the order will take to process

                                        </div>

                                         <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="control-label">Hours</label>
                                                         <input type="number" name="hours" value="0" required class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label for="" class="control-label">Minutes</label>
                                                        <input type="number" name="minutes" value="15" required class="form-control">
                                                    </div>
                                                </div>


                                </div>
                                <div class="col-sm-8">
                                    @if(session('receiptNotFound'))
                                        <div class="alert alert-danger alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                            {{session('receiptNotFound')}}
                                        </div>
                                    @endif



                                    <div class="form-group has-feedback @if($errors->has('phone')) has-error @endif">
                                        <input id="phone" name="phone" type="text" class="form-control" placeholder="Phone" value="{{old('phone')}}" required>
                                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                        <span class="help-block">{{$errors->first('phone')}}</span>
                                    </div>


                                    <div class="form-group has-feedback @if($errors->has('name')) has-error @endif">
                                        <input id="name" name="name" type="text" class="form-control" placeholder="Customer Name" value="{{old('name')}}" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                        <span class="help-block">{{$errors->first('name')}}</span>
                                        <span id="name_error" class="help-block" style="display: none; color: indianred; font-weight: bold">Customer not found. Provide a name</span>

                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('channel')) has-error @endif">
                                        <select id="channel" name="channel" class="form-control select2" style="width: 100%" value="{{old('channel')}}">
                                            <option value="3" selected>Loyalty</option>
                                            <option value="2" selected>Cash</option>
                                            <option value="1">M-Pesa</option>
                                            <option value="0">Visa</option>
                                        </select>
                                        <span class="help-block">{{$errors->first('channel')}}</span>
                                    </div>

                                    <input type="hidden" value="{{old('t_id')}}" id="t_id" name="t_id">

                                    <div class="form-group has-feedback @if($errors->has('amount')) has-error @endif" id="amount" style="display: block">
                                        <input  name="amount" id="amount_field" type="text" class="form-control" placeholder="Amount Paid" value="{{old('amount')}}" required>
                                        <span class="glyphicon glyphicon-euro form-control-feedback"></span>
                                        <span class="help-block">{{$errors->first('amount')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('code')) has-error @endif" id="mpesa_code" style="display: none">
                                        <input  name="mpesa_code" type="text" class="form-control" placeholder="Mpesa Code" value="{{old('mpesa_code')}}">
                                        <span class="glyphicon glyphicon-euro form-control-feedback"></span>
                                        <span class="help-block">{{$errors->first('code')}}</span>
                                    </div>
                                    <div class="form-group has-feedback @if($errors->has('visa_code')) has-error @endif" id="visa_code" style="display: none">
                                        <input  name="visa_code" type="text" class="form-control" placeholder="Visa Code" value="{{old('visa_code')}}">
                                        <span class="glyphicon glyphicon-euro form-control-feedback"></span>
                                        <span class="help-block">{{$errors->first('visa_code')}}</span>
                                    </div>
                                    {{csrf_field()}}
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary btn-flat pull-right">Complete Sale</button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- stockError dialog -->
    <div id="stockError" class="modal modal-warning" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Warning</h4>
                </div>
                <div class="modal-body">
                    @if(session('stockNotEnough'))
                        <p>{{session('stockNotEnough')}}</p>
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

    <!-- hasChange dialog -->
    <div id="hasChange" class="modal modal-default" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change</h4>
                </div>
                <div class="modal-body">
                    @if(session('hasChange'))
                        <h3>{{session('hasChange')}}</h3>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!--mpesa fetch transaction modal--->
    <div id="mpesaModal" class="modal modal-default" role="dialog">
        <div class="modal-dialog">

            <div class="modal-content">

                <div class="box box-primary">
                    <div class="box-header">
                        <h4 class="box-title">Mpesa checker</h4>
                    </div>
                </div>

                <div class="box-body">

                    <div class="form-group">
                        <label for="">Phone number</label>
                        <input type="text" class="form-control" id="mpesa-number-input">
                    </div>

                    <div class="form-group">
                        <table class="table table-condensed" id="t_table" style="display: none">
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>KYC Name</th>
                                <th>Phone Number</th>
                                <th>Transaction Code</th>
                                <th style="width: 40px">Amount</th>
                            </tr>
                            <tr>
                                <td>1.</td>
                                <td id="t_name"></td>
                                <td id="t_phone"></td>
                                <td id="t_code"></td>
                                <td ><span class="badge bg-red" id="t_amount" ></span></td>
                            </tr>

                        </table>

                        <div class="alert alert-danger" style="display: none;" id="t_error">
                            No transaction data found
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary pull-right" onclick="check()">Check</button>
                        <button class="btn btn-success pull-right" style="display:none;" id="t_apply" onclick="apply()">Apply</button>
                    </div>
                </div>

            </div>

            </div>
        </div>

    {{--Loyalty points modal--}}

    <div id="loyaltyModal" class="modal modal-default" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="box-title">Loyalty points processor</div>
                    </div>

                    <div class="box-body">

                        <div class="alert alert-default" id="loyalty_alert">
                           <p id="loyalty_feedback">
                               Loading customer information...
                           </p>
                        </div>

                        <div id="loyalty_form">

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
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
        });
        function setCustomer() {
            @if($sub_total > 1)
                $("#setCustomer").modal('show');
            @endif
        }

        function getReceiptView() {
                $("#receiptview").modal('show');
        }

        @if(session('current_receipt')!="")
            getReceiptView();
        @endif

        function cancelItemsForm() {
            location.href = '{{url('sales/cancel')}}';
        }
        function confirmCashReceipt() {
            location.href = '{{url('sales/confirm-cash')}}';
        }

        $(function () {
            @if(session('setCustomer'))
                $("#setCustomer").modal('show');
            @endif

            @if(session('stockNotEnough'))
                $("#stockError").modal('show');
            @endif

            @if(session('hasChange'))
                $("#hasChange").modal('show');
            @endif
        });

        var phone_input = $("input[name='phone']");
        phone_input.data('oldVal', phone_input.val());
        phone_input.bind("propertychange change click keyup input paste", function () {
            if (phone_input.data('oldVal') != phone_input.val()) {
                phone_input.data('oldVal', phone_input.val());
                if (phone_input.data('oldVal').length == 10) {
                    $.get('sales/user-name/' + phone_input.data('oldVal'), function (response) {
                        if (response[0] == 0){
                            $('#name_error').css('display', 'none');
                            $("input[name='name']").val(response[1]);
                        }else if (response[0] == 1){
                            $('#name_error').css('display', 'block');
                            $("input[name='name']").val("");
                        }
                    });
                }
            }
        });

        $(':checkbox').change(function () {
            alert('heh');
        })

        function updateLoyaltyMode(points_worth) {
            var _typed_amount=$('#loyalty_amount').val();
            if(points_worth<_typed_amount){
                alert('Loyalty points cannot cover this amount')
            }else{
                $("input[name='amount']").val(_typed_amount);
                $('#loyaltyModal').modal('toggle')
            }
        }

        function loadCustomerPoints() {
            var url='load_customer_loyalty';
            axios.post(url,{'number':$('#phone').val()})
                .then(function (res) {
                    if(res.data.success){

                        if ($('#loyalty_alert').hasClass('alert-default'))
                            $('#loyalty_alert').removeClass("alert-default")
                        if ($('#loyalty_alert').hasClass('alert-danger'))
                            $('#loyalty_alert').removeClass("alert-danger")
                        $('#loyalty_alert').addClass("alert-success")
                        $('#loyalty_feedback').html(res.data.message)

                        //put loyalty redeemable loyalty points
                        $('#loyalty_form').html("<p>Loyalty points:"+res.data.customer.loyalty_points +" Points to 1 sh:"+res.data.business.loyalty_redeem_rate+ " Points worth "+res.data.points_worth+"</p>" +
                            "<p>" +
                            "<input type='number' class='form-control' id='loyalty_amount'></br> " +
                            "<a href='javascript:;' onclick='updateLoyaltyMode("+res.data.points_worth +")' class='btn btn-primary pull-right'>Proceed</a></p>" +
                            "")


                    }else{

                        $('#loyalty_form').html('')
                        if ($('#loyalty_alert').hasClass('alert-default'))
                            $('#loyalty_alert').removeClass("alert-default")
                        if ($('#loyalty_alert').hasClass('alert-success'))
                            $('#loyalty_alert').removeClass("alert-success")

                        $('#loyalty_alert').addClass("alert-danger");
                        $('#loyalty_feedback').html(res.data.message);

                        if(res.data.customer!=null){
                            $('#loyalty_feedback').append("</br> Customer name:"+res.data.customer.name+" Phone number:"+res.data.customer.phone+" Loyalty phoints"+res.data.customer.loyalty_points+"");
                        }

                    }
                })

        }

        $('#channel').change(function () {

            var value = $(this).val();

            $('#visa_code').css('display', value == 0 ? 'block' : 'none');
            $("input[name='visa_code']").attr("required", value == 0);
            $('#mpesa_code').css('display', value == 1 ? 'block' : 'none');
            $("input[name='mpesa_code']").attr("required", value == 1);

            if(value==1){
                $('#mpesaModal').modal('show');
            }else if(value==3){
                $('#loyaltyModal').modal('show');
                loadCustomerPoints();
            }
            else{
                $('#t_id').val();
            }
        })

        function check(){
            window.url='{{url('mpesa/fetch-last-transaction')}}';
            window.mpesaData=null;
            $('#t_apply').hide();
            axios.post(url,{'phone_number':$('#mpesa-number-input').val()})
                    .then(function(res){
                        if(res.data.success){
                            var transaction=res.data.transaction;
                            $('#t_apply').show();
                            $('#t_table').slideDown();
                            $('#t_error').hide();
                            $('#t_amount').text(transaction.trans_amount);
                            $('#t_phone').text(transaction.msisdn);
                            $('#t_name').text(transaction.kyc_name);
                            $('#t_code').text(transaction.trans_id);
                            window.mpesaData=transaction;
                        }else{
                            $('#t_error').fadeIn();
                            $('#t_table').hide();
                            $('#t_id').val('');
                        }
                    })
                    .catch(function (res) {
                        console.log(res);
                    });
        }

        function apply() {
            if(mpesaData!=null){
                $('#t_id').val(mpesaData.id);
                $("input[name='amount']").val(mpesaData.trans_amount);
                $("input[name='mpesa_code']").val(mpesaData.trans_id);
                $('#mpesaModal').modal('hide');
            }
        }
    </script>

@endsection