@extends('default')

@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection

@section('content')
<!--begin::Main-->
<!--begin::Header Mobile-->
@include('layouts.mobile')
<!--end::Header Mobile-->
<!--begin::Aside-->
@include('layouts.aside')
<!--end::Aside-->
<!--begin::Wrapper-->
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">
    <!--begin::Header-->
    @include('layouts.header')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <i class="fas fa-home pr-4" style="color:black"> </i>
                    <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
                    <h2 class="text-dark font-weight-bold mt-2 mb-2 mr-5">{{trans('menu.home')}}</h2>
                    <span style="font-style: italic">
                      
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <a href="#" class="btn btn-sm btn-light font-weight-bold mr-2" >
                        <span class="text-muted font-size-base font-weight-bold mr-2">{{trans('display.general_you_are_here')}}:</span>
                        <span class="text-primary font-size-base font-weight-bolder" id="kt_dashboard_daterangepicker_date">{{trans('menu.home')}}</span>
                    </a>
                </div>
                <!--end::Details-->
            </div>
        </div>
        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <div class="row">
                    <div class="col-xl-4">
                    <!--begin::Mixed Widget 4-->
                        <div class="card card-custom bg-radial-gradient-danger gutter-b card-stretch">
                            <!--begin::Header-->
                            <div class="card-header border-0 py-5">
                                <h3 class="card-title font-weight-bolder text-white">Sales Progress</h3>
                                <div class="card-toolbar">
                                    <div class="dropdown dropdown-inline">
                                        <a href="#" class="btn btn-text-white btn-hover-white btn-sm btn-icon border-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                            <!--begin::Navigation-->
                                            <ul class="navi navi-hover">
                                                <li class="navi-header pb-1">
                                                    <span class="text-primary text-uppercase font-weight-bold font-size-sm">Add new:</span>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-shopping-cart-1"></i></span>
                                                        <span class="navi-text">Order</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-calendar-8"></i></span>
                                                        <span class="navi-text">Event</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-graph-1"></i></span>
                                                        <span class="navi-text">Report</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-rocket-1"></i></span>
                                                        <span class="navi-text">Post</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a href="#" class="navi-link">
                                                        <span class="navi-icon"><i class="flaticon2-writing"></i></span>
                                                        <span class="navi-text">File</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <!--end::Navigation-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex flex-column p-0" style="position: relative;">
                                <!--begin::Chart-->
                                <div id="kt_mixed_widget_4_chart" style="height: 200px; min-height: 200px;"><div id="apexcharts5a9k5pq5" class="apexcharts-canvas apexcharts5a9k5pq5 apexcharts-theme-light" style="width: 413px; height: 200px;"><svg id="SvgjsSvg1251" width="413" height="200" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1253" class="apexcharts-inner apexcharts-graphical" transform="translate(20, 0)"><defs id="SvgjsDefs1252"><linearGradient id="SvgjsLinearGradient1256" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1257" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1258" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1259" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMask5a9k5pq5"><rect id="SvgjsRect1261" width="378" height="201" x="-2.5" y="-0.5" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMask5a9k5pq5"><rect id="SvgjsRect1262" width="377" height="204" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1260" width="7.992857142857142" height="200" x="238.21429421561106" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1256)" class="apexcharts-xcrosshairs" y2="200" filter="none" fill-opacity="0.9" x1="238.21429421561106" x2="238.21429421561106"></rect><g id="SvgjsG1282" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1283" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1291" class="apexcharts-grid"><g id="SvgjsG1292" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1294" x1="0" y1="0" x2="373" y2="0" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1295" x1="0" y1="20" x2="373" y2="20" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1296" x1="0" y1="40" x2="373" y2="40" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1297" x1="0" y1="60" x2="373" y2="60" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1298" x1="0" y1="80" x2="373" y2="80" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1299" x1="0" y1="100" x2="373" y2="100" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1300" x1="0" y1="120" x2="373" y2="120" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1301" x1="0" y1="140" x2="373" y2="140" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1302" x1="0" y1="160" x2="373" y2="160" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1303" x1="0" y1="180" x2="373" y2="180" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line><line id="SvgjsLine1304" x1="0" y1="200" x2="373" y2="200" stroke="#ecf0f3" stroke-dasharray="4" class="apexcharts-gridline"></line></g><g id="SvgjsG1293" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1306" x1="0" y1="200" x2="373" y2="200" stroke="transparent" stroke-dasharray="0"></line><line id="SvgjsLine1305" x1="0" y1="1" x2="0" y2="200" stroke="transparent" stroke-dasharray="0"></line></g><g id="SvgjsG1263" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1264" class="apexcharts-series" rel="1" seriesName="NetxProfit" data:realIndex="0"><path id="SvgjsPath1266" d="M 18.65 200L 18.65 131.49821428571428Q 22.14642857142857 128.50178571428572 25.64285714285714 131.49821428571428L 25.64285714285714 131.49821428571428L 25.64285714285714 200L 25.64285714285714 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 18.65 200L 18.65 131.49821428571428Q 22.14642857142857 128.50178571428572 25.64285714285714 131.49821428571428L 25.64285714285714 131.49821428571428L 25.64285714285714 200L 25.64285714285714 200z" pathFrom="M 18.65 200L 18.65 200L 25.64285714285714 200L 25.64285714285714 200L 25.64285714285714 200L 18.65 200" cy="130" cx="71.43571428571428" j="0" val="35" barHeight="70" barWidth="7.992857142857142"></path><path id="SvgjsPath1267" d="M 71.93571428571428 200L 71.93571428571428 71.49821428571428Q 75.43214285714285 68.50178571428572 78.92857142857143 71.49821428571428L 78.92857142857143 71.49821428571428L 78.92857142857143 200L 78.92857142857143 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 71.93571428571428 200L 71.93571428571428 71.49821428571428Q 75.43214285714285 68.50178571428572 78.92857142857143 71.49821428571428L 78.92857142857143 71.49821428571428L 78.92857142857143 200L 78.92857142857143 200z" pathFrom="M 71.93571428571428 200L 71.93571428571428 200L 78.92857142857143 200L 78.92857142857143 200L 78.92857142857143 200L 71.93571428571428 200" cy="70" cx="124.72142857142856" j="1" val="65" barHeight="130" barWidth="7.992857142857142"></path><path id="SvgjsPath1268" d="M 125.22142857142856 200L 125.22142857142856 51.49821428571428Q 128.71785714285713 48.50178571428571 132.2142857142857 51.49821428571428L 132.2142857142857 51.49821428571428L 132.2142857142857 200L 132.2142857142857 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 125.22142857142856 200L 125.22142857142856 51.49821428571428Q 128.71785714285713 48.50178571428571 132.2142857142857 51.49821428571428L 132.2142857142857 51.49821428571428L 132.2142857142857 200L 132.2142857142857 200z" pathFrom="M 125.22142857142856 200L 125.22142857142856 200L 132.2142857142857 200L 132.2142857142857 200L 132.2142857142857 200L 125.22142857142856 200" cy="50" cx="178.00714285714284" j="2" val="75" barHeight="150" barWidth="7.992857142857142"></path><path id="SvgjsPath1269" d="M 178.50714285714284 200L 178.50714285714284 91.49821428571428Q 182.0035714285714 88.50178571428572 185.49999999999997 91.49821428571428L 185.49999999999997 91.49821428571428L 185.49999999999997 200L 185.49999999999997 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 178.50714285714284 200L 178.50714285714284 91.49821428571428Q 182.0035714285714 88.50178571428572 185.49999999999997 91.49821428571428L 185.49999999999997 91.49821428571428L 185.49999999999997 200L 185.49999999999997 200z" pathFrom="M 178.50714285714284 200L 178.50714285714284 200L 185.49999999999997 200L 185.49999999999997 200L 185.49999999999997 200L 178.50714285714284 200" cy="90" cx="231.29285714285712" j="3" val="55" barHeight="110" barWidth="7.992857142857142"></path><path id="SvgjsPath1270" d="M 231.79285714285712 200L 231.79285714285712 111.49821428571428Q 235.28928571428568 108.50178571428572 238.78571428571425 111.49821428571428L 238.78571428571425 111.49821428571428L 238.78571428571425 200L 238.78571428571425 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 231.79285714285712 200L 231.79285714285712 111.49821428571428Q 235.28928571428568 108.50178571428572 238.78571428571425 111.49821428571428L 238.78571428571425 111.49821428571428L 238.78571428571425 200L 238.78571428571425 200z" pathFrom="M 231.79285714285712 200L 231.79285714285712 200L 238.78571428571425 200L 238.78571428571425 200L 238.78571428571425 200L 231.79285714285712 200" cy="110" cx="284.5785714285714" j="4" val="45" barHeight="90" barWidth="7.992857142857142"></path><path id="SvgjsPath1271" d="M 285.0785714285714 200L 285.0785714285714 81.49821428571428Q 288.575 78.50178571428572 292.07142857142856 81.49821428571428L 292.07142857142856 81.49821428571428L 292.07142857142856 200L 292.07142857142856 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 285.0785714285714 200L 285.0785714285714 81.49821428571428Q 288.575 78.50178571428572 292.07142857142856 81.49821428571428L 292.07142857142856 81.49821428571428L 292.07142857142856 200L 292.07142857142856 200z" pathFrom="M 285.0785714285714 200L 285.0785714285714 200L 292.07142857142856 200L 292.07142857142856 200L 292.07142857142856 200L 285.0785714285714 200" cy="80" cx="337.8642857142857" j="5" val="60" barHeight="120" barWidth="7.992857142857142"></path><path id="SvgjsPath1272" d="M 338.3642857142857 200L 338.3642857142857 91.49821428571428Q 341.86071428571427 88.50178571428572 345.35714285714283 91.49821428571428L 345.35714285714283 91.49821428571428L 345.35714285714283 200L 345.35714285714283 200z" fill="rgba(255,255,255,0.25)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 338.3642857142857 200L 338.3642857142857 91.49821428571428Q 341.86071428571427 88.50178571428572 345.35714285714283 91.49821428571428L 345.35714285714283 91.49821428571428L 345.35714285714283 200L 345.35714285714283 200z" pathFrom="M 338.3642857142857 200L 338.3642857142857 200L 345.35714285714283 200L 345.35714285714283 200L 345.35714285714283 200L 338.3642857142857 200" cy="90" cx="391.15" j="6" val="55" barHeight="110" barWidth="7.992857142857142"></path></g><g id="SvgjsG1273" class="apexcharts-series" rel="2" seriesName="Revenue" data:realIndex="1"><path id="SvgjsPath1275" d="M 26.64285714285714 200L 26.64285714285714 121.49821428571428Q 30.13928571428571 118.50178571428572 33.63571428571428 121.49821428571428L 33.63571428571428 121.49821428571428L 33.63571428571428 200L 33.63571428571428 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 26.64285714285714 200L 26.64285714285714 121.49821428571428Q 30.13928571428571 118.50178571428572 33.63571428571428 121.49821428571428L 33.63571428571428 121.49821428571428L 33.63571428571428 200L 33.63571428571428 200z" pathFrom="M 26.64285714285714 200L 26.64285714285714 200L 33.63571428571428 200L 33.63571428571428 200L 33.63571428571428 200L 26.64285714285714 200" cy="120" cx="79.42857142857143" j="0" val="40" barHeight="80" barWidth="7.992857142857142"></path><path id="SvgjsPath1276" d="M 79.92857142857143 200L 79.92857142857143 61.49821428571428Q 83.425 58.50178571428571 86.92142857142858 61.49821428571428L 86.92142857142858 61.49821428571428L 86.92142857142858 200L 86.92142857142858 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 79.92857142857143 200L 79.92857142857143 61.49821428571428Q 83.425 58.50178571428571 86.92142857142858 61.49821428571428L 86.92142857142858 61.49821428571428L 86.92142857142858 200L 86.92142857142858 200z" pathFrom="M 79.92857142857143 200L 79.92857142857143 200L 86.92142857142858 200L 86.92142857142858 200L 86.92142857142858 200L 79.92857142857143 200" cy="60" cx="132.7142857142857" j="1" val="70" barHeight="140" barWidth="7.992857142857142"></path><path id="SvgjsPath1277" d="M 133.2142857142857 200L 133.2142857142857 41.49821428571428Q 136.71071428571426 38.50178571428571 140.20714285714283 41.49821428571428L 140.20714285714283 41.49821428571428L 140.20714285714283 200L 140.20714285714283 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 133.2142857142857 200L 133.2142857142857 41.49821428571428Q 136.71071428571426 38.50178571428571 140.20714285714283 41.49821428571428L 140.20714285714283 41.49821428571428L 140.20714285714283 200L 140.20714285714283 200z" pathFrom="M 133.2142857142857 200L 133.2142857142857 200L 140.20714285714283 200L 140.20714285714283 200L 140.20714285714283 200L 133.2142857142857 200" cy="40" cx="185.99999999999997" j="2" val="80" barHeight="160" barWidth="7.992857142857142"></path><path id="SvgjsPath1278" d="M 186.49999999999997 200L 186.49999999999997 81.49821428571428Q 189.99642857142854 78.50178571428572 193.4928571428571 81.49821428571428L 193.4928571428571 81.49821428571428L 193.4928571428571 200L 193.4928571428571 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 186.49999999999997 200L 186.49999999999997 81.49821428571428Q 189.99642857142854 78.50178571428572 193.4928571428571 81.49821428571428L 193.4928571428571 81.49821428571428L 193.4928571428571 200L 193.4928571428571 200z" pathFrom="M 186.49999999999997 200L 186.49999999999997 200L 193.4928571428571 200L 193.4928571428571 200L 193.4928571428571 200L 186.49999999999997 200" cy="80" cx="239.28571428571425" j="3" val="60" barHeight="120" barWidth="7.992857142857142"></path><path id="SvgjsPath1279" d="M 239.78571428571425 200L 239.78571428571425 101.49821428571428Q 243.28214285714282 98.50178571428572 246.77857142857138 101.49821428571428L 246.77857142857138 101.49821428571428L 246.77857142857138 200L 246.77857142857138 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 239.78571428571425 200L 239.78571428571425 101.49821428571428Q 243.28214285714282 98.50178571428572 246.77857142857138 101.49821428571428L 246.77857142857138 101.49821428571428L 246.77857142857138 200L 246.77857142857138 200z" pathFrom="M 239.78571428571425 200L 239.78571428571425 200L 246.77857142857138 200L 246.77857142857138 200L 246.77857142857138 200L 239.78571428571425 200" cy="100" cx="292.57142857142856" j="4" val="50" barHeight="100" barWidth="7.992857142857142"></path><path id="SvgjsPath1280" d="M 293.07142857142856 200L 293.07142857142856 71.49821428571428Q 296.5678571428571 68.50178571428572 300.0642857142857 71.49821428571428L 300.0642857142857 71.49821428571428L 300.0642857142857 200L 300.0642857142857 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 293.07142857142856 200L 293.07142857142856 71.49821428571428Q 296.5678571428571 68.50178571428572 300.0642857142857 71.49821428571428L 300.0642857142857 71.49821428571428L 300.0642857142857 200L 300.0642857142857 200z" pathFrom="M 293.07142857142856 200L 293.07142857142856 200L 300.0642857142857 200L 300.0642857142857 200L 300.0642857142857 200L 293.07142857142856 200" cy="70" cx="345.85714285714283" j="5" val="65" barHeight="130" barWidth="7.992857142857142"></path><path id="SvgjsPath1281" d="M 346.35714285714283 200L 346.35714285714283 81.49821428571428Q 349.8535714285714 78.50178571428572 353.34999999999997 81.49821428571428L 353.34999999999997 81.49821428571428L 353.34999999999997 200L 353.34999999999997 200z" fill="rgba(255,255,255,1)" fill-opacity="1" stroke="transparent" stroke-opacity="1" stroke-linecap="square" stroke-width="1" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMask5a9k5pq5)" pathTo="M 346.35714285714283 200L 346.35714285714283 81.49821428571428Q 349.8535714285714 78.50178571428572 353.34999999999997 81.49821428571428L 353.34999999999997 81.49821428571428L 353.34999999999997 200L 353.34999999999997 200z" pathFrom="M 346.35714285714283 200L 346.35714285714283 200L 353.34999999999997 200L 353.34999999999997 200L 353.34999999999997 200L 346.35714285714283 200" cy="80" cx="399.1428571428571" j="6" val="60" barHeight="120" barWidth="7.992857142857142"></path></g><g id="SvgjsG1265" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG1274" class="apexcharts-datalabels" data:realIndex="1"></g></g><line id="SvgjsLine1307" x1="0" y1="0" x2="373" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1308" x1="0" y1="0" x2="373" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1309" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1310" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1311" class="apexcharts-point-annotations"></g></g><g id="SvgjsG1290" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1254" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 100px;"></div><div class="apexcharts-tooltip apexcharts-theme-light" style="left: 88.0701px; top: 66.0449px;"><div class="apexcharts-tooltip-title" style="font-family: Poppins; font-size: 12px;">Jun</div><div class="apexcharts-tooltip-series-group apexcharts-active" style="order: 1; display: flex;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(255, 255, 255); display: none;"></span><div class="apexcharts-tooltip-text" style="font-family: Poppins; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label">Revenue: </span><span class="apexcharts-tooltip-text-value">$50 thousands</span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2; display: none;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(255, 255, 255); display: none;"></span><div class="apexcharts-tooltip-text" style="font-family: Poppins; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label">Revenue: </span><span class="apexcharts-tooltip-text-value">$50 thousands</span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div><div class="apexcharts-yaxistooltip apexcharts-yaxistooltip-0 apexcharts-yaxistooltip-left apexcharts-theme-light"><div class="apexcharts-yaxistooltip-text"></div></div></div></div>
                                <!--end::Chart-->

                                <!--begin::Stats-->
                                <div class="card-spacer bg-white card-rounded flex-grow-1">
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col px-8 py-6 mr-8">
                                            <div class="font-size-sm text-muted font-weight-bold">Average Sale</div>
                                            <div class="font-size-h4 font-weight-bolder">$650</div>
                                        </div>
                                        <div class="col px-8 py-6">
                                            <div class="font-size-sm text-muted font-weight-bold">Commission</div>
                                            <div class="font-size-h4 font-weight-bolder">$233,600</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <div class="row m-0">
                                        <div class="col px-8 py-6 mr-8">
                                            <div class="font-size-sm text-muted font-weight-bold">Annual Taxes</div>
                                            <div class="font-size-h4 font-weight-bolder">$29,004</div>
                                        </div>
                                        <div class="col px-8 py-6">
                                            <div class="font-size-sm text-muted font-weight-bold">Annual Income</div>
                                            <div class="font-size-h4 font-weight-bolder">$1,480,00</div>
                                        </div>
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Stats-->
                            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 414px; height: 431px;"></div></div><div class="contract-trigger"></div></div></div>
                            <!--end::Body-->
                        </div>
                        <!--end::Mixed Widget 4-->
                    </div>
                    
                </div>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
    <!--end::Content-->
    <!--begin::Footer-->
    @include('layouts.footer')
    <!--end::Footer-->
</div>
<!--end::Wrapper-->
<!--end::Main-->
@section('javascript')

@endsection
@stop