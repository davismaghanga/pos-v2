@extends('layouts.basic_content')

@section('content')

    <div class="error-page">
        <h2 class="headline text-red">403</h2>

        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Sorry! You are not allowed to be here.</h3>

            <p>
                Please <a href="{{url(session('returnUrl'))}}">return to dashboard</a>
            </p>
        </div>
    </div>
    <!-- /.error-page -->

@endsection