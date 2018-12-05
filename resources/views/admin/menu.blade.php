@extends('layouts.base')

@section('menu')

    <?php

    $menuText = array(  'Dashboard', 'Businesses','Logs', 'Settings');
    $menuUrl = array(   'home', 'businesses', 'logs','settings');
    $menuIcons = array(   'fa-home', 'fa-home', 'fa-cog','fa-cogs');
    $adminMenu = array(0, 1, 2,3);
    ?>

    @for($i = 0; $i < count($adminMenu); $i++)
        <li class="@if($page == $menuUrl[$i]) active @endif">
            <a href="{{url('admin/'.$menuUrl[$i])}}">
                <i class="fa {{$menuIcons[$i]}}"></i>
                <span>{{$menuText[$i]}}</span>
            </a>
        </li>
    @endfor

@endsection