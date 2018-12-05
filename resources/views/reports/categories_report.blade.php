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
        Categories reports
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Categories reports</li>
    </ol>

@endsection
@section('content')
    <div class="box box-default">
        <div class="box-header with-border">

            <form action="{{url('categories_report/filter')}}" id="find_form" method="post">
                <input id="start_date" name="start_date" value="{{old('start_date')}}" hidden>
                <input id="end_date" name="end_date" value="{{old('end_date')}}" hidden>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-group">
                                <select name="type" id="type_to_pass" class="form-control" >
                                    <option readonly>Select An Option</option>
                                    <option value="1"@if(1 == old('type')) selected @endif>Products</option>
                                    <option value="2"@if(2 == old('type')) selected @endif>Services</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <select name="category" id="categorySelect" class="form-control">
                               @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if($category->id == old('category')) selected @endif>{{$category->name}}</option>
                                   @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 pull-right">
                    <button type="button" id="findBtn" class="btn btn-primary btn-block btn-flat" >Find</button>
                </div>

            </form>


        </div>
        <!-- /.box-header -->
        <div class="box-body">


<div class="col-md-offset-2">
           <h3>  {{session('sentence')}}</h3>

</div>
                @if(isset($sales_items))

                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{url('export_category_report')}}" id="excel_form" class="pull-right" method="post">

                            {{csrf_field()}}

                            <input type="hidden" name="start_date" value="{{old('start_date')}}">
                            <input type="hidden" name="end_date" value="{{old('end_date')}}">
                            <input type="hidden" name="type" id="typer" value="{{old('type')}}">
                            <input type="hidden" name="category" id="categoryInput" value="{{old('category')}}">
                            {{--<button data-toggle="tooltip" title="excel" class="btn-xs btn-success" name="excel"><i class="fa fa-file-excel-o"></i>--}}
                                {{--Excel  </button>--}}
                            <button class="btn btn-primary" id="export_button" name="excel">Export To Excel</button>

                            {{--<button data-toggle="tooltip" title="pdf" class="btn-xs btn-danger" name="pdf"><i class="fa fa-file-pdf-o"></i>--}}
                            {{--</button>--}}
                        </form>
                    </div>
                </div>
                <hr>
                    @include('reports.categories_report_table')

                    @endif
        </div>
    </div>
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
            $('#start_date').val(dateStart);
            $('#end_date').val(dateEnd);
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
                        $('#start_date').val(dateStart);
                        $('#end_date').val(dateEnd);
                    }
            );

            $('#findBtn').click(function () {
                $("input[name='start_date']").val(dateStart);
                $("input[name='end_date']").val(dateEnd);
                $('#find_form').submit();
            })
        });
        var type=$('#type_to_pass').val();
        $('#typer').val(type);

        var categoty=$('#categorySelect').val();
        $('#categoryInput').val(categoty);
        $('#export_button').click(function () {
            $('#excel_form').submit();
        })
    </script>

@endsection