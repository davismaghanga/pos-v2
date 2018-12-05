@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">



    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">


@endsection

@section('title')

    <h1>
        Completed orders
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Sales Reports</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="table-responsive mailbox-messages">
                <table id="collectedTable" class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>
                            <button type="button" class="btn btn-default btn-sm btn-flat checkbox-toggle"><i class="fa fa-square-o"></i>
                            </button></th>
                        <th>Invoice No</th>
                        <th>Date</th>
                        <th>Cashier</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Balance</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($sales as $sale)
                        <tr>
                            <td><input type="checkbox"></td>
                            <th>{{$sale->id}}</th>
                            <th>{{$sale->created_at}}</th>
                            <th>{{($sale->User!=null)?$sale->User->name:"undefined"}}</th>
                            @if($sale->customer == 0)
                                <th> - </th>
                            @else
                                <th>{{($sale->Customer!=null)?$sale->Customer->name:"undefined"}}</th>
                            @endif
                            @php($discamt=$sale->amount - $sale->discount + $sale->tax_ex)
                            <td>{{$discamt}}</td>
                            <td>{{$discamt + $sale->balance}}</td>
                            <td>@if($sale->balance!=0)
                                    <a href="{{url('balances')}}" class="btn btn-flat btn-xs btn-success">Pay</a>
                                @endif {{$sale->balance}}</td>
                            <td>

                            <form method="post" action="{{url('orders/collect')}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="sale_id" value="{{$sale->id}}">
                                    <input type="submit" class="btn btn-xs btn-block btn-primary" value="Collect">
                                </form>
                                </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

    <script type="text/javascript">
        $('#collectedTable').DataTable();
    </script>

@endsection
