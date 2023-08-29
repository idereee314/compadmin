<style>
    .card-content {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      height: 100%;
      font-size: 20px;
      color: black;
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      height: 100%;
      font-size: 20px;
      color: black;
    }
    
    .card-content i {
      margin-bottom: 5px;
    }

    .card {
      margin-bottom: 20px;
    }

    @media only screen and (max-width: 768px) {
      .card {
        width: 100%;
      }
    }
    .action-title {
        justify-content: center;
    }
</style>

@extends('default')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.css')}}">
@endsection
@include('layouts.mobile_v2')
@section('content')

<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper" style="background-image: url('{{ asset('assets/media/bg/bg-3.jpg') }}');">

    @include('layouts.header_v2')
        <!--begin::Content-->
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Subheader-->
                    <div class="subheader py-2 py-lg-4 subheader-transparent mb-5" id="kt_subheader">
                        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                            <!--begin::Details-->
                            <div class="d-flex align-items-center flex-wrap mr-2">
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="" class="text-muted">{{ $sport->name}}</a>
                                    </li>                                    
                                    <li class="breadcrumb-item text-muted">
                                        <a href="{{ route('event.config.index') }}" class="text-muted">Лавлагаа</a>
                                    </li>
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            <!--end::Details-->
                        </div>
                    </div>
                    <!--end::Subheader-->

                    @if($sport_id == '1')
                        @include('reference.info.sports.jiujitsu_division')
                    @elseif($sport_id == '2')
                        @include('reference.info.sports.volleyball_division')
                    @elseif($sport_id == '3')
                        @include('reference.info.sports.judo_division')
                    @endif
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
@include ($view_path.'.modals')
<!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script type="text/javascript">

$(document).ready(function() {
    if ('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
        $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
    }
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

</script>
@endsection
@stop
