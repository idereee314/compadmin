<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Тэмцээний дэвжээний тохиргоо</h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="javascript:;" class="btn btn-primary font-weight-bolder" id="mat-add-settings">
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
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table table-separate table-head-custom dtr-inline">
                    <thead>
                        <tr>
                            <th class="w-55px text-center">#</th>
                            <th class="min-w-200px text-center">{{trans('display.general_name')}}</th> 
                            <th class="min-w-100px text-center">{{trans('display.general_name_en')}}</th>
                            <th class="min-w-125px text-center">Prefix</th>
                            <th class="min-w-125px text-center">{{trans('display.general_calendar_day')}}</th>
                            <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                            <th class="min-w-150px text-center">{{trans('display.general_manage')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matSettings as $mat)
                        <tr>
                            <td class="text-center">{{++$loop->index}}</td>
                            <td class="text-center">{{$mat->name}}</td>
                            <td class="text-center">{{$mat->name_en}}</td>
                            <td class="text-center">{{$mat->prefix}}</td>
                            <td class="text-center">{{$mat->day_id}}</td>
                            <td class="text-center">{{$mat->created_at}}</td>
                            <td class="text-center pr-0">
                                <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-mat" data-matid="{{$mat->id}}">
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
                                <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm delete-mat" data-matid="{{$mat->id}}">
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
    </div>
</div>
<script>
$("#mat-add-settings").on('click', function(){
    var eventId = $("#event_id").val();

	$.get('{!! route('config.mat.create') !!}?eventId='+eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);

			$('#create-mat_settings-form').validate({
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
                                $('#eventEntryModal').find("#close").trigger('click');   
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
				},
				errorPlacement: function(error, element) {
					error.insertAfter(element);
				}
			});

			$(this).off('shown.bs.modal');
		});
		
		$('#eventEntryModal').on('hidden.bs.modal', function(){
			$('#eventEntryModal .panel-body').empty();
		});
		
	});
});

$(".edit-mat").on('click', function(){
    var eventId = $("#event_id").val();
    var matId = $(this).data("matid");

    $.get('/event/config/mat/'+matId+'/edit?eventId=' + eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);
            
			$('#update-mat-settings-form').validate({
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
                                $('#eventEntryModal').find("#close").trigger('click');   
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

		$('#eventEntryModal').on('hidden.bs.modal', function(){
			$('#eventEntryModal .modal-content').empty();
		});
	});
});

$(".delete-mat").on('click', function(){
    var matId = $(this).data("matid");
    
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
                url: '/event/config/mat/'+matId,
                type: 'DELETE',
                success: function (response) {
                    if(response.status == 'success')
                    {
                        $('#eventEntryModal').find("#close").trigger('click');   
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