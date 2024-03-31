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
                    <h1 class="margin-bottom-xs-0" style="color: #0f4b63;">Чансаа <?php echo date("Y"); ?></h1>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h2 style="font-size: 2rem; color: #0f4b63;" class="text-center"> Эрэгтэй Насанд хүрэгчид </h2>
                                </div>
                                <ul class="list-group">
                                    @foreach($adultMaleList as $index => $male)
                                            <li class="list-group-item">
                                                <div class="col-md-2">
                                                    @if(@$male->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($male->profile_url)) || @env('local')))
                                                        <div class="rounded-circle">
                                                            <img src="{{ \Storage::disk('s3')->url($male->profile_url) }}" style="background-size: cover; width: 42px; height: 42px; border-radius: 50%;" alt="Profile Image">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-10" style="font-size: 1.5em; color: #0f4b63;">
                                                    {{ $index + 1 }}. {{ $male->lastname }} {{ $male->firstname }} <span class="badge badge-success">{{ $male->point }} оноо</span>
                                                </div>
                                            </li>
                                    @endforeach
                                </ul>
                            </div>
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
@include ($view_path.'.modals')
<!--end::Wrapper-->
<!--end::Main-->
@section('javascript')
<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    
</script>
@endsection
@stop
