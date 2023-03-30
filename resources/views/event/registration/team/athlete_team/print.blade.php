<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>UniQ Competition System</title>
    <style>
        body{
            font-family: Tahoma;
            
        }
        
        #table1 {
            border-collapse: collapse;
        }

        .td1 {
            border: 1px solid black;
            padding: 1px;
            font-size: 11px;
        }
        table {
            border-collapse: collapse;
            border: 1px solid black;
            border-style: solid; /* Add this property to change the border style */
        }
        th, td {
            border: 1px solid black;
            padding-bottom: 2px;
            padding-top: 2px;
            text-align: left;
        }
    </style>
</head>
<?php
    $width = " 842px";
    $background = "gray";
?>
<body style="background-color: {{ $background }};">      
    @include('event.registration.team.athlete_team.MVA0103001')

</body>

</html>