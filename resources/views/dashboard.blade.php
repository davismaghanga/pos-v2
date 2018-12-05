@extends('menu')

@section('title')

    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li class="active">Dashboard</li>
    </ol>

@endsection

@section('content')

    <?php

    $menuText = array(  'Dashboard', 'Sales', 'Services', 'Products', 'Customers',
            'Suppliers', 'Users', 'Sales Reports', 'Settings');
    $menuUrl = array(   'home', 'sales', 'services', 'products', 'customers',
            'suppliers', 'users', 'sReports', 'settings');
    $menuIcons = array( 'glyphicon-home', 'glyphicon-shopping-cart', 'glyphicon-link', 'glyphicon-gift', 'glyphicon-user',
            'glyphicon-user', 'glyphicon-user', 'glyphicon-file', 'glyphicon-cog');
    $adminMenu = array(0, 1, 2, 3, 4 ,5, 6, 7, 8);
    $managerMenu = array(0, 1, 2, 3, 4 ,5, 6, 7, 8);
    $cashierMenu = array(0, 1, 7);
    $menuTypes = array($adminMenu, $managerMenu, $cashierMenu);

    $user = \Illuminate\Support\Facades\Auth::user();
    $menu = $menuTypes[($user->level - 1)];
    ?>

    <div class="row">

        @for($i = 0; $i < count($menu); $i++)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{url($menuUrl[$menu[$i]])}}" style="color: darkslategray" class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="glyphicon {{$menuIcons[$i]}}"></i></span>

                    <div class="info-box-content"><br>
                        <h4 class="box-title">{{$menuText[$menu[$i]]}}</h4>
                    </div>
                    <!-- /.info-box-content -->
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        @endfor

    </div>
    <!-- /.row -->


@endsection