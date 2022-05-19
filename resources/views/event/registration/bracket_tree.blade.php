@extends('default')
@section('css')
<link rel="stylesheet" href="{{asset('assets/js/plugins/custom/jstree/dist/themes/default/style.min.css')}}">
@endsection
@section('content')
<!--begin::Main-->
<!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid">
            <!--begin::Subheader-->
            <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
                <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                    <!--begin::Details-->
                    <div class="d-flex align-items-center flex-wrap mr-2">
                        <!--begin::Title-->
                        <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3"> Тэмцээний оноолт</h2>
                        <!--end::Title-->
                    </div>
                    <!--end::Details-->
                </div>
            </div>
            <!--end::Subheader-->
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                <div class="card card-custom gutter-b">
                        <div class="card-body">
                            <!--begin::Top-->
                            <div class="d-flex">
                                <!--begin::Pic-->
                                <div class="flex-shrink-0 mr-7">
                                    <div class="symbol symbol-50 symbol-lg-120">
                                        <img alt="Pic" src="{{ \Storage::disk('s3')->url(@$event->picturesMobileCover->first()->dir_url.'/thumbnail/'.@$event->picturesMobileCover->first()->url) }}">
                                    </div>
                                </div>
                                <!--end::Pic-->
                                <!--begin: Info-->
                                <div class="flex-grow-1">
                                    <!--begin::Title-->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mt-2">
                                        <!--begin::User-->
                                        <div class="mr-3">
                                            <!--begin::Name-->
                                            <a href="{{ route('event.registration.index').'?event_id='.@$event->id }}" class="d-flex align-items-center text-dark text-hover-primary font-size-h5 font-weight-bold mr-3">{{@$event->name}} 
                                            <i class="flaticon2-correct text-success icon-md ml-2"></i></a>
                                            <!--end::Name-->
                                            <!--begin::Contacts-->
                                            <div class="d-flex flex-wrap my-2">
                                                <a href="javascript:;" class="text-muted text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                                <span class="svg-icon svg-icon-md svg-icon-gray-500 mr-1">
                                                    <!--begin::Svg Icon | path:/metronic/theme/html/demo5/dist/assets/media/svg/icons/Communication/Mail-notification.svg-->
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"/>
                                                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                                                            <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                                                        </g>
                                                    </svg>
                                                    <!--end::Svg Icon-->
                                                </span>{{ @Carbon\Carbon::parse($event->config->reg_start_date)->format('y M, d g:i A') }} / {{ @Carbon\Carbon::parse(@$event->config->reg_end_date)->format('y M, d g:i A') }}</a>
                                            </div>
                                            <!--end::Contacts-->
                                        </div>
                                        <!--begin::User-->
                                        <!--begin::Actions-->
                                        <!--
                                        <div class="my-lg-0 my-1">
                                            <a href="#" class="btn btn-sm btn-light-primary font-weight-bolder text-uppercase mr-2">Ask</a>
                                            <a href="#" class="btn btn-sm btn-primary font-weight-bolder text-uppercase">Hire</a>
                                        </div>
                                        -->
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Title-->
                                    <!--begin::Content-->
                                    <div class="d-flex align-items-center flex-wrap justify-content-between row">
                                        <div class="col-md-7">
                                            <!--begin::Description-->
                                            <div class="flex-grow-1 font-weight-bold text-dark-50 py-2 py-lg-2 mr-5">{{ Str::words(strip_tags(@$event->description), 20, '...') }}</div>
                                            <!--end::Description-->
                                        </div>
                                        <div class="col-md-5">
                                            <!--begin::Progress-->
                                            <div class="d-flex mt-4 mt-sm-0 float-right">
                                                <span class="font-weight-bold mr-4">Бүртгэлийн явц</span>
                                                <div class="progress progress-xs mt-2 mb-2 flex-shrink-0 w-150px w-xl-250px">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{@$progressPercent}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="font-weight-bolder text-dark ml-4">{{ @$progressPercent }}%</span>
                                            </div>
                                            <!--end::Progress-->
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Top-->
                        </div>
                    </div>
                    <!--begin::Row-->
                    @foreach(@$eventRegistration->chunk(3) as $chunk)
                    <div class="row">
                    @foreach(@$chunk as $key => $entry)
                        <div class="col-xl-4">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label"> {{ @$key }}</h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="tree-demo div-tree">
                                        <ul>
                                            @forelse($entry as $bkey => $belt)
                                            <li data-jstree='{ "opened" : true }'> {{ $bkey }} ({{ @$belt->count() }}н/{{ @$belt->flatten(1)->count() }}ж/{{ @$belt->flatten(2)->count() }}о)
                                                <ul>
                                                    @forelse($belt as $akey => $age)
                                                    <li data-jstree='{ "opened" : true }'> {{ $akey }} ({{ @$age->count() }}ж/{{ @$age->flatten(1)->count() }}о)
                                                        <ul>
                                                            @forelse($age as $wkey => $weight)
                                                            <li data-jstree='{ "type" : "file", "entry_id": "{{ @$weight->first()->entry_id }}", "belt_id": "{{ @$weight->first()->entry_belt_id }}", "age_id": "{{ @$weight->first()->entry_age_id }}", "weight_id": "{{ @$weight->first()->entry_weight_id }}"}'> {{ $wkey }}кг ({{ @$weight->count() }})</li>
                                                            @empty
                                                            @endforelse
                                                        </ul>
                                                    </li>
                                                    @empty
                                                    @endforelse
                                                </ul>
                                            </li>
                                            @empty
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                    @endforeach
                    </div>
                    @endforeach
                    <!--end::Row-->
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
<script src="{{asset('assets/js/plugins/custom/jstree/jstree.bundle.js')}}"></script>
<script>
$(document).ready(function() {
    $('.div-tree').jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }
        },
        "types" : {
            "default" : {
                "icon" : "fa fa-folder text-warning"
            },
            "file" : {
                "icon" : "fa fa-file  text-warning"
            }
        },
        "plugins": ["types"]
    });

    $('.div-tree').on('select_node.jstree', function(e,data) {
        var elData = data.node.data.jstree;
        //console.log(data.node.data.jstree.type);
        if(typeof elData.type !== 'undefined' && elData.type == 'file')
        {
            $.get('/bracket/{{@$event->id}}/'+elData.entry_id+'/'+elData.age_id+'/'+elData.belt_id+'/'+elData.weight_id+'', showBracketModal);
        }
    });
}).ajaxStart($.blockUI).ajaxStop($.unblockUI);

//Modal
function showBracketModal( data ) {

$('#bracketModal').modal();
$('#bracketModal').on('shown.bs.modal', function(){
    $('#bracketModal .card-body').html(data.html);
    $("#print").on('click', function(){

        window.open('/bracket/print/'+data.eventId+'/'+data.entryId+'/'+data.entryAgeId+'/'+data.entryBeltId+'/'+data.entryWeightId+''); 

        /*var divToPrint=document.getElementById('div-print');

        var newWin=window.open('','Print-Window');

        newWin.document.open();

        newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

        newWin.document.close();*/

        //setTimeout(function(){newWin.close();},10);
    });

    $(this).off('shown.bs.modal');
});

$('#bracketModal').on('hidden.bs.modal', function(){
    $('#bracketModal .card-body').empty();
});
}
</script>
@endsection
@stop