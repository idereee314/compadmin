<div class="card card-custom">
    <div class="card-header card-header-tabs-line">
        <div class="card-title">
            <h3 class="card-label"><strong>{{ $eventTeamRegistration->team->name }} {{trans('display.general_team_athlete')}}</strong></h3>
        </div>
    </div>
    <div class="card-body">     
        <div class="d-flex align-items-center flex-wrap justify-content-between row">
            <div class="col-md-7">    
                <!--  -->
            </div>
            <div class="col-md-5">
                <!--begin::Progress-->
                <div class="d-flex mt-4 mt-sm-0 float-right">
                    <span class="font-weight-bold mr-4">Бүртгэлийн явц</span>
                    <div class="progress progress-xs mt-2 mb-2 flex-shrink-0 w-200px w-xl-250px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{@$progressPercent}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="font-weight-bolder text-dark ml-4">{{ @$progressPercent }}%</span>
                </div>
                <!--end::Progress-->
            </div>
        </div>  

        <input type="hidden" name="event_id" id="event_id" value="{{ $event_id }}"/>
        <input type="hidden" name="team_id" id="team_id" value="{{ $eventTeamRegistration->team->id }}">

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-head-custom" id="event-team-member-registration-datatable">
                <button class="btn btn-light-danger rounded-left btn-square font-weight-bolder mb-4 add_ath" id="team_athlete_add"><i class="la la-plus"></i>{{trans('display.comp_add_member')}}</button>
                <button class="btn btn-light-success rounded-right btn-square font-weight-bolder mb-4"><i class="far fa-address-card"></i> Мандат хэвлэх </button>
                <!-- <button class="btn btn-light-warning rounded-right btn-square font-weight-bolder mb-4"><i class="fa fa-print"></i> Багийн тамирчдын жагсаалт {!! trans('display.general_excel') !!}</button> -->
                
                @if(count($eventTeamRegistration->teamathlete) > 0)
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="min-w-250px text-center">{{trans('display.comp_member')}}</th>
                        <!-- <th class="min-w-80px text-center">{{trans('display.athlete_jersey_number')}}</th>
                        <th class="min-w-80px text-center">{{trans('display.voll_rank')}}</th>
                        <th class="min-w-80px text-center">{{trans('display.voll_role')}}</th>
                        <th class="min-w-50px text-center">{{trans('display.athlete_height')}}</th> -->
                        <th class="min-w-100px text-center">{{trans('display.comp_academy_name')}}</th>
                        <th class="min-w-100px text-center">{{trans('display.general_team')}}</th>
                        <th class="min-w-50px text-center">{{trans('display.comp_entry_age')}}</th>
                        <th class="min-w-50px text-center">{{trans('display.human_gender_code')}}</th>
                        <th class="min-w-100px text-center">{{trans('display.general_status')}}</th>
                        <th class="min-w-100px text-center">{{trans('display.general_created_at')}}</th>
                        <th class="min-w-120px text-center">{{trans('display.general_action')}}</th>
                    </tr>
                </thead>
                <tbody>

                @foreach($eventTeamRegistration->teamathlete as $athlete)
                    <tr>
                    <!-- <strong>{{ Config::get("enums.team_lead")[@$athlete->is_team_lead] }}</strong> -->
                        <td class="text-center border-right">{{ ++$loop->index }} </td>
                        <td class="text-center border-right">
                            <div class="d-flex align-items-center">
                                @if(@$athlete->member->profile_url xor ((@env('production') && \Storage::disk('s3')->exists($athlete->member->profile_url)) || @env('local')))
                                    <a href="javascript:;" class="show-image" data-id="{{$athlete->member->id}}" data-type="profile"><div class="symbol symbol-60 flex-shrink-0">
                                        <img src="{{\Storage::disk('s3')->url($athlete->member->profile_url)}}" alt="Profile">
                                    </div></a>
                                @endif
                                <div class="ml-3">                                            
			                        <span class="text-dark-75 line-height-sm d-block pb-3" style="white-space: nowrap;">{{$athlete->member->lastname}} <strong>{{$athlete->member->firstname}}</strong></span>
                                    <span class="text-dark-75 line-height-sm d-block pb-2"><i class="la la-address-book"></i>{{$athlete->member->register_number}}, <i class="la la-phone"></i>{{$athlete->member->contact_phone}}, <i class="la la-birthday-cake"></i>{{$athlete->member->birth}}</span>
			                    </div>
                            </div>
                        </td>
                        <!-- <td class="text-center border-right"></td>
                        <td class="text-center border-right"></td>
                        <td class="text-center border-right"></td>
                        <td class="text-center border-right"></td>                                 -->
                        <td class="text-center border-right">{{ $eventTeamRegistration->academy->name }}</td>
                        <td class="text-center border-right">{{ $eventTeamRegistration->team->name }}</td>
                        <td class="text-center border-right">{{ @$athlete->member->age }}</td>
                        <td class="text-center border-right">{{ Config::get("enums.gender_code")[@$athlete->member->gender_code] }}</td>
                        <td class="text-center border-right"><button type="button" class="btn btn-light-{{ @Config::get('smart.event_registration_status_class')[$athlete->status]}} btn-sm btn-status" data-registrationid="{{$athlete->id}}">{{@Config::get('enums.event_registration_status')[$athlete->status] }}</button></td>
                        <td class="text-center border-right">{{ $athlete->created_at }}</td>
                        <td class="text-center border-right">
                            @if ($athlete->member->id_url)
			                    <a class="btn btn-icon btn-clean btn-sm mr-3 show-image" data-id="{{$athlete->member->id}}" data-type="id" title="{{trans('display.id_photo')}}"><i class="far fas fa-paperclip text-warning"></i></a>
                            @endif

                            <a id="team_athlete_edit" class="btn btn-icon btn-light btn-hover-primary btn-sm mr-3 edit" href="javascript:;" data-registrationid="{{$athlete->id}}" title="{{trans('display.general_edit')}}"><i class="la la-edit"></i></a>
                            <!-- if($qry->source_type == @Config::get('smart.event_registration_source_type')['admin'] && (empty($qry->status) || $qry->status == @Config::get('smart.event_registration_status')['created']))
						    {  -->
                                <a class="btn btn-icon btn-light btn-hover-primary btn-sm delete" href="javascript:;" data-registrationid="{{$athlete->id}}" title="{{trans('display.general_delete')}}"><i class="la la-trash"></i></li></a>
                            <!-- } -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                @else
                    <tr>
                        <td colspan="12" class="text-center"><strong>{{ trans('display.general_team_no_athlete') }}</strong></td>
                    </tr>
                @endif                
            </table>
        </div>        
    </div>
    <div class="modal-footer text-right bg-gray-100 border-top-0">
        <button type="button" id="close" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">{{trans('display.general_close')}}</button>
    </div>
</div> 

<script src="{{asset('assets/js/plugins/custom/datatables/datatables.bundle.js')}}"></script>

<script>
// $(document).ready(function() {
//     eventTable = $("#event-team-member-registration-datatable").DataTable({
//         dom: "<'row'<'col-sm-6 text-left'B><'col-sm-6 text-right'<'#colvis'>>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>",
//         buttons: [
//             {
//                 extend: 'excelHtml5',
//                 text: '<i class="fa fa-print"></i>Багийн жагсаалт {!! trans('display.general_excel') !!}',
//                 className: "btn btn-light-warning font-weight-bolder mb-2",
//                 title: 'Тэмцээнд оролцогч багийн жагсаалт',
//                 customize: function ( xlsx ) {
//                     var sheet = xlsx.xl.worksheets['sheet1.xml'];
//                     $('c[r=A1] t', sheet).text( '{{ $eventTeamRegistration->team->name }} багийн тэмцээнд оролцох тамирчдын жагсаалт' );
//                 },
//                 exportOptions: {
//                     columns: [ 0,1,2,3,4,5,6],
//                     modifier: {
//                         order: 'current',
//                         page: 'all',
//                         focused: undefined,
//                         selected: undefined
//                     }
//                 }
//             },
//         ]
// 	});

    $('#team_athlete_add').on( 'click', function () {
        var eventId = $('#event_id').val();
        var teamId = $('#team_id').val();
        $.get('registration/team/member/create?event_id=' + eventId + '&team_id=' + teamId, showAddAthleteModal);
    });

    
    $('#event-team-member-registration-datatable').on( 'click','tr td a.edit', function () {
        var id = $(this).data("registrationid");
        var eventId = $('#event_id').val();
        var teamId = $('#team_id').val();

        $.get('registration/team/member/'+id+'/edit?event_id=' + eventId + '&team_id=' + teamId, showEditAthleteModal);
    });

    $('#event-team-member-registration-datatable tbody').on( 'click', 'tr td button.btn-status', function () {
        var id = $(this).data("registrationid");
        
        $.get('registration/team/member/change/status?reg_id='+id, showStatusModal);
    });

    $('#event-team-member-registration-datatable tbody').on( 'click', 'tr td a.show-image', function () 
    {
        var id = $(this).data("id");
        var type = $(this).data("type");

        $.get('/member/show/image/'+type+'/'+id, function( data ) {
            $('#showImageModal').modal();
            $('#showImageModal').on('shown.bs.modal', function(){
                $('#showImageModal .modal-content').html(data);

                $(this).off('shown.bs.modal');
            });

            $('#showImageModal').on('hidden.bs.modal', function(){
                $('#showImageModal .modal-content').empty();
            });
        });
    });

    $('#event-team-member-registration-datatable tbody').on( 'click', 'tr td a.delete', function () {
        var id = $(this).data("registrationid");

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
                    url: 'registration/team/member/' + id,
                    type: 'DELETE',
                    success: function(response) {
                        if(response.status == 'success')
                        {
                            toastr.success(response.msg);
                            eventTable.draw();
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

    function showStatusModal(data){
        $('#showImageModal').modal();
        $('#showImageModal').on('shown.bs.modal', function(){
            $('#showImageModal .modal-content').html(data);
            $('.selectpicker').selectpicker();

            $('#change-status-form select[name=status]').on('change', function(){
                var status = $(this).val(); 

                if(status == '{{ @Config::get('smart.event_registration_status')['created']}}')
                {
                    $(".payment").hide();
                    $(".payment").find(':input').prop('disabled', true);
                }
                else 
                {
                    $(".payment").show();
                    $(".payment").find(':input').prop('disabled', false);

                    if(status == '{{ @Config::get('smart.event_registration_status')['approved']}}')
                    {
                        $('#change-status-form input[name=payment_status]').prop("checked", true);
                    }
                    else 
                    {
                        $('#change-status-form input[name=payment_status]').prop("checked", false)
                    }
                }
            }) 

            $('#change-status-form').validate({
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
                        data: new FormData(form),
                        success: function(response) {
                            var page = eventTable.page.info().page;
                            if(response.status == 'success')
                            {
                                $('#showImageModal').find("#close").trigger('click');
                                toastr.success(response.msg);
                                eventTable.page(page).draw('page');
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

            $(this).off('shown.bs.modal');
        });

        $('#showImageModal').on('hidden.bs.modal', function(){
            $('#showImageModal .modal-content').empty();
        });
    }

    //Modal
    function showAddAthleteModal( data ) {

    $('#showImageModal').modal();
    $('#showImageModal').on('shown.bs.modal', function(){
        $('#showImageModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        $('#create-event-team-member-registration-form select[name=member_id]').select2();        
        $('#create-event-team-member-registration-form select[name=member_id]').select2({
            width: 'resolve',
            dropdownAutoWidth : true,
            dropdownParent: $('#showImageModal'),
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

        $('#create-event-team-member-registration-form').validate({
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
                    data: new FormData(form),
                    success: function(response) {
                        if(response.status == 'success')
                        {
                            $('#showImageModal').find("#close").trigger('click');
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

        $(this).off('shown.bs.modal');
    });

    $('#showImageModal').on('hidden.bs.modal', function(){
        $('#showImageModal .modal-content').empty();
    });
    }

    function showEditAthleteModal(data){
    $('#showImageModal').modal();
    $('#showImageModal').on('shown.bs.modal', function(){
        $('#showImageModal .modal-content').html(data);
        $('.selectpicker').selectpicker();

        $('#update-event-team-member-registration-form').validate({
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
                    data: new FormData(form),
                    success: function(response) {
                        var page = eventTable.page.info().page;
                        if(response.status == 'success')
                        {
                            $('#showImageModal').find("#close").trigger('click');
                            toastr.success(response.msg);
                            eventTable.page(page).draw('page');
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

        $('#update-event-team-member-registration-form select[name=status]').trigger('change');

        $(this).off('shown.bs.modal');
    });

    $('#showImageModal').on('hidden.bs.modal', function(){
        $('#showImageModal .modal-content').empty();
    });
    }
// });
</script>

