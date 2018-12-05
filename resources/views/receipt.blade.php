<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Invoice</title>
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <![endif]-->
</head>
<body
@if(isset($print))
    onload="window.print()"
@endif
>
<div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i>{{$business->name}}
                    <small class="pull-right">{{\Carbon\Carbon::now()}}</small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-12 invoice-col">
                <b>Invoice #{{$sale->id}}</b><br>
                To
                <address>
                    <strong>{{$customer->name}}</strong><br>
                </address>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!session('current_receipt') || session('current_receipt') == 0 || $sale_items->isEmpty())
                        <tr>
                            <td class="mailbox-name">Data unavailable</td>
                        </tr>
                    @else
                        @foreach($sale_items as $key=>$sale_item)
                            <tr>
                                <td>{{($key+1)}}</td>
                                <td class="mailbox-name">{{$sale_item->Product->name}}</td>
                                <td class="mailbox-subject">{{$sale_item->Product->Category->name}}</td>
                                <td class="mailbox-date">{{$sale_item->quantity}}</td>
                                <td class="mailbox-date">{{max($sale_item->Product->price, $sale_item->Product->charge) * $sale_item->quantity}}</td>

                            </tr>

                        @endforeach
                            <tr>
                                <th colspan="4"><span class="pull-right">Total</span> </th>
                                <td>{{$sale->amount}}</td>
                            </tr>
                            <tr>
                                <th colspan="4"><span class="pull-right">Balance:</span></th>
                                <td>{{$sale->balance}}</td>
                            </tr>
                            <tr>
                                <th colspan="4"><span class="pull-right">Points earned:</span></th>
                                <td>{{session('points')}}</td>
                            </tr>
                            <tr>
                                <th colspan="4"><span class="pull-right">Dicsount:</span></th>
                                <td>{{$sale->discount}}</td>
                            </tr>
                        @if(session('hasChange'))
                            <tr>
                                <th colspan="4"><span class="pull-right">Return:</span></th>
                                <td>{{session('hasChange')}}</td>
                            </tr>
                        @endif


                    @endif


                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                    </table>
                </div>
                <p class="text-muted no-shadow" style="margin-top: 10px;">

                </p>
                <p class="text-muted no-shadow" style="margin-top: 10px;">
                    Thank you for shopping with Us
                    Contact us: {{$business->phone}}
                </p>
                <p class="text-muted no-shadow" style="margin-top: 10px;">
                    Powered by HightechComputing Ltd
                    Phone: 0724989566
                </p>
            </div>
            <!-- /.col -->
        </div>

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="{{url('receipts/print-out')}}" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                <a href="{{url('receipts/close-out')}}" class="btn btn-default pull-right"><i class="fa fa-print"></i> Close</a>

            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
