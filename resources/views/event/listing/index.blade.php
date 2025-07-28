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
                    <h2 class="d-flex align-items-center text-dark font-weight-bold my-1 mr-3">{{ trans('display.event_list') }}</h2>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold my-2 p-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="javascript:;" class="text-muted">{{ trans('display.general_registration') }}</a>
                        </li>
                    </ul>
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
                <!--begin::Card-->
                <div class="card card-custom">
                    <div class="card-body">
                        
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-light-borderless accordion-svg-toggle" id="search">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#search-event">
                                        <span class="svg-icon svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-right.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999)" />
                                                </g>
                                            </svg>
                                            <!--end::Svg Icon-->
                                        </span>
                                        <div class="card-label pl-4">{{ trans('display.general_search_list') }}</div>
                                    </div>
                                </div>
                                <div id="search-event" class="collapse" data-parent="#search">
                                    <div class="card-body">
                                        <!--begin: Search Form-->
                                        <form class="mb-10" id="event-search-form" method="POST">
                                            <div class="row mb-6">
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{trans('display.general_name')}}</label>
                                                    <input type="text" class="form-control datatable-input" name="search_name" id="search_name" data-col-index="2">
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
                                                    <label>{{ trans('display.general_status') }}:</label>
                                                    <select class="form-control selectpicker datatable-input" name="search_status" id="search_status" data-col-index="6">
                                                        <option value="">-- {{ trans('display.general_all') }} --</option>
                                                        @forelse(@Config::get('enums.event_status') as $key => $status)
                                                        <option value="{{ $key }}">{{ $status }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 mb-lg-0 mb-6">
			                                    	<label>{{ trans('display.general_event_date') }}:</label>
                                                    <input type="text" class="form-control datatable-input date" id="search_date" name="search_date" readonly placeholder="{{ trans('display.general_select') }}"/>
                                                </div>
                                            </div>
                                            <div class="row mt-8">
                                                <div class="col-lg-12">
                                                    <button type="submit" class="btn btn-primary btn-primary--icon">
                                                        <span>
                                                            <i class="la la-search"></i>
                                                            <span>{{ trans('display.general_search') }}</span>
                                                        </span>
                                                    </button>
                                                    <button type="reset" class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                                        <span>
                                                            <i class="la la-close"></i>
                                                            <span>{{ trans('display.general_reset') }}</span>
                                                        </span>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Accordion-->
                        <div class="separator separator-solid mb-5"></div>
                        <!--begin: Datatable-->
                        <table class="table table-separate table-head-custom" id="event-datatable" style="margin-top: 13px !important">
                            <thead>
                                <tr>
                                    <th width="5%">№</th>
                                    <th width="30%">{{trans('display.general_name')}}</th>
                                    <th width="15%">{{trans('display.general_event_date')}}</th>
                                    <!-- <th width="10%">{{trans('display.general_description')}}</th> -->
                                    <th width="10%">{{trans('display.event_details')}}</th>
                                    <th width="10%">{{trans('display.general_status')}}</th>
                                    <!-- <th width="10%">{{trans('display.organization')}}</th> -->                                    
                                    <th width="15%">{{trans('display.general_created_at')}}</th>
                                    <th width="10%">{{trans('display.general_manage')}}</th>
                                </tr>
                            </thead>
                        </table>
                        <!--end: Datatable-->
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
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-datepicker-vitalets/js/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/moment-develop/min/moment.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/jasny-bootstrap-fileinput/js/jasny-bootstrap.fileinput.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/croppie-master/croppie.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>

<script type="text/javascript" src="{{asset('assets/js/plugins/custom/ol/build/ol.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/js/plugins/custom/ol-ext-master/dist/ol-ext.js')}}"></script>
<script>
$(document).ready(function() {
    $('.date').datepicker({
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        templates: {
            leftArrow: '<i class="la la-angle-left"></i>',
            rightArrow: '<i class="la la-angle-right"></i>',
        },
    });

    eventTable = $("#event-datatable").DataTable({
        processing: false,
        serverSide: true,
        autoWidth: true,
        select: true,
        responsive: true,
        dataType: 'json',
        paginationType: "full_numbers",
        ajax: {
            url: '{{route('event.data.list')}}',
            type: 'POST',
            data: function ( d ) {
                d.search_name = $('#event-search-form input[id="search_name"]').val();
                d.search_status = $('#event-search-form select[id="search_status"]').val();
                d.search_date = $('#event-search-form input[id="search_date"]').val();
            },
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: "30px"
            },
            {data: 'name'},
            {data: 'event_date'},
            {data: 'description'},
            {data: 'status'},            
            {data: 'created_at'},
            {data: 'action'},
        ],
        columnDefs: [ 
        {
            searchable: false,
            orderable: false,
            targets: [0]
        },{
            class: "text-center",
            targets: [0, 3, 4, 6]
        }],
        order: [[ 5, "desc" ]],
        dom: "<'top'B><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
        buttons: [
        {
            text: '<i class="la la-plus"></i> {{ trans('display.general_new') }}',
            className: "btn btn-light-danger font-weight-bolder mb-2 {{ SecurityHelper::checkPermission(@Config::get('permission.event_registration'), Config::get('permission.editable')) ? '' : 'd-none' }}",
            action: function ( e, dt, node, config ) {
                $.get('{!! route('event.list.create') !!}', showAddModal);
            }
        }]
	});

    $('#event-search-form').on('submit', function(e) {
        eventTable.draw();
        e.preventDefault();
    });

    $("#kt_reset").click(function(e){
        e.preventDefault();
        $('.datatable-input').each(function() {
            $(this).val('');
            eventTable.column($(this).data('col-index')).search('', false, false);

            $("#search_status").val('').selectpicker("refresh");
        });
        eventTable.draw();
    });

    $('#event-datatable tbody').on( 'click', 'tr td a.description', function () {
        var eventId = $(this).data("eventid");

        $.get('list/'+eventId, showEventDescriptionModal);
    });

    function showEventDescriptionModal( data ) {
        $('#eventDetailModal').modal("show");
        $('#eventDetailModal').on('shown.bs.modal', function(){
            $('#eventDetailModal .modal-content').html(data);
            $('.selectpicker').selectpicker();
            $(this).off('shown.bs.modal');
        });
        $('#eventDetailModal').on('hidden.bs.modal', function(){
            $('#eventDetailModal .modal-content').empty();
        });
    }

    function showAddModal(data){
        $('#eventModal').modal();
        $('#eventModal').on('shown.bs.modal', function(){
            $('#eventModal .modal-content').html(data);
            $('.chosen-select').chosen({ max_selected_options: 1 });
            $(':input').inputmask();
            
            var orgWidth = null;
            var orgHeight = null;
            var $basic = null;
            var imgWidth = Math.round($("#event-create-form input[name=picture_type]").data('width') / 2);
            var imgHeight = Math.round($("#event-create-form input[name=picture_type]").data('height') / 2);
            var img = null;
            var croppedData = null;
            var orginalData = null;

            var role;
            var oganizars;
            var i = 0;

            $('#event-create-form .date-range-picker-time').daterangepicker({
                showWeekNumbers: true,
                showDropdowns: true,
                //timePicker: true,
                //timePicker24Hour: true,
                //autoUpdateInput: false,
                //timePickerIncrement: 10,
                minYear: 2021,
                maxYear: parseInt(moment().format("YYYY"), 1),
                locale: {
                    format: 'YYYY-MM-DD',
                    separator: " аас ",
                    applyLabel: "Оруулах",
                    cancelLabel: "Болих",
                    fromLabel: "аас",
                    toLabel: "руу",
                    customRangeLabel: "Сонголт",
                    daysOfWeek: [
                        "Ня",
                        "Да",
                        "Мя",
                        "Лха",
                        "Пү",
                        "Ба",
                        "Бя"
                    ],
                    firstDay: 1
                }
            }, function(start, end, label) {
                var html = "";
                for(var d = new Date(start); d <= new Date(end); d.setDate(d.getDate() + 1))
                {
                    html += '\
                    <div class="form-group">\
                        <div class="col-md-3 col-sm-12">\
                            <input type="type" class="form-control" name="event_date[]" id="event_date" value="'+moment(d).format("YYYY-MM-DD")+'" readonly/>\
                        </div>\
                        <div class="col-md-9 col-sm-12">\
                            <div class="row">\
                                <div class="col-md-6">\
                                    <div class="timepicker input-group">\
                                        <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="start_time[]" id="start_time" data-rule-required="true" data-msg-required=""/>\
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                                    </div>\
                                </div>\
                                <div class="col-md-6">\
                                    <div class="timepicker input-group">\
                                        <input class="form-control" type="text" data-format="hh:mm" data-inputmask="hh:mm" name="end_time[]" id="end_time" data-rule-required="true" data-msg-required=""/>\
                                        <span class="input-group-addon add-on"><i class="fa fa-clock-o"></i></span>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    </div>\
                    ';

                    $.when($("#div-event-date").html(html)).then(function( data, textStatus, jqXHR ) {
                        $(":input").inputmask(); 
                        $('.timepicker').datetimepicker({
                            timePicker24Hour: true,
                            pickDate: false,
                            timeFormat:  "hh:mm",
                            pickSeconds: false,
                            minuteStep: 1,
                            container: '.modal-content'
                        });
                    });                    
                }
            });

            $('#event-create-form input[name=soum_district]').select2({data: ""}).select2("enable", false);
            $('#event-create-form input[name=bag_khoroo]').select2({data: ""}).select2("enable", false);

            $('#img_canvas').croppie('destroy');
            $basic = $('#img_canvas').croppie({
                enableExif: true,
                viewport: {
                    width: imgWidth,
                    height: imgHeight,
                    type: 'square'
                },
                boundary: { 
                    width: 600, 
                    height: 600 
                },
                showZoomer: true,
                enableOrientation: true
            });

            $('#btn-upload').on('change', function(){
                if (this.files && this.files[0]) {
                    if ( this.files[0].type.match(/^image\//) ) {
                        var reader = new FileReader();
                        reader.onload = function(evt) {
                            img = new Image();
                            
                            img.onload = function() {
                                $basic.croppie('bind', {
                                    url: evt.target.result,
                                    orientation: 1,
                                    zoom: 0
                                });                 
                            }
                            img.src = evt.target.result;
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                    else {
                        alert("Invalid file type! Please select an image file.");
                    }
                }
                else {
                    alert('No file(s) selected.');
                }
            });

            $('.rotate').on('click', function(ev) {
                $basic.croppie('rotate', parseInt($(this).data('deg')));
            });

            var $validator = $('#event-create-form').validate({
                ignore: [],
                highlight:function(element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function(element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    formData.set('croppedData', croppedData);
                    formData.set('orginalData', orginalData);

                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        success: function(response) {
                            eventTable.draw();
                            
                            $('#eventModal').find("#close").trigger('click');
                            $('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
                        },
                        error: function (xhr, textStatus, error) {
                            console.log(xhr.statusText);
                            console.log(textStatus);
                            console.log(error);
                        },
                        async: false,
                        processData: false,
                        contentType: false        
                    });
                },
                errorPlacement: function(error, element) {
                    if($(element).parents('.form-group').find(".error-here")){
                        error.appendTo($(element).parents('.form-group').find(".error-here"));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $('#validation-wizard').bootstrapWizard({
                'onNext': function(tab, navigation, index) {
                    var $valid = $("#event-create-form").valid();
                    if(!$valid) {
                        $validator.focusInvalid();
                    }
                    
                    if(index == 2)
                    {
                        role = $("#event-create-form select[name^=roles]")[0];
                        oganizars = $(role).closest('.form-group').find('input[name^=organizations]').val();

                        
                        $("#event-create-form input[name=organization_branch]").val(null).trigger('change.select2');
                        if(oganizars.length > 0)
                        {
                            $("#event-create-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 0;
                        }
                        else
                        {
                            $("#event-create-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 3;
                        }
                        $(".ol-unselectable").css('display', 'block');
                    }
                },
                onTabClick: function(tab, navigation, index) {
                    var $valid = $("#event-create-form").valid();
                    if(!$valid) {
                        $validator.focusInvalid();
                    }

                    if(index == 2)
                    {
                        role = $("#event-create-form select[name^=roles]")[0];
                        oganizars = $(role).closest('.form-group').find('input[name^=organizations]').val();

                        
                        $("#event-create-form input[name=organization_branch]").val(null).trigger('change.select2');
                        if(oganizars.length > 0)
                        {
                            $("#event-create-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 0;
                        }
                        else
                        {
                            $("#event-create-form input[name=organization_branch]").data("select2").opts.minimumInputLength = 3;
                        }
                    }
                    $(".ol-unselectable").css('display', 'block');
                }
            });
            
            $("#btn-row-add").on('click', function()
            {
                var selCount = $("#event-create-form select[name^=roles]:first option").size();
                var sel = $("#div-form-group");
                var clone = sel.clone(true, true);

                i ++;
                if(selCount >= i)
                {
                    clone.attr("id", "organizationId"+i);
                    clone.show();
                    clone.insertBefore('#div-form-group').find('input').select2({
                        width: 'resolve',
                        tags: true,
                        tokenSeparators: [',', ' '],
                        dropdownAutoWidth : true,
                        placeholder: "-- {{ trans('display.general_select') }} --",
                        ajax: {
                            type: 'GET',
                            url: '{!! route('organization.by.tree') !!}',
                            data: function (params) {
                                return {
                                    q: params
                                };
                            },
                            processResults: function (data) {
                                return {results: data}
                            },
                            cache: true
                        },
                        id: 'id',
                        closeOnSelect: true,
                        allowClear: true,
                        maximumSelectionLength: 30,
                        minimumInputLength: 3,
                        formatSelection: function (item) {
                            return item.name;
                        },
                        formatResult: function (item) {
                            return item.name;
                        }
                    });
                    
                    clone.find("select option").eq(i-1).prop("selected", 'selected');
                    clone.find("select option:selected").prop("disabled", true);
                }
            });

            $("#event-create-form input[name=organization_branch]").select2({
                width: 'resolve',
                tags: true,
                tokenSeparators: [',', ' '],
                dropdownAutoWidth : true,
                placeholder: "-- {{ trans('display.general_select') }} --",
                ajax: {
                    type: 'GET',
                    url: '{!! route('organization.by.name') !!}',
                    data: function (params) {
                        return {
                            orgIds: oganizars,
                            q: params
                        };
                    },
                    processResults: function (data) {
                        return {results: data}
                    },
                    cache: true
                },
                id: 'id',
                closeOnSelect: true,
                allowClear: true,
                maximumSelectionLength: 10,
                //minimumInputLength: 3,
                formatSelection: function (item) {
                    return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });

            $('#event-create-form input[name=object_locations]').tagsinput({
                freeInput: false,
                maxTags: 20,
                itemValue: function(item) {
                    return item.id;
                },
                itemText: function(item) {
                    return item.text;
                }
            });

            $('#event-create-form input[name=object_datas]').tagsinput({
                freeInput: false,
                maxTags: 20
            });

            $('#event-create-form input[name=organization_branch]').on('select2-selecting', function (e) {
                //console.log(e.choice.firstname);
                $('#event-create-form input[name=object_locations]').tagsinput('add', {id: e.choice.address.object_location_id, text: e.choice.address.object_location.object_name});
                $.ajax({
                    url: '/location/object/find/'+e.choice.address.object_location_id,
                    type: 'GET',
                    success: function(response) {
                        if (response != "") {
                            addSelectedLocationToMap(map, 'selectObject', response[1]);
                        }
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false,
                    cache: true,
                    processData: false,
                    contentType: false        
                });
                
            }).on("select2-removing", function(e) {
                $('#event-create-form input[name=object_locations]').tagsinput('remove', e.address.object_location_id);
            });

            $("#event-create-form select[name=aimag_city]").on("change", function()
            {
                var aimagId = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/location/unit/soumDistrict',
                    data: {aimagCityId: aimagId},
                    success: function (data) {
                        $('#event-create-form input[name=soum_district]').select2({
                            placeholder: "-- {{ trans('display.soum_district') }} --",
                            data: {results: JSON.parse(data), text: function (item) {
                                return item.name;
                            }},
                            id: 'id',
                            closeOnSelect: true,
                            allowClear: true,
                            formatSelection: function (item) {
                                return item.name;
                            },
                            formatResult: function (item) {
                                return item.name;
                            },

                        }).select2("enable", true);
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                });

                $('#event-create-form input[name=bag_khoroo]').select2({data: ""}).select2("enable", false);
            });

            $("#event-create-form input[name=soum_district]").on("change", function()
            {
                var soumId = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/location/unit/bagKhoroo',
                    data: {soumDistrictId: soumId},
                    success: function (data) {
                        $('#event-create-form input[name=bag_khoroo]').select2({
                            placeholder: "-- {{ trans('display.bag_khoroo') }} --",
                            data: {results: JSON.parse(data), text: function (item) {
                                return item.name;
                            }},
                            id: 'id',
                            closeOnSelect: true,
                            allowClear: true,
                            formatSelection: function (item) {
                                return item.name;
                            },
                            formatResult: function (item) {
                                return item.name;
                            },

                        }).select2("enable", true);
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                });    
            });

            $("#btn-zoom-unit").on('click', function(){
                const urlParams = new URLSearchParams($("#event-create-form").serialize());

                if(!!urlParams.get('bag_khoroo') && urlParams.get('bag_khoroo') != '')
                {
                    locationType = "Bag";
                    showLocationId = $("#event-create-form input[name=bag_khoroo]").val();
                }
                else if(!!urlParams.get('soum_district') && urlParams.get('soum_district') != '')
                {
                    locationType = "Soum";
                    showLocationId = $("#event-create-form input[name=soum_district]").val();
                }
                else if(!!urlParams.get('aimag_city') && urlParams.get('aimag_city') != '')
                {
                    locationType = "Aimag";
                    showLocationId = $("#event-create-form select[name=aimag_city]").val();
                }
                else 
                {
                    $.alert({
                        title: '{!! trans('messages.info_title') !!}',
                        content: 'Засаг захиргааны хил сонгоно уу!'
                    });
                    return false;
                }
                map.updateSize();
                changeLayerVisible(map, 'objectLayer', true, geoserver, addObjectLayerMethodName, [showLocationId], true);
                addLocationById(map, 'selectedLocation', showLocationId, locationType);
            });

            $("#btn-submit").on('click', function()
            {
                $basic.croppie('result', {
                    type: 'base64',
                    size: 'orginal'
                }).then(function (resp) {
                    orginalData = resp;
                    $basic.croppie('result', {
                        type: 'base64',
                        size: {
                            width: orgWidth,
                            height: orgHeight
                        }
                    }).then(function (resp) {
                        croppedData = resp;
                        $("#event-create-form").submit();
                    });
                });
            });

            $(this).off('shown.bs.modal');
        });

        $('#eventModal').on('hidden.bs.modal', function(){
            $('#eventModal .modal-content').empty();
        });
    }

}).ajaxStart($.blockUI).ajaxStop($.unblockUI);
</script>
@endsection