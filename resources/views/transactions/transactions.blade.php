@extends('menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">

    <link rel="stylesheet" href="{{asset('plugins/autocomplete/easy-autocomplete.themes.min.css')}}">
    <!-- CSS file -->
    <link rel="stylesheet" href="{{asset('plugins/autocomplete/easy-autocomplete.min.css')}}">


@endsection

@section('title')
    <h1>
        Transactions Report
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Transactions Reports</li>
    </ol>

    @endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-12">
                            <form method="post" action="{{url('transactions/filtershit')}}">
                                {{csrf_field()}}

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" type="text" id="search_string" name="search_string" placeholder="phone/name">
                                        <input class="form-control" type="hidden" id="search_id" name="search_id" placeholder="phone/name">
                                    </div>
                                </div>

                                <div class="col-md-4 pull-right">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newUser">search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if(isset($results))
                        <div class="row">
                            <div class="col-md-12">
                                <p>Search results</p>
                                <table id="results_table" class="table table-responsive table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Sale date</th>
                                            <th>Amount</th>
                                            <th>Discount</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($results['sales'] as $sale)
                                            <tr>
                                                <td>{{$results['customer']->name}}</td>
                                                <td>{{$sale->created_at}}</td>
                                                <td>{{number_format($sale->amount,2)}}</td>
                                                <td>{{number_format($sale->discount,2)}}</td>
                                                <td>{{number_format($sale->balance,2)}}</td>
                                            </tr>
                                            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @endif


                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('script')

        <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

        <!-- JS file -->
        <script src="{{asset('plugins/autocomplete/jquery.easy-autocomplete.min.js')}}"></script>

        <script type="text/javascript">
            $('#results_table').DataTable();

            var options = {
                url: "{{url('transactions/suggestions')}}",

                getValue: "name",

                list: {
                    match: {
                        enabled: true
                    },
                    onSelectItemEvent: function() {
                        var value = $("#search_string").getSelectedItemData().id;
                        $("#search_id").val(value).trigger("change");
                    }
                }
            };

            $("#search_string").easyAutocomplete(options);
        </script>

        @endsection

