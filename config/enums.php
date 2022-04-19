<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Information alert configuration
	|--------------------------------------------------------------------------
	|
	*/
	'gender_code' => [
		1 => 'Эрэгтэй',
		2 => 'Эмэгтэй'
	],

	'event_registeation_status' => [
		'created' => 'Илгээсэн',
		//'registered' => 'Бүртгэсэн',
		'approved' => 'Баталгаажсан',
		'canceled' => 'Цуцалсан',
	],

	'member_status' => [
		'created' => 'Үүсгэсэн',
		'approved' => 'Баталгаажсан',
		'canceled' => 'Цуцалсан',
	],

	'boolean_type' => 
	[
		'0' => 'Үгүй',
		'1' => 'Тийм'
	],

	'permission_opration' => [
        'visit' => 'Харах',
	    'editable' => 'Засварлах',
    ],

	'event_config' => [
        '0' => array('title' => "Ерөнхий мэдээлэл",'code' => 'general','name' => "tab_general", 'icon' => "fa fa-newspaper-o",'number' => 'tab1-1'),
        '1' => array('title' => "Төрөл",'code' => 'event_entries','name' => "tab_entries", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-2'),
		'2' => array('title' => "Бүс",'code' => 'entry_config_belt','name' => "tab_config_belt", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-3'),
		'3' => array('title' => "Нас",'code' => 'entry_config_age','name' => "tab_config_age", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-4'),
		'4' => array('title' => "Жин",'code' => 'entry_config_weight','name' => "tab_config_weight", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-5'),
		'5' => array('title' => "Төлбөр",'code' => 'event_entries_fee','name' => "tab_entries_fee", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-6'),
		'6' => array('title' => "Хэрэглэгч",'code' => 'event_event_user','name' => "tab_event_user", 'icon' => "glyphicon glyphicon-time",'number' => 'tab1-7'),
    ],
)

?>