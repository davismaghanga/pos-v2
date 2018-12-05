@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('plugins/datepicker/datepicker3.css')}}">

@endsection

@section('title')

    <h1>
        Transactions
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Transactions</li>
    </ol>

@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">

            <form action="{{url('rTransactions/filter')}}" id="find_form" method="post">
                <input name="start_date" value="{{old('start_date')}}" hidden>
                <input name="end_date" value="{{old('end_date')}}" hidden>
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
                    <div class="col-sm-4">

                        <div class="form-group">
                            <select id="" name="t_type" class="form-control">
                                <option value="1" @if(old('t_type')==1) selected @endif>Mpesa</option>
                                <option value="0" @if(old('t_type')==0) selected @endif>Visa</option>
                                <option value="" @if(old('t_type')=="" || old('t_type')==null) selected @endif>All</option>
                                <option value="2" @if(old('t_type')==2) selected @endif>Cash</option>
                                <option value="3" @if(old('t_type')==3) selected @endif>Loyalty</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="form-group">
                            <select name="user" class="form-control select2" style="width: 100%">
                                <option value="0" selected>All Users</option>
                                @if(\Illuminate\Support\Facades\Auth::user()->level == 3)
                                    <option value="{{\Illuminate\Support\Facades\Auth::user()->id}}">{{\Illuminate\Support\Facades\Auth::user()->name}}</option>
                                @else
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" @if(old('user')==$user->id) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 pull-right">
                    <button type="button" id="findBtn" class="btn btn-primary btn-block btn-flat">Find</button>
                </div>
            </form>


        </div>
        <!-- /.box-header -->
        <div class="box-body">

            @if(isset($receipts))

                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{url('rTransactions/export')}}" class="pull-right" method="get">

                            {{csrf_field()}}

                            <input type="hidden" name="start_date" value="{{old('start_date')}}">
                            <input type="hidden" name="end_date" value="{{old('end_date')}}">
                            <input type="hidden" name="user" value="{{old('user')}}">
                            <button data-toggle="tooltip" title="excel" class="btn-xs btn-success" name="excel"><i class="fa fa-file-excel-o"></i>
                              Excel  </button>
                            {{--<button data-toggle="tooltip" title="pdf" class="btn-xs btn-danger" name="pdf"><i class="fa fa-file-pdf-o"></i>--}}
                            {{--</button>--}}
                        </form>
                    </div>
                </div>
            <hr>

                    @include('transactions.table')
                @else
                <div class="alert alert-info">
                    Please filter a range
                </div>
            @endif


                </div>
            </div>
        {{--</div>--}}
    {{--</div>--}}

@endsection

@section('script')

    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

    <script>
        $(function () {
            $("#table").DataTable();
        });


    </script>

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

    </script>

@endsection