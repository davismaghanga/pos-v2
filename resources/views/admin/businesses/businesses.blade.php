@extends('admin.menu')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/datatables/dataTables.bootstrap.css')}}">


@endsection

@section('title')

    <h1>
        Businesses
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('admin/home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Businesses</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <div class="col-md-6">
                        <div class="has-feedback">
                            <input type="text" class="form-control input-sm" placeholder="Search Business">
                            <span class="glyphicon glyphicon-search form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-3 pull-right">
                        <button type="button" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#newUser">Add a new User</button>
                    </div>
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
                                <th>Name</th>
                                <th>Location</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Used sms</th>
                                <th>Actions</th>
                            </tr>
                            @if($businesses->isEmpty())
                                <tr>
                                    <th colspan="5">Sorry. No businesses were found.</th>
                                </tr>
                            @endif
                            @foreach($businesses as $business)
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td class="mailbox-name">{{$business->name}}</td>
                                    <td class="mailbox-subject">{{$business->location}}</td>
                                    <td class="mailbox-subject">{{$business->phone}}</td>
                                    <td class="mailbox-date">{{$business->email}}</td>
                                    <td class="mailbox-date">{{$business->pay_plan}}</td>
                                    <td class="mailbox-date">{{$business->units_consumed}}</td>
                                    <td class="mailbox-date">

                                        <a href="" class="btn btn-primary btn-xs"><i class="fa fa-cog"></i></a>
                                        <a href="javascript:;" onclick="openUnitsChanger({{$business->id}})" class="btn btn-warning btn-xs"><i class="fa fa-commenting-o"></i></a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="smsUnitsModal" class="modal modal-default" role="dialog">
        <div class="modal-dialog">

            <div class="box box-primary">
                <div class="box-header">
                    <div class="box-title">
                        Update Sms
                    </div>
                </div>

                <div class="box-body">

                    <p id="error_feedback" class="text-red"></p>

                    <form method="post" name="units_form" onsubmit="return updateBusinessUnits()">
                        <input type="hidden" id="business_id" name="business_id">
                        <div class="col-xs-8">
                            <input type="number" name="delta_units" class="form-control" placeholder="New units">
                        </div>
                        <div class="col-xs-4">
                            <input type="submit" value="Update" class="btn btn-primary btn btn-block">
                        </div>
                    </form>

                    <p>Updation history</p>

                    <hr>

                    <table class="table" id="delete_history">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Old units</th>
                                <th>Delta</th>
                                <th>New units</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/select2.full.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

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

        window.business_id=0

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

//            initialize datatable with ajax calls
            window.history_table=$('#delete_history').DataTable({
                'ajax':{
                    "url":'{{url('admin/fetch_update_history')}}',
                    "type": "POST",
                    "data":function ( d ) {
                        d.business_id=window.business_id;
                    },

                },
                "columns": [
                    { "data": "id" },
                    { "data": "changer.name" },
                    { "data": "pre_units" },
                    { "data": "delta_units",
                        render: function (data, type, row) {
                            return "<span class='badge bg-gray'>"+data+"</span>";
                        }
                    },
                    { "data": "post_units" }
                ]
            })
        });

        function openUnitsChanger(business_id) {
            $('#smsUnitsModal').modal('show')
            $('#business_id').val(business_id)
            window.business_id=business_id;
            window.history_table.ajax.reload();
        }

        function updateBusinessUnits() {
            var form=document.forms.namedItem('units_form')
            var data=new FormData(form)
            var url='{{url('admin/update_units')}}';
            axios.post(url,data)
                .then(function (res) {
                    if(res.data.success){
                        location.reload();
                    }else{
                        $('#error_feedback').html(res.data.message)
                    }
                })
                .catch(function (res) {
                    console.log(res)
                    $('#error_feedback').html(res)
                })
            return false;
        }
    </script>

@endsection