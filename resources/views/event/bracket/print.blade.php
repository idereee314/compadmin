<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="shortcut icon" href="{{asset('assets/images/logo/uniq_logo.ico')}}" />
    <title>UniQ Sport Bracket System</title>
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
    </style>
</head>
<?php
    $width = "800px";
    $background = "gray";
?>
<body style="background-color: {{ $background }};">
    @include('event.bracket.print_bracket')
</body>
</html>