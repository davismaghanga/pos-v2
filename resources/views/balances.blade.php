@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
ndsection

@section('title')

    <h1>
        Balances
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Balances</li>
    </ol>

@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">

        </div>
        <!-- /.box-header -->
        <div class="box-body">
            @if($sales->isEmpty())
                <tr>
                    <th colspan="5">Sorry. No balances were found.</th>
                </tr>
            @endif

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="100px">#</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Phone Number</th>
                    <th>Cashier</th>
                    <th>Total</th>
                    <th>Balance</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>


                @php($s_total=0)
                @php($s_balance=0)


                @foreach($sales as $key=>$sale)

                    <tr>
                        <td>{{($key+1)}}</td>
                        <td class="mailbox-name">{{$sale->id}}</td>
                        @if($sale->customer == 0)
                            <th> - </th>
                        @else
                            @if(!is_null($sale->myCustomer))
                                <th>{{$sale->myCustomer->name}}</th>
                            @else
                                <th><span class="label label-success">Not found</span></th>
                            @endif
                        @endif

                        @if($sale->customer == 0)
                            <td> - </td>
                        @else
                            @if(!is_null($sale->myCustomer))
                                <td>{{$sale->myCustomer->phone}}</td>
                            @else
                                <td><span class="label label-success">Not found</span></td>
                            @endif
                        @endif

                        @php($value=$sale->amount - $sale->discount + $sale->tax_ex)
                        @php($s_total+=$value)
                        @php($s_balance+=$sale->balance)

                        <td class="mailbox-date">{{$sale->User->name}}</td>
                        <td class="mailbox-date">{{$value}}</td>
                        <td class="mailbox-date">{{$sale->balance}}</td>
                        <td>
                            <a onclick="onPayAction('{{$sale->id}}')" class="action btn btn-xs btn-flat btn-success">Pay</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th width="100px">#</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{$s_total}}</th>
                    <th>{{abs($s_balance)}}</th>
                    <th></th>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
    </div>
    </div>

    <!-- pay customer dialog -->
    <div id="payBalance" class="modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Pay balance</h4>
                </div>
                <div class="modal-body">
                    @if(session('receiptNotFound'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {{session('receiptNotFound')}}
                        </div>
                    @endif
                    <form action="{{url('balances/pay-balance')}}" method="post">
                        <div class="form-group has-feedback @if($errors->has('channel')) has-error @endif">
                            <select id="channel" name="channel" class="form-control select2" style="width: 100%" value="{{old('channel')}}">
                                <option value="2" selected>Cash</option>
                                <option value="1">M-Pesa</option>
                                <option value="0">Visa</option>
                            </select>
                            <span class="help-block">{{$errors->first('channel')}}</span>
                        </div>

                        <input type="hidden" value="{{old('t_id')}}" id="t_id" name="t_id">

                        <div class="form-group has-feedback @if($errors->has('amount')) has-error @endif" id="amount" style="display: block">
                            <input  name="amount" type="text" class="form-control" placeholder="Amount Paid" value="{{old('amount')}}" required>
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
                        <input name="sale_id" hidden>
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
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal-->

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

@endsection

@section('script')
        <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

    <script>
        $(function () {
            $("#example1").DataTable();
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

                @if(session('payBalance'))
                    $("#payBalance").modal('show');
                @endif

                $('#channel').change(function () {

                    var value = $(this).val();

                    $('#visa_code').css('display', value == 0 ? 'block' : 'none');
                    $("input[name='visa_code']").attr("required", value == 0);
                    $('#mpesa_code').css('display', value == 1 ? 'block' : 'none');
                    $("input[name='mpesa_code']").attr("required", value == 1);

                    if(value==1){
                        $('#mpesaModal').modal('show');
                    }else{
                        $('#t_id').val();
                    }

                })
            });

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

            function onPayAction(id) {
                $("input[name='sale_id']").val(id);
                $("#payBalance").modal('show');
            }

        </script>

@endsection