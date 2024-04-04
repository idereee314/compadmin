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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
                                    @php $count = 0; @endphp
                                    @foreach($adultMaleList as $index => $male)
                                        @if($count < 10)
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
                                                    <br><span class="badge" style="background-color: #FFD700">{{$male->place_1}}</span><span class="badge" style="background-color: #C0C0C0">{{$male->place_2}}</span><span class="badge" style="background-color: #CD7F32">{{$male->place_3}}</span>
                                                </div>
                                            </li>
                                            @php $count++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                                <div style="text-align: center; padding: 10px;">
                                    <a href="/1/ranking/athlete/male" style="color: #0f4b63; text-decoration: none; font-weight: bold;">Бүгдийг харах</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h2 style="font-size: 2rem; color: #0f4b63;"> Эмэгтэй Насанд хүрэгчид </h2></div>
                                <ul class="list-group">
                                    @php $count = 0; @endphp
                                    @foreach($adultFemaleList as $index => $female)
                                        @if($count < 10)
                                            <li class="list-group-item">
                                                <div class="col-md-2">
                                                    @if(@$female->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($female->profile_url)) || @env('local')))
                                                        <div class="rounded-circle">
                                                            <img src="{{ \Storage::disk('s3')->url($female->profile_url) }}" style="background-size: cover; width: 42px; height: 42px; border-radius: 50%;" alt="Profile Image">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-10" style="font-size: 1.5em; color: #0f4b63;">
                                                    {{ $index + 1 }}. {{ $female->lastname }} {{ $female->firstname }} <span class="badge badge-success">{{ $female->point }} оноо</span>
                                                    <br><span class="badge" style="background-color: #FFD700">{{$female->place_1}}</span><span class="badge" style="background-color: #C0C0C0">{{$female->place_2}}</span><span class="badge" style="background-color: #CD7F32">{{$female->place_3}}</span>
                                                </div>
                                            </li>
                                            @php $count++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                                <div style="text-align: center; padding: 10px;">
                                    <a href="/1/ranking/athlete/female" style="color: #0f4b63; text-decoration: none; font-weight: bold;">Бүгдийг харах</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h2 style="font-size: 2rem; color: #0f4b63;"> Мастерс </h2></div>
                                <ul class="list-group">
                                    @php $count = 0; @endphp
                                    @foreach($mastersList as $index => $masters)
                                        @if($count < 10)
                                            <li class="list-group-item">
                                                <div class="col-md-2">
                                                    @if(@$masters->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($masters->profile_url)) || @env('local')))
                                                        <div class="rounded-circle">
                                                            <img src="{{ \Storage::disk('s3')->url($masters->profile_url) }}" style="background-size: cover; width: 42px; height: 42px; border-radius: 50%;" alt="Profile Image">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-10" style="font-size: 1.5em; color: #0f4b63;">
                                                    {{ $index + 1 }}. {{ $masters->lastname }} {{ $masters->firstname }} <span class="badge badge-success">{{ $masters->point }} оноо</span>
                                                    <br><span class="badge" style="background-color: #FFD700">{{$masters->place_1}}</span><span class="badge" style="background-color: #C0C0C0">{{$masters->place_2}}</span><span class="badge" style="background-color: #CD7F32">{{$masters->place_3}}</span>
                                                </div>
                                            </li>
                                            @php $count++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                                <div style="text-align: center; padding: 10px;">
                                    <a href="/1/ranking/athlete/masters" style="color: #0f4b63; text-decoration: none; font-weight: bold;">Бүгдийг харах</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading"><h2 style="font-size: 2em; color: #0f4b63;"> Өсвөр </h2></div>
                                <ul class="list-group">
                                    @php $count = 0; @endphp
                                    @foreach($kidsList as $index => $kids)
                                        @if($count < 10)
                                            <li class="list-group-item">
                                                <div class="col-md-2">
                                                    @if(@$kids->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($kids->profile_url)) || @env('local')))
                                                        <div class="rounded-circle">
                                                            <img src="{{ \Storage::disk('s3')->url($kids->profile_url) }}" style="background-size: cover; width: 42px; height: 42px; border-radius: 50%;" alt="Profile Image">
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-10" style="font-size: 1.5em; color: #0f4b63;">
                                                    {{ $index + 1 }}. {{ $kids->lastname }} {{ $kids->firstname }} <span class="badge badge-success">{{ $kids->point }} оноо</span>
                                                    <br><span class="badge" style="background-color: #FFD700">{{$kids->place_1}}</span><span class="badge" style="background-color: #C0C0C0">{{$kids->place_2}}</span><span class="badge" style="background-color: #CD7F32">{{$kids->place_3}}</span>
                                                </div>
                                            </li>
                                            @php $count++; @endphp
                                        @endif
                                    @endforeach
                                </ul>
                                <div style="text-align: center; padding: 10px;">
                                    <a href="/1/ranking/athlete/kids" style="color: #0f4b63; text-decoration: none; font-weight: bold;">Бүгдийг харах</a>
                                </div>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script type="text/javascript">
    
</script>
@endsection
@stop
