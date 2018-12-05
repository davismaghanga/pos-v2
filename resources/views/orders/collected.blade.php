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
        Sales Reports
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
                    </tr>
                    </thead>
                    <tbody>

                    @php($total_balance=0)
                    @php($total_sold=0)
                    @php($total_paid=0)
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
                            <td>{{$sale->balance}}</td>
                        </tr>
                        @php($total_balance+=$sale->balance)
                        @php($total_sold+=$discamt)
                        @php($total_paid+=($discamt + $sale->balance))
                    @endforeach

                    {{--<tr>--}}
                        {{--<th></th>--}}
                        {{--<th>TOTALS</th>--}}
                        {{--<th></th>--}}
                        {{--<th></th>--}}
                        {{--<th></th>--}}
                        {{--<th>{{$total_sold}}</th>--}}
                        {{--<th>{{$total_paid}}</th>--}}
                        {{--<th>{{$total_balance}}</th>--}}
                    {{--</tr>--}}
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
