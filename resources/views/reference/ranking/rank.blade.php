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
                    
                    @if($sport_id == '1')
                        @include('reference.ranking.jiujitsu')
                    @elseif($sport_id == '2')
                        @include('reference.ranking.volleyball')
                    @elseif($sport_id == '3')
                        @include('reference.ranking.judo')
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
    <!--end::Wrapper-->
<!--end::Main-->
@include ($view_path.'.modals')
<!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        if ('{{ old('tab_id') }}' != '' || '{{ $tab_id }}' != '') {
            $('a[name={{ old('tab_id')? old('tab_id'): $tab_id }}]').trigger('click');
        }

        // Get the initial content of the breadcrumb item
        var initialContent = $("#breadcrumb-item-text").html();

        // Listen for changes in the selectpicker
        $(".selectpicker").on("change", function () {
            var selectedOption = $(this).find("option:selected");
            var seasonName = selectedOption.text();
            
            // Update the breadcrumb item with the selected season name
            $("#season").html('<a href="" style="color: black;"><strong>' + seasonName + '</strong></a>');
        });
    }).ajaxStart($.blockUI).ajaxStop($.unblockUI);

    var KTBootstrapSelect = function () {

    // Private functions
    var demos = function () {
        // minimum setup
        $('.kt-selectpicker').selectpicker();
    }
    
    return {
        // public functions
        init: function() {
            demos();
        }
    };
    }();
    
    jQuery(document).ready(function() {
    KTBootstrapSelect.init();
    });
</script>
@endsection
@stop
