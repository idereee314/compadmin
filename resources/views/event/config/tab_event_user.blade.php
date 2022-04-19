<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Тэмцээний тохиргоо  
                <span class="text-muted pt-2 font-size-sm d-block">Тэмцээн удирдах хэрэглэгчийн жагсаалт</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="javascript:;" class="btn btn-primary font-weight-bolder" id="event-user-add">
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
            <!--begin::Table-->
            <table class="table table-separate table-head-custom dtr-inline">
                <thead>
                    <tr>
                        <th>#</th>
                        <th class="min-w-200px text-left">{{trans('display.human_lastname')}}</th>  
                        <th class="min-w-125px text-left">{{trans('display.human_firstname')}}</th>
                        <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                        <th class="min-w-150px text-center">{{trans('display.general_manage')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(@$eventUsers as $eventUser)
                    <tr>
                        <td class="pl-0 py-4 text-center">{{ ++$loop->index }}</td>
                        <td class="pl-0">{{$eventUser->user->lastname}}</td>
                        <td class="text-left">{{$eventUser->user->firstname}}</td>
                        <td class="text-center">{{$eventUser->created_at}}</td>
                        <td class="text-center pr-0">
                            <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm delete-event-user" data-eventuserid="{{ $eventUser->id }}">
                                <span class="svg-icon svg-icon-md svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/General/Trash.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero" />
                                            <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">{{ trans('display.general_no_record') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!--end::Table-->
        </div>
    </div>
</div>
<script>
$("#event-user-add").on('click', function(){
    var eventId = $("#event_id").val();

	$.get('{!! route('event.user.create') !!}?eventId='+eventId, function( data ) {
        $('#eventConfigModal').modal();
        $('#eventConfigModal').on('shown.bs.modal', function(){
            $('#eventConfigModal .modal-content').html(data);
            $('.selectpicker').selectpicker();

            $('#create-event-user-form select[id=user_id]').select2();

            $('#create-event-user-form select[id=user_id]').select2({
                width: 'resolve',
                dropdownAutoWidth : true,
                dropdownParent: $('#eventConfigModal'),
                placeholder: "-- {{ trans('display.general_select') }} --",
                minimumInputLength: 3,
                ajax: {
                    url: '{!! route('system.user.search') !!}',
                    delay: 1500,
                    data: function (params) {
                        var query = {
                            q: params.term
                        }
                        return query;
                    },

                    processResults: function (data) {
                        return {
                            results: JSON.parse(data)
                        };
                    },
                    cache: true
                },
                templateSelection: function (item) {
                    return item.firstname;
                },
                templateResult: function (item) {
                    return item.firstname;
                }
            });

            $('#create-event-user-form').validate({
                ignore: [],
                highlight:function(element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function(element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    formData.append('eventId', eventId);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: new FormData(form),
                        success: function(response) {
                            if(response.status == 'success')
                            {
                                $('#eventConfigModal').find("#close").trigger('click');   
                                $("#config_tabs").find("li a.active").trigger('click');
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

        $('#eventConfigModal').on('hidden.bs.modal', function(){
            $('#eventConfigModal .modal-content').empty();
        });
	});
});

$(".delete-event-user").on('click', function(){
    var eventUserId = $(this).data("eventuserid");

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
                url: '/event/user/'+eventUserId,
                type: 'DELETE',
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $("#config_tabs").find("li a.active").trigger('click');
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
</script>