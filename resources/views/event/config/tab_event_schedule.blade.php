<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Тэмцээны хуваарь
                <span class="text-muted pt-2 font-size-sm d-block">Тэмцээны хуваарь гаргахад хэрэгцээтэй мэдээллүүд</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="javascript:;" class="btn btn-primary font-weight-bolder" id="entry-add-fee">
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
            <form action="">
                <div class="form-group row">
					<label class="col-md-3 col-form-label text-right">Дэвжээний тоо: <span class="text-danger">*</span></label>
					<div class="col-md-9 col-lg-6">
						<input class="form-control form-control-lg" type="text" value="" id="example-text-input">
					</div>
				</div>
                <div class="form-group row">
					<label class="col-md-3 col-form-label text-right">Өдрийн тоо: <span class="text-danger">*</span></label>
					<div class="col-md-9 col-lg-6">
						<input class="form-control form-control-lg" type="text" value="" id="example-text-input">
					</div>
				</div>
                <div class="form-group row">
					<label class="col-md-3 col-form-label text-right">Тэмцээн хэдэн цагт эхлэх: <span class="text-danger">*</span></label>
					<div class="col-md-9 col-lg-6">
						<input class="form-control form-control-lg" type="text" value="" id="example-text-input">
					</div>
				</div>
                <div class="form-group row">
					<label class="col-md-3 col-form-label text-right">Эхлэх ангилал: <span class="text-danger">*</span></label>
					<div class="col-md-9 col-lg-6">
						<input class="form-control form-control-lg" type="text" value="" id="example-text-input">
					</div>
				</div>
            </form>
        </div>
    </div>
</div>
<script>
$("#entry-add-fee").on('click', function(){
    var eventId = $("#event_id").val();

	$.get('{!! route('event.entry.fee.create') !!}?eventId='+eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);
            $('.selectpicker').selectpicker();
            
            $('#end_date').datepicker({
                rtl: KTUtil.isRTL(),
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd',
                templates: {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            })

			$('#create-event-entry-fee-form').validate({
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

$(".edit-fee").on('click', function(){
    var eventId = $("#event_id").val();
    var feeId = $(this).data("feeid");

	$.get('/event/entry/fee/'+feeId+'/edit?eventId=' + eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);
            $('.selectpicker').selectpicker();

            $('#end_date').datepicker({
                rtl: KTUtil.isRTL(),
                todayHighlight: true,
                orientation: "bottom left",
                format: 'yyyy-mm-dd',
                templates: {
                    leftArrow: '<i class="la la-angle-right"></i>',
                    rightArrow: '<i class="la la-angle-left"></i>'
                }
            })

			$('#update-event-entry-fee-form').validate({
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

$(".delete-fee").on('click', function(){
    var feeId = $(this).data("feeid");

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
                url: '/event/entry/fee/'+feeId,
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
})
</script>