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
<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
    @include('layouts.header')
    <div class="content d-flex flex-column flex-column-fluid">
        @include('event.config.tab_match')
    </div>
</div>
<!--begin::Footer-->
@include('layouts.footer')
<!--end::Footer-->

@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script  type="text/javascript">
</script>
@endsection
@stop