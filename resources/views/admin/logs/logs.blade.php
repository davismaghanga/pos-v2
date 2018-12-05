@extends('admin.menu')

@section('title')

    <h1>
        Logs
    </h1>
    <ol class="breadcrumb">
        <li class="active">All logs</li>
    </ol>

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Log entries</h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Type</th>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Trace</th>
                        </tr>
                        @foreach($logs as $log)
                        <tr>
                            <td>
                                @if($log->level=="info")
                                    <span class="label label-info">Info</span>
                                @elseif($log->level=="warning")
                                    <span class="label label-warning">warning</span>
                                @elseif($log->level=="error")
                                    <span class="label label-danger">Error</span>
                                @else
                                    <span class="label label-deafult">Debug</span>
                                @endif

                            </td>
                            <td>{{$log->id}}</td>
                            <td>{{$log->date}}</td>

                            <td>{{str_limit($log->context->message,255,'...')}}</td>
                        </tr>
                       @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection