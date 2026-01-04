<div class="form-group">
	<div class="pull-left">
		<button class="btn btn-theme" type="button" id="picture-add">{{trans('display.general_new')}}</button>
	</div>
	<div class="clearfix"></div>
</div>
<table id="features-list" class="table table-theme table-hover">
	<thead>
	<tr>
		<th class="text-center border-right" width="30px">No.</th>
		<th width="10%">{{trans('display.general_image')}}</th>
		<th class="text-center border-right" width="20%">{{trans('display.picture_type')}}</th>
		<th class="text-center border-right" width="60%">{{trans('display.general_url')}}</th>
		<th class="text-center border-right" width="5%">{{trans('display.general_manage')}}</th>
		
	</tr>
	</thead>
	<tbody>
		@forelse($pictures as $picture)
		<tr>
			<td class="text-center">{{ ++$loop->index }}</td>
			<td> 
				<div style="max-height:100px; max-width: 100px; justify-content: center; content: center; overflow: hidden">
					<img style="width: 100%; height: auto;" src="{{ @Config::get('smart.cloud_image_url').$picture->pictureType->dir_url.'/thumbnail/'.$picture->url}}" alt="...">
				</div>
			</td>
			<td class="text-center">{{$picture->pictureType->description}}</td>
			<td class="text-left">{{$picture->url}}</td>
			<td class="text-center">
				<a href="javascript:;" class="btn btn-circle btn-danger feature-delete" id="deleteBtn" onclick="deleteRecord({{$picture->id}})" data-toggle="tooltip" data-placement="top" data-original-title="'.trans('display.general_delete').'"><i class="fa fa-times"></i></a>
			</td>
		</tr>
		@empty
		<tr>
			<td colspan="3">{{trans('display.no_inserted_image')}}</td>
		</tr>
		@endforelse
	</tbody>		
</table>
<script>
$("#picture-add").on('click', function(){
	var eventId = $("#event_id").val();

	$.get('{!! route('event.picture.create') !!}?eventId='+eventId, function( data ) {
		$('#pictureModal').modal();
		$('#pictureModal').on('shown.bs.modal', function(){
			$('#pictureModal .modal-content').html(data);

			var orgWidth = null;
			var orgHeight = null;
			var $basic = null;
			var imgWidth = '250';
			var imgHeight = '250';
			var img = null;
			var croppedImageData;
			var orginalImageData;

			$basic = $('#canvas').croppie({
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
								orgWidth = $("#picture-add-form select[name=picture_type]").find('option:selected').data("width");
								orgHeight = $("#picture-add-form select[name=picture_type]").find('option:selected').data("height");

								imgWidth = Math.round(orgWidth / 2);
								imgHeight = Math.round(orgHeight / 2);
								
								$('#canvas').croppie('destroy');
								$basic = $('#canvas').croppie({
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

			$("#picture-add-form select[name=picture_type]").on('change', function(){
				orgWidth = $("#picture-add-form select[name=picture_type]").find('option:selected').data("width");
				orgHeight = $("#picture-add-form select[name=picture_type]").find('option:selected').data("height");

				imgWidth = Math.round(orgWidth / 2);
				imgHeight = Math.round(orgHeight / 2);

				$('#canvas').croppie('destroy');

				$basic = $('#canvas').croppie({
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

				if(img != null )
				{
					$basic.croppie('bind', {
						url: img.src,
						orientation: 1,
						zoom: 0
					});
				}
			});

			$('#picture-add-form').validate({
				ignore: [],
				highlight:function(element) {
					$(element).parents('.form-group').addClass('has-error has-feedback');
				},
				unhighlight: function(element) {
					$(element).parents('.form-group').removeClass('has-error');
				},
				submitHandler: function(form) {
					$(form).find('submit').prop('disabled', true);

					var original = $basic.croppie('result', {
						type: 'base64',
						size: 'orginal',
						format:'jpeg'
					}).then(function (resp) {
						orginalImageData = resp;
						var cropped = $basic.croppie('result', {
							type: 'base64',
							size: {
								width: orgWidth,
								height: orgHeight
							},
							format:'jpeg',
							//quality: 0.6
						}).then(function (resp) {
							croppedImageData = resp;
							$.ajax({
								url: form.action,
								type: form.method,
								data: {
									event_id: $("#event_id").val(),
									picture_type: $("#picture_type").val(),
									croppedData: croppedImageData,
									orginalData: orginalImageData
								},
								success: function(response) {
									$('#pictureModal').find("#close").trigger('click');
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
								async: false,         
							}).done(function(data) {
								$(form).find('submit').prop('disabled', false);
							});
						});
					});
				
				},
				errorPlacement: function(error, element) {
					if($(element).parents('.form-group').find(".error-here").length > 0){
						error.appendTo($(element).parents('.form-group').find(".error-here"));
					} else {
						error.insertAfter(element);
					}
				}
			});

			$("#picture-add-form select[name=picture_type]").trigger('change');

			$(this).off('shown.bs.modal');
		});

		$('#pictureModal').on('hidden.bs.modal', function(){
			$('#pictureModal .modal-content').empty();
		});
		
	});
});

function deleteRecord(id){
	var eventId = $("#event_id").val();

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
				url: '/listing/event/picture/'+id,
				type: 'DELETE',
				data: 'orgId=' + eventId,
				success: function (response) {
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
}

function showImage(id)
{
	$.get('/listing/event/picture/show/image/'+id, function( data ) {
        if (data.status) {
            $('#pictureShowModal').modal();
    
            $('#pictureShowModal').on('shown.bs.modal', function(){
                $('#pictureShowModal .modal-content').html(data.view);
            });

            $('#pictureShowModal').on('hidden.bs.modal', function(){
                $('#pictureShowModal .modal-content').empty();
                
                // $.ajax({
                //     url: '{!! route('event.picture.remove') !!}',
                //     type: "POST",
                //     error: function (xhr, textStatus, error) {
                //         console.log(xhr.statusText);
                //         console.log(textStatus);
                //         console.log(error);
                //     },
                //     async: false,
                //     processData: false,
                //     contentType: false        
                // });
            });
        }
        else
        {
            $('.form-sub-heading').empty().html(data.view).fadeIn().delay(5000).fadeOut();
        }
	});
}
</script>