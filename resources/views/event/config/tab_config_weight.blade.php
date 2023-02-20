<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">Тэмцээнд оролцох жин
                <span class="text-muted pt-2 font-size-sm d-block">Тэмцээнд оролцох боломжтой жинг наснаас хамааруулна</span></h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Button-->
                <a href="javascript:;" class="btn btn-primary font-weight-bolder" id="entry-add-weight">
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
                        <th class="w-75px text-center">#</th>
                        <th class="min-w-200px text-left">{{trans('display.age_title')}}</th> 
                        <th class="min-w-100px text-left">{{trans('display.weight')}}</th>
                        <th class="min-w-100px text-center">{{trans('display.comp_max_entry')}}</th>
                        <th class="min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                        <th class="min-w-150px text-center">{{trans('display.general_manage')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(@$configWeights as $key => $group)
                    <tr class="table-secondary">
                        <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{++$loop->index}}. {{ @$entries->where('id', @$key)->first()->fullname }}</td>
                    </tr>
                    @foreach($group as $keyAge => $age)
                    <tr class="table-secondary">
                        <td colspan="6" class="text-primary font-weight-bolder"><i class="mr-5"></i>{{$loop->parent->index+1}}.{{++$loop->index}}. {{ @$configAges[$key]->where('id', $keyAge)->first()->name }}</td>
                    </tr>
                    @foreach($age as $weight)
                    <tr>
                        <td class="text-center">{{$loop->parent->parent->index+1}}. {{$loop->parent->index+1}}. {{++$loop->index}}</td>
                        <td>{{$weight->age->name}}</td>
                        <td>{{$weight->weight}}</td>
                        <td class="text-center">{{$weight->max_entry}}</td>
                        <td class="text-center">{{$weight->created_at}}</td>
                        <td class="text-center pr-0">
                            <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-weight" data-weightid="{{$weight->id}}">
                                <span class="svg-icon svg-icon-md svg-icon-primary">
                                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg-->
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                            <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        </g>
                                    </svg>
                                    <!--end::Svg Icon-->
                                </span>
                            </a>
                            <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm delete-weight" data-weightid="{{$weight->id}}">
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
                    @endforeach
                    @endforeach
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
$("#entry-add-weight").on('click', function(){
    var eventId = $("#event_id").val();

    $.get('{!! route('event.entry.weight.create') !!}?eventId='+eventId, function( data ) {
        $('#eventEntryModal').modal();
        $('#eventEntryModal').on('shown.bs.modal', function(){
            $('#eventEntryModal .modal-content').html(data);
            $('.selectpicker').selectpicker();

            $('#create-event-entry-weight-form select[name=entry_id]').on('change', function(){
                var entryId = $(this).val();
                var jsonDataAge;

                $.ajax({
                    type: 'POST',
                    url: '{!! route('event.entry.age.by.entry') !!}',
                    data: {entry_id: entryId},
                    success: function (data) {
                        jsonDataAge = JSON.parse(data);
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                });

                $('#create-event-entry-weight-form input[name=entry_age_id]').select2({
                    placeholder: "-- {{ trans('display.general_select') }} --",
                    data: jsonDataAge,
                    id: 'id',
                    closeOnSelect: true,
                    allowClear: true,
                    templateSelection: function (item) {
                        return item.name;
                    },
                    templateResult: function (item) {
                        return item.name;
                    }
                });
            });

            $('#create-event-entry-weight-form').validate({
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

$(".edit-weight").on('click', function(){
    var eventId = $("#event_id").val();
    var weightId = $(this).data("weightid");

    $.get('/event/entry/weight/'+weightId+'/edit?eventId=' + eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
		    $('#eventEntryModal .modal-content').html(data);
            $('.selectpicker').selectpicker();
            
            $('#update-event-entry-weight-form select[name=entry_id]').on('change', function(){
                var entryId = $(this).val();
                var jsonDataAge;

                $.ajax({
                    type: 'POST',
                    url: '{!! route('event.entry.age.by.entry') !!}',
                    data: {entry_id: entryId},
                    success: function (data) {
                        jsonDataAge = JSON.parse(data);
                    },
                    error: function (xhr, textStatus, error) {
                        console.log(xhr.statusText);
                        console.log(textStatus);
                        console.log(error);
                    },
                    async: false
                });

                $('#update-event-entry-weight-form select[name=entry_age_id]').select2({
                    placeholder: "-- {{ trans('display.general_select') }} --",
                    data:jsonDataAge,
                    id: 'id',
                    closeOnSelect: true,
                    allowClear: true,
                    templateSelection: function (item) {
                        return item.name;
                    },
                    templateResult: function (item) {
                        return item.name;
                    }
                });

            });

			$('#update-event-entry-weight-form').validate({
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

$(".delete-weight").on('click', function(){
    var weightId = $(this).data("weightid");

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
                url: '/event/entry/weight/'+weightId,
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