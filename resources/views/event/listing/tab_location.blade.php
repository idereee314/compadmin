<div class="panel-sub-heading"></div>
<div class="panel-body">
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default add-panel" style="display: none">
				<div class="panel-heading">
					<div class="pull-left">
						<h3 class="panel-title">{{ trans('display.general_add') }}</h3>
					</div>
					<div class="pull-right">
						<button class="btn btn-sm" data-action="collapse" data-toggle="tooltip" data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="panel-body">
					<form method="POST" id="event-create-form" class="tab-content form-horizontal smart-form" action="{!! route('event.location.store') !!}">
						<input type="hidden" name="event_id" value="{{ @$event->id }}"/>
						<div class="form-body">
							<div class="form-group">
                                <label class="col-md-3 col-sm-6 text-right">{{trans('display.organization_branches')}}</label>
                                <div class="col-md-9 col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon" >
                                            <div class="ckbox ckbox-success">
                                                <input id="checkbox-success-all-org" type="checkbox" name="is_all_branch" value="1">
                                                <label for="checkbox-success-all-org"></label>
                                            </div>
                                        </span>
                                        <input type="hidden" name="organization_branch" id="organization_branch"/>
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-md-3 col-sm-6 text-right">{{trans('display.general_object')}}</label>
								<div class="col-md-9 col-sm-6">
									<input type="text" name="object_locations" id="object_locations" class="form-control" data-role="tagsinput" readonly/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 col-sm-6 text-right">{{trans('display.general_point')}}</label>
								<div class="col-md-9 col-sm-6">
									<input type="text" name="location_datas" id="location_datas" class="form-control" data-role="tagsinput" readonly/>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-close-panel" data-dismiss="modal">{{ trans('display.general_close') }}</button>
					<button type="submit" class="btn btn-success btn-save">{{ trans('display.general_save') }}</button>
				</div>
			</div>
			<table id="event-location-datatable" width="100%" class="table table-striped table-theme">
				<thead>
					<tr>
						<th class="text-center border-right" width="30px">No.</th>
						<th width="">{{trans('display.location')}}</th>
						<th width="">X</th>
						<th width="">Y</th>
						<th width="8%">{{trans('display.general_manage')}}</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
		<div class="col-md-8">
			<form action="javascript:;" id="search-unit-form">
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-btn">
							<select class="form-control inline" name="aimag_city" id="aimag_city">
								<option value="">-- {{ trans('display.aimag_city') }} --</option>
								@forelse(@$aimagCity as $city)
								<option value="{{ $city->id }}">{{ $city->name }}</option>
								@empty
								@endforelse
							</select>
						</span>
						<span class="input-group-btn">
							<input class="form-control" type="text" name="soum_district" id="soum_district"/>
						</span>
						<input class="form-control" type="text" name="bag_khoroo" id="bag_khoroo"/>
						<span class="input-group-btn">
							<button type="button" class="btn btn-info" id="btn-zoom-unit">Харах</button>
						</span>
					</div>
				</div>
			</form>
			<div id="mapid" class="map-sidebar-map" name="" style="width:100%; height:500px;"></div>
			<div id="mappopup" class="ol-popup" style="max-height: 500px;">
				<a href="#" id="mappopup-closer" class="ol-popup-closer"></a>
				<div id="mappopup-content">

				</div>
			</div>
		</div>
	</div>
</div>
<!-- LAYER -->
<script type="text/javascript" src="{{asset('js/script/base/corelayers.js')}}"></script>
<script type="text/javascript" src="{{asset('js/script/event/map_script_event.js')}}"></script>
<script>
var oganizars = {!! json_encode($event->organizations->where('role', @Config::get('smart.event_organization_role')['organizer'])->pluck('organization.id')->toArray()) !!}
$('#search-unit-form input[name=soum_district]').select2({data: ""}).select2("enable", false);
$('#search-unit-form input[name=bag_khoroo]').select2({data: ""}).select2("enable", false);

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

var eventTable = $("#event-location-datatable").DataTable({
	processing:     true,
	serverSide:     true,
	deferRender:    true,
	autoWidth:      false,
	filter:         false,
	responsive:     true,
	fixedHeader: {
		header: true,
		footer: false
	},
	dataType: 'json',
	paginationType: "full_numbers",
	ajax: {
		url: '{!! route('event.location.datalist') !!}',
		dataType: "JSON",
		type: 'POST',
		data: function ( d ) {
			d.event_id = {{ @$event->id }};
		}
	},
	columns: [
		{ 
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			},
			width: "30px"
		},
		{ data: 'address' },
		{ data: 'coord_x' },
		{ data: 'coord_y' },
		{ 
			data: 'action',
			orderable: false,
			searchable: false
		}
	],
	columnDefs: [ 
		{
			targets: [ 2, 3 ],
            visible: false,
		},
		{
			searchable: false,
			orderable: false,
			targets: [0, 4]
		},{
			class: "text-left border-right",
			targets: [1]
		},{
			class: "text-center",
			targets: [0, 4]
		}
	],
	order: [[ 0, "asc" ]],
	dom: '<"pull-left"B><"pull-right"l><"clear">tip',
	buttons: [
		{
			text: '<i class="fa fa-plus-square"></i> {!! trans('display.general_new') !!}',
			className: 'btn btn-theme',
			action: function ( e, dt, node, config ) {
				$(".add-panel").show();
			}
		},
	]
});

$('#event-location-datatable tbody').on( 'click', 'tr td a.location-delete', function () {
    var id = $(this).data('eventlocationid');

	$.confirm({
		title: '{{trans('messages.warning_title')}}',
		content: '{{trans('messages.confirm_delete')}}',
		confirmButton: 'Тийм',
		cancelButton: 'Үгүй',
		autoClose: 'cancel|10000',
		icon: 'fa fa-warning',
		theme: 'hololight',
		backgroundDismiss: false,
		confirm: function(){
			$.ajax({
				url: '/listing/event/location/'+id,
				type: 'DELETE',
				success: function(response) {            
					$('.panel-sub-heading').html(response).fadeIn().delay(5000).fadeOut();
					$(".btn-close-panel").trigger();
					eventTable.draw();
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
		cancel: function(){
		}
	});
});

$('#event-location-datatable tbody').on( 'dblclick', 'tr', function () {
	if($(this).hasClass('selected')) {
		$(this).removeClass('selected');
	}
	else {
		var id = this.id;

		eventTable.$('tr.selected').removeClass('selected');
		$(this).addClass('selected');
		
		if(id)
		{
			$.ajax({
				url: '/location/object/find/'+id,
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
		}
		else 
		{
			var lon = eventTable.row( this ).data().coord_x;
			var lat = eventTable.row( this ).data().coord_y;
			drawPoint(lon, lat);
		}
	}
});

$("#search-unit-form select[name=aimag_city]").on("change", function()
{
	var aimagId = $(this).val();
	$.ajax({
		type: 'POST',
		url: '/location/unit/soumDistrict',
		data: {aimagCityId: aimagId},
		success: function (data) {
			$('#search-unit-form input[name=soum_district]').select2({
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

	$('#search-unit-form input[name=bag_khoroo]').select2({data: ""}).select2("enable", false);
});

$("#search-unit-form input[name=soum_district]").on("change", function()
{
	var soumId = $(this).val();
	$.ajax({
		type: 'POST',
		url: '/location/unit/bagKhoroo',
		data: {soumDistrictId: soumId},
		success: function (data) {
			$('#search-unit-form input[name=bag_khoroo]').select2({
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
				orgIds: oganizars.join(','),
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

$('#event-create-form input[name=organization_branch]').on('select2-selecting', function (e) {
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

$("#btn-zoom-unit").on('click', function(){
	const urlParams = new URLSearchParams($("#search-unit-form").serialize());

	if(!!urlParams.get('bag_khoroo') && urlParams.get('bag_khoroo') != '')
	{
		locationType = "Bag";
		showLocationId = $("#search-unit-form input[name=bag_khoroo]").val();
	}
	else if(!!urlParams.get('soum_district') && urlParams.get('soum_district') != '')
	{
		locationType = "Soum";
		showLocationId = $("#search-unit-form input[name=soum_district]").val();
	}
	else if(!!urlParams.get('aimag_city') && urlParams.get('aimag_city') != '')
	{
		locationType = "Aimag";
		showLocationId = $("#search-unit-form select[name=aimag_city]").val();
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

$(".btn-close-panel").on('click', function(){
	$(".add-panel").hide();
});

$(".btn-save").on('click', function(){
	$('#event-create-form').submit();
});

$('#event-create-form').validate({
	ignore: [],
	highlight:function(element) {
		$(element).parents('.form-group').addClass('has-error has-feedback');
	},
	unhighlight: function(element) {
		$(element).parents('.form-group').removeClass('has-error');
	},
	submitHandler: function(form) {
		var formData = new FormData(form);
		$.ajax({
			url: form.action,
			type: form.method,
			data: formData,
			success: function(response) {
				eventTable.draw();
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
</script>