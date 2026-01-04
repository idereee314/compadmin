<div class="form-group">
	<div class="pull-left">
		<button class="btn btn-theme" type="button" id="organizer-add">{{trans('display.general_new')}}</button>
	</div>
	<div class="clearfix"></div>
</div>
<div class="table-responsive mb-20">
	<table class="table table-theme table-hover">
		<thead>
            <tr>
                <th class="text-center border-right" width="30px">No.</th>
                <th width="">{{trans('display.organization')}}</th>
                <th width="8%">{{trans('display.general_manage')}}</th>
            </tr>
		</thead>
		<tbody>
			@forelse($organizations as $key => $organization)
			<tr>
				<td colspan="3"><strong>{{ @Config::get('enums.event_organization_role')[$key] }}</strong></td>
			</tr>
			@foreach($organization as $org)
			<tr>
				<td class="text-center border-right">{{ ++$loop->index }}.</td>
				<td class="text-left border-right">{{ $org->organization->name }}</td>
				<td class="text-center">
					<a href="javascript:;" class="btn btn-circle btn-primary edit" data-id="{{ $org->id }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('display.general_update') }}"><i class="fa fa-pencil"></i></a>
					<a href="javascript:;" class="btn btn-circle btn-danger delete" data-id="{{ $org->id }}" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('display.general_delete') }}"><i class="fa fa-times"></i></a>
				</td>
			</tr>
			@endforeach
			@empty
			<tr>
				<td colspan="3">{{ trans('display.no_record') }}</td>
			</tr>
			@endforelse		
		</tbody>		
	</table>
</div>
<script>
$("#organizer-add").on('click', function(){
	var eventId = $("#event_id").val();

	$.get('{!! route('event.organizer.create') !!}?eventId='+eventId, function( data ) {
		$('#organizerModal').modal();
		$('#organizerModal').on('shown.bs.modal', function(){
			$('#organizerModal .modal-content').html(data);

			$("#organizer-add-form input[name=organizations]").select2({
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
                minimumInputLength: 3,
                formatSelection: function (item) {
                    return item.name;
                },
                formatResult: function (item) {
                    return item.name;
                }
            });

			$('#organizer-add-form').validate({
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
							$('#organizerModal').find("#close").trigger('click');
							$('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
							
							var tab_id = $("#event_tabs").find("li.active a").data("tabid");
							$(".tab-content").find("#" + tab_id).empty();
							$("#event_tabs").find("li.active a").trigger('click');
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
		$('#organizerModal').on('hidden.bs.modal', function(){
			$('#organizerModal .modal-content').empty();

		});
	});

	$(".edit").off().on('click', function(){
		var id = $(this).data("id");

		$.get('/listing/event/organizer/'+id+'/edit', function( data ) {
			$('#organizerModal').modal();
			$('#organizerModal').on('shown.bs.modal', function(){
				$('#organizerModal .modal-content').html(data);
				
				$('#organizer-edit-form').validate({
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
								$('#organizerModal').find("#close").trigger('click');

								var tab_id = $("#event_tabs").find("li.active a").data("tabid");
								$(".tab-content").find("#" + tab_id).empty();
								$("#event_tabs").find("li.active a").trigger('click');
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
					}
				});

				$(this).off('shown.bs.modal');
			});
			$('#organizerModal').on('hidden.bs.modal', function(){
				$('#organizerModal .modal-content').empty();
			});
			
		});
	});

	$(".delete").on('click', function(){
		var id = $(this).data("id");
		$.confirm({
			title: '{{trans('messages.warning_title')}}',
			content: '{{trans('messages.confirm_delete_content')}}',
			confirmButton: 'Тийм',
			cancelButton: 'Үгүй',
			autoClose: 'cancel|10000',
			icon: 'fa fa-warning',
			theme: 'hololight',
			backgroundDismiss: false,
			confirm: function () 
			{
				$.ajax({
					url: '/listing/event/register/organizer/'+id,
					type: 'DELETE',
					data: 'eventId=' + eventId+'&_token={{ csrf_token() }}',
					success: function (response) {
						$('#preloader').hide();		
							$('.form-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
							
							var tab_id = $("#event_tabs").find("li.active a").data("tabid");
							$(".tab-content").find("#" + tab_id).empty();
							$("#event_tabs").find("li.active a").trigger('click');
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