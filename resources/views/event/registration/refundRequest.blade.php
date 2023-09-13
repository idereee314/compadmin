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
    <!--begin::Header-->
    @include('layouts.header')
        <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-4 subheader-transparent" id="kt_subheader">
            <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Details-->
                <div class="d-flex align-items-center flex-wrap mr-2">
                    <!--begin::Title-->
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">Тэмцээний буцаалт</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <!-- <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">Бүртгэл</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('event.config.index') }}" class="text-muted">Тэмцээний тохиргоо</a>
                        </li>
                    </ul> -->
                    <!--end::Breadcrumb-->
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
                                            <span class="font-weight-bold mr-4">Буцаалтын явц</span>
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
                <!--begin::Card-->
                <div class="card card-custom">
                    <input type="hidden" name="event_id" id="event_id" value="{{ $event->id}}"/>
                   
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <div class="card-toolbar">
                            <!--begin::Button-->
                            <a href="javascript:;" class="btn btn-primary font-weight-bolder" id="refund-add-request">
                            <span class="svg-icon svg-icon-md">
                                <!--begin::Svg Icon | path:/metronic/theme/html/demo5/dist/assets/media/svg/icons/Design/Flatten.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <circle fill="#000000" cx="9" cy="15" r="6"></circle>
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>{{trans('display.general_new')}}</a>
                            <!--end::Button-->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="separator separator-solid mb-5"></div>
                        <!--begin::Table-->
                        <table class="table table-separate table-head-custom" id="event-refund-request-datatable" style="margin-top: 13px !important">                            
                            <thead>
                                <tr>
                                    <th class="w-55px text-center">#</th>
                                    <th class="min-w-200px text-center">{{trans('display.event_title')}}</th> 
                                    <th class="min-w-100px text-center">{{trans('display.general_name')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.comp_academy_name')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.general_amount')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.general_description')}}</th>
                                    <th class="min-w-125px text-center">{{trans('display.general_status')}}</th>
                                    <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                                    <th class="min-w-150px text-center">{{trans('display.general_manage')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventRefundRequest as $request)
                                <tr>
                                    <td class="text-center">{{++$loop->index}}</td>
                                    <td class="text-center">{{$request->event->name}}</td>
                                    <td class="text-center">{{$request->member->fullname}}</td>
                                    <td class="text-center">{{$request->academy->name}}</td>
                                    <td class="text-center">{{$request->amount}}</td>
                                    <td class="text-center">{{ Str::words(strip_tags(@$request->description), 20, '...') }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-light-{{ Config::get('smart.event_refund_request_status_class')[$request->status] }} btn-sm btn-status" data-requestid="{{ $request->id }}">{{ Config::get('enums.event_refund_request_status')[$request->status] }}</button>
                                    </td>
                                    <td class="text-center">{{$request->created_at}}</td>
                                    <td class="text-center pr-0">
                                        <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-request" data-requestid="{{$request->id}}">
                                            <span class="svg-icon svg-icon-md svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                                        <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                        <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm delete-request" data-requestid="{{$request->id}}">
                                            <span class="svg-icon svg-icon-md svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                                        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card-->
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
<script>
$(document).ready(function () {
    $("#refund-add-request").on('click', function(){
        var eventId = $("#event_id").val();
        
    	$.get('{!! route('event.refund.request.create') !!}?eventId='+eventId, function( data ) {
    		$('#memberModal').modal();
    		$('#memberModal').on('shown.bs.modal', function(){
    			$('#memberModal .modal-content').html(data);
                $('.selectpicker').selectpicker();

                $('#create-event-refund-request-form select[name=member_id]').select2();
                $('#create-event-refund-request-form input[name=academy_id]').select2({data: ""});
                
                $('#create-event-refund-request-form select[name=member_id]').select2({
                    width: 'resolve',
                    dropdownAutoWidth : true,
                    dropdownParent: $('#memberModal'),
                    placeholder: "-- {{ trans('display.general_select') }} --",
                    minimumInputLength: 3,
                    ajax: {
                        url: '{!! route('member.search') !!}',
                        delay: 1500,
                        data: function (params) {
                            var query = {
                                q: params.term
                            }
                            return query;
                        },

                        processResults: function (data) {
                            console.log(data);
                            return {
                                results: JSON.parse(data)
                            };
                        },
                        cache: true
                    },
                    templateSelection: function (item) {
                        return item.fullname;
                    },
                    templateResult: function (item) {
                        return item.fullname;
                    }
                });

                $('#create-event-refund-request-form select[name=academy_id]').on('change', function(){
                    var academyId = $(this).val(); 
                    $.ajax({
                        type: 'POST',
                        url: '{!! route('academy.isother') !!}',
                        data: {academy_id: academyId},
                        success: function (data) {
                            $('#academy_name_other').addClass('d-none');
                            $("#academy_name").attr("disabled", true);
                            $("#academy_name").val("");
                            jsonData = JSON.parse(data);
                        
                            if(jsonData) {
                                $('#academy_name_other').removeClass('d-none');
                                $("#academy_name").attr("disabled", false);
                            }              
                        },
                        error: function (xhr, textStatus, error) {
                            console.log(xhr.statusText);
                            console.log(textStatus);
                            console.log(error);
                        },
                        async: false
                    });
                })

    			$('#create-event-refund-request-form').validate({
    				ignore: [],
    				highlight:function(element) {
    					$(element).parents('.form-group').addClass('has-error has-feedback');
    				},
    				unhighlight: function(element) {
    					$(element).parents('.form-group').removeClass('has-error');
    				},
    				submitHandler: function(form) {
    					$.ajax({
                            url: form.action,
    						type: form.method,
    						data: $(form).serialize(),
    						success: function(response) {
    							if(response.status == 'success')
                                {
                                    $('#memberModal').find("#close").trigger('click');   
                                    toastr.success(response.msg);
                                    $("#eventTable").page(page).draw("page");
                                }
                                else {
                                    toastr.error(response.errors, response.msg, {
                                        "closeButton": true,
                                        "timeOut": "0",
                                        "extendedTimeOut": "0",
                                    });
                                }     
    						},
    						error: function (xhr, textStatus, error) {
    							console.log(xhr.statusText);
    							console.log(textStatus);
    							console.log(error);
    						},
    						async: false          
    					});
    				},
    				errorPlacement: function(error, element) {
    					error.insertAfter(element);
    				}
    			});

    			$(this).off('shown.bs.modal');
    		});
        
    		$('#memberModal').on('hidden.bs.modal', function(){
    			$('#memberModal .panel-body').empty();
    		});
        
    	});
    });

    $(".edit-request").on('click', function(){
        var eventId = $("#event_id").val();
        var requestId = $(this).data("requestid");

        $.get('/event/refund/request/'+requestId+'/edit?eventId=' + eventId, function( data ) {
    		$('#memberModal').modal();
    		$('#memberModal').on('shown.bs.modal', function(){
    			$('#memberModal .modal-content').html(data);
                $('.selectpicker').selectpicker();

    			$('#update-event-refund-request-form').validate({
    				ignore: [],
    				highlight:function(element) {
    					$(element).parents('.form-group').addClass('has-error has-feedback');
    				},
    				unhighlight: function(element) {
    					$(element).parents('.form-group').removeClass('has-error');
    				},
    				submitHandler: function(form) {
    					$.ajax({
    						url: form.action,
    						type: form.method,
    						data: $(form).serialize(),
    						success: function(response) {                           
                                if(response.status == 'success')
                                {
                                    $('#memberModal').find("#close").trigger('click');
                                    toastr.success(response.msg);
                                }
                                else {
                                    toastr.error(response.errors, response.msg, {
                                        "closeButton": true,
                                        "timeOut": "0",
                                        "extendedTimeOut": "0",
                                    });
                                }   
    						},
    						error: function (xhr, textStatus, error) {
    							console.log(xhr.statusText);
    							console.log(textStatus);
    							console.log(error);
    						},
    						async: false,        
    					});
    				},
    				errorPlacement: function(error, element) {
    					error.insertAfter(element);
                        if($(element).parents('.form-group').find(".error-here").length > 0){
                            error.appendTo($(element).parents('.form-group').find(".error-here"));
                        } else {
                            error.insertAfter(element);
                        }
    				}
    			});

    			$(this).off('shown.bs.modal');
    		});

    		$('#memberModal').on('hidden.bs.modal', function(){
    			$('#memberModal .modal-content').empty();
    		});
    	});
    });

    $(".delete-request").on('click', function(){
        var requestId = $(this).data("requestid");

        Swal.fire({
            title: "Та устгахдаа итгэлтэй байна уу",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Тийм",
            cancelButtonText: 'Үгүй',
            customClass: {
                confirmButton: "btn btn-primary",
                cancelButton: 'btn btn-secondary'
            },
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: '/event/refund/request/'+requestId,
                    type: 'DELETE',
                    success: function (response) {
                        if(response.status == 'success')
                        {
                            $('#memberModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                        }
                        else {
                            toastr.error(response.errors, response.msg, {
                                "closeButton": true,
                                "timeOut": "0",
                                "extendedTimeOut": "0",
                            });
                        }  
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                });
            }
        });
    });

});
</script>
@endsection
@stop