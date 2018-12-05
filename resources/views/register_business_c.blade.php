@extends('layouts.lone_form')

@section('style')

    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">

@endsection

@section('form')

    <p class="register-box-msg">Select the plan you wish to use.</p>

    <form action="{{url('registerBusiness')}}" method="post">
        <div class="form-group">
            @foreach($plans as $plan)
            <div class="radio">
                <label>
                    <input type="radio" name="pay_plan" id="optionsRadios1" value="{{$plan->id}}" checked>
                    <b>{{$plan->name}}</b><br>
                    <p>{{$plan->description}}</p>
                    <span class="text-muted">Price: {{$plan->price}} {{$plan->periodic_time}}</span><br>
                </label>
            </div>
            @endforeach
        </div>
        <input name="reg_user_id" type="hidden" value="{{$user}}">
        <input name="reg_user_pwd" type="hidden" value="{{$pwd}}">
        <input name="business" type="hidden" value="{{$business}}">
        <input name="stage" type="hidden" value="c">
        {{csrf_field()}}
        <div class="row">
            <div class="col-xs-8">

            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Finish</button>
            </div>
            <!-- /.col -->
        </div>
    </form>

@endsection

