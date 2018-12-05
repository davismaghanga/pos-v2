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
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <form action="{{url('sReports/find-between-dates')}}" id="find_form" method="post">
                        <input name="start_date" hidden>
                        <input name="end_date" hidden>
                        {{csrf_field()}}
                        <div class="col-md-4 col-sm-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-default btn-block btn-flat" id="daterange-btn">
                                    <span>
                                      <i class="fa fa-calendar"></i> Select a range of dates
                                    </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-6">
                            <div class="form-group">
                                <select name="user" class="form-control select2" style="width: 100%">
                                    <option value="0" selected>All Users</option>
                                    @if(\Illuminate\Support\Facades\Auth::user()->level == 3)
                                        <option value="{{\Illuminate\Support\Facades\Auth::user()->id}}">{{\Illuminate\Support\Facades\Auth::user()->name}}</option>
                                    @else
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 pull-right">
                            <button type="button" id="findBtn" class="btn btn-primary btn-block btn-flat">Find</button>
                        </div>
                    </form>
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
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Cashier</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Balance</th>
                            </tr>
                            @if($sales->isEmpty())
                                <tr>
                                    <th colspan="5">No sales were found for user(s) between these dates.</th>
                                </tr>
                            @endif
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

                            <tr>
                                <th></th>
                                <th>TOTALS</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{$total_sold}}</th>
                                <th>{{$total_paid}}</th>
                                <th>{{$total_balance}}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


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

@endsection

@section('script')
    <!-- InputMask -->
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Page script -->
    <script>
        $(function () {
            var dateStart = moment().format('YYYY-MM-DD');
            var dateEnd = moment().add(1, 'days').format('YYYY-MM-DD');
            //Date range as a button
            $('#daterange-btn').daterangepicker(
                    {
                        ranges: {
                            'Today': [moment(), moment().add(1, 'days')],
                            'Yesterday': [moment().subtract(1, 'days'), moment()],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        },
                        startDate: moment().subtract(29, 'days'),
                        endDate: moment()
                    },
                    function (start, end) {
                        dateStart = start.format('YYYY-MM-DD');
                        dateEnd = end.format('YYYY-MM-DD');
                        $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    }
            );

            $('#findBtn').click(function () {
                $("input[name='start_date']").val(dateStart);
                $("input[name='end_date']").val(dateEnd);
                $('#find_form').submit();
            })
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
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

@endsection