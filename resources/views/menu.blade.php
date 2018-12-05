@extends('layouts.base')

@section('menu')

    <?php

    $menuText = array(  'Dashboard', 'Sales', 'Orders','Services', 'Products', 'Customers',
            'Suppliers', 'Users', 'Balances', 'Sales Reports','Sales Transactions Reports','Transactions Report', 'Settings','Categories Reports');
    $menuChildren=array(11=>[['name'=>'Processing','url'=>'orders/processing','icon'=>'glyphicon-time'],['name'=>'Completed','url'=>'orders/completed','icon'=>'glyphicon-ok'],['name'=>'Collected','url'=>'orders/collected','icon'=>'glyphicon-thumbs-up']]);

    $menuUrl = array(   'home', 'sales','orders', 'services', 'products', 'customers',
            'suppliers', 'users', 'balances', 'sReports','rTransactions','transactions', 'settings','categories_report');
    $menuIcons = array( 'glyphicon-home', 'glyphicon-shopping-cart', 'glyphicon-link', 'glyphicon-gift', 'glyphicon-user',
            'glyphicon-user', 'glyphicon-user', 'glyphicon-euro', 'glyphicon-file', 'glyphicon-cog', 'glyphicon-usd','glyphicon-usd','glyphicon-cog','glyphicon-cog');

    $multilevel=[2];

    $adminMenu = array(0, 1, 2, 3, 4 ,5, 6, 7, 8, 9,10,11,12,13);
    $managerMenu = array(0, 1, 2, 3, 4 ,5, 6, 7, 8, 9,10,11,12,13);
    $cashierMenu = array(0, 1, 7);
    $menuTypes = array($adminMenu, $managerMenu, $cashierMenu);

    $user = \Illuminate\Support\Facades\Auth::user();
    $menu = $menuTypes[($user->level - 1)];
    ?>

    @for($i = 0; $i < count($menu); $i++)
        @if(in_array($i,$multilevel))

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>{{$menuText[$menu[$i]]}}</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    @foreach($menuChildren[11] as $child)
                        <li><a href="{{url($child['url'])}}"><i class="glyphicon {{$child['icon']}}"></i>{{$child['name']}}</a></li>
                    @endforeach
                </ul>
            </li>

        @else
            <li class="@if($page == $menuUrl[$menu[$i]]) active @endif">
                <a href="{{url($menuUrl[$menu[$i]])}}">
                    <i class="glyphicon {{$menuIcons[$i]}}"></i>
                    <span>{{$menuText[$menu[$i]]}}</span>
                </a>
            </li>

        @endif
    @endfor

@endsection