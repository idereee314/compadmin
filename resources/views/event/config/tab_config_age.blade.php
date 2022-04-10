<div class="tab-pane fade show active" id="kt_tab_pane_11_3" role="tabpanel" aria-labelledby="kt_tab_pane_11_3">
    <!--begin::Table-->
    <div class="form-group">
        <div class="float-left">
            <a href="#" class="btn btn-text-primary btn-light-primary font-weight-bold ml-2" id="entry-add-age">{{trans('display.general_new')}}</a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="table-responsive mb-20">
        @if($configAges->count() > 0)
        <table class="table table-borderless table-vertical-center">
            <thead>
                <tr>
                    <th class="p-0 w-40px text-center">#</th>
                    <th class="p-0 min-w-200px text-left">Төрөл</th>
                    <th class="p-0 min-w-200px text-left">Доод нас</th> 
                    <th class="p-0 min-w-100px text-left">Дээд нас</th>
                    <th class="p-0 min-w-125px text-center">{{trans('display.possible_belts')}}</th>
                    <th class="p-0 min-w-110px text-center">{{trans('display.general_created_at')}}</th>
                    <th class="p-0 min-w-150px text-center">{{trans('display.general_manage')}}</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;?>
			    @foreach($configAges as $age)
                <tr>
                    <td class="pl-0 py-4 text-center">{{$i}}</td>
                    <td class="pl-0">{{@$age->entry->name}} / {{Config::get('enums.gender_code')[@$age->entry->gender_code]}}</td>
                    <td class="pl-0">{{$age->start_age}}</td>
                    <td class="text-left">{{$age->end_age}}</td>
                    <td class="text-center">{{$age->possible_belts}}</td>
                    <td class="text-center">{{$age->created_at}}</td>
                    <td class="text-center pr-0">
                        <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3" onclick="updateRecord({{$age->id}})">
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
                        <a href="#" class="btn btn-icon btn-light btn-hover-primary btn-sm" onclick="deleteRecord({{$age->id}})">
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
                <?php $i ++;?>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <!--end::Table-->
</div>

<script>
$("#entry-add-age").on('click', function(){
    var eventId = $("#event_id").val();

	$.get('{!! route('event.entry.age.create') !!}?eventId='+eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);

			$('#create-event-entry-age-form').validate({
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
                        //+'&entry_id='+entryId+'&_token={{ csrf_token() }}'
						beforeSend: function() {
							$('#preloader').show();
						},
						success: function(response) {
							$('#preloader').hide();
                            var tab_id = $("#config_tabs").find("li.active a").data("tabid");
                            $(".tab-content").find("#" + tab_id).empty();

                            $("#config_tabs").find("li.active a").trigger('click');
                            $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
						},
						error: function (xhr, textStatus, error) {
							console.log(xhr.statusText);
							console.log(textStatus);
							console.log(error);
						},
						async: false          
					}).done(function(data) {
						//submitButton.prop('disabled', false);
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

function updateRecord(id){
    var eventId = $("#event_id").val();
	$.get('/event/entry/age/'+id+'/edit?eventId=' + eventId, function( data ) {
		$('#eventEntryModal').modal();
		$('#eventEntryModal').on('shown.bs.modal', function(){
			$('#eventEntryModal .modal-content').html(data);

			$('#update-event-entry-age-form').validate({
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
                            var tab_id = $("#config_tabs").find("li.active a").data("tabid");
                            $(".tab-content").find("#" + tab_id).empty();

                            $("#config_tabs").find("li.active a").trigger('click');
                            $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
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
}

function deleteRecord(id){
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
				url: '/event/entry/age/'+id,
				type: 'DELETE',
				success: function (response) {
					$('#preloader').hide();		
                    var tab_id = $("#config_tabs").find("li.active a").data("tabid");
                    $(".tab-content").find("#" + tab_id).empty();

                    $("#config_tabs").find("li.active a").trigger('click');
                    $('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
				},
				error: function (xhr, textStatus, error) {
					console.log(xhr.statusText);
					console.log(textStatus);
					console.log(error);
				},
				async: false
			});
        }
    })
}
</script>