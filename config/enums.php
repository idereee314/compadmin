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

	'gender_code_for_stats' => [
		1 => 'Эр',
		2 => 'Эм'
	],

	'team_lead' => [
		'0' => '',
		'1' => 'Багийн ахлагч'
	],

	'athlete_position' => [
		'outside hitter' => 'Гадна Hitter',
		'opposite' => 'Эсрэг Hitter',
		'setter' => 'Тогтоогч',
		'middle blocker' => 'Дунд Хориглогч',
		'libero' => 'Либеро',
		'defensive specialist' => 'Хамгаалалтын Мэргэжилтэн',
	],

	'athlete_role' => [
		'Товтлогч' => 'Товтлогч',
		'Холбогч' => 'Холбогч',
	],

	'sport_title' => [
		'Спортын мастер' => 'Спортын мастер	',
		'Спортын дэд мастер' => 'Спортын дэд мастер	',
		'Олон улсын хэмжээний мастер' => '	Олон улсын хэмжээний мастер	',
		'Улсын шүүгч' => 'Улсын шүүгч',
	],

	'event_registration_status' => [
		'created' => 'Илгээсэн',
		//'registered' => 'Бүртгэсэн',
		'approved' => 'Баталгаажсан',
		'canceled' => 'Цуцалсан',
	],

	'event_registration_status_for_stats' => [
		'created' => 'Баталгаажаагүй',
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
        '0' => array('title' => "Ерөнхий мэдээлэл",'code' => 'general','name' => "tab_general", 'icon' => "fa-calendar-alt",'number' => 'tab1-1'),
        '1' => array('title' => "Төрөл",'code' => 'event_entries','name' => "tab_entries", 'icon' => "fa-list",'number' => 'tab1-2'),
		'2' => array('title' => "Бүс",'code' => 'entry_config_belt','name' => "tab_config_belt", 'icon' => "fa-bacon",'number' => 'tab1-3'),
		'3' => array('title' => "Нас",'code' => 'entry_config_age','name' => "tab_config_age", 'icon' => "fa-hourglass-half",'number' => 'tab1-4'),
		'4' => array('title' => "Жин",'code' => 'entry_config_weight','name' => "tab_config_weight", 'icon' => "fa-weight",'number' => 'tab1-5'),
		'5' => array('title' => "Төлбөр",'code' => 'event_entries_fee','name' => "tab_entries_fee", 'icon' => "fa-money-bill",'number' => 'tab1-6'),
		'6' => array('title' => "Хэрэглэгч",'code' => 'event_event_user','name' => "tab_event_user", 'icon' => "fa-user-check",'number' => 'tab1-7'),
    ],
	'event_config_index' => [
        // '0' => array('title' => "Ерөнхий мэдээлэл",'code' => 'general','name' => "tab_general", 'icon' => "fa-calendar-alt",'number' => 'tab1-1'),
        '1' => array('title' => "Төрөл",'code' => 'event_entries','name' => "tab_entries", 'icon' => "fa-list",'number' => 'tab1-2'),
		'2' => array('title' => "Бүс",'code' => 'entry_config_belt','name' => "tab_config_belt", 'icon' => "fa-bacon",'number' => 'tab1-3'),
		'3' => array('title' => "Нас",'code' => 'entry_config_age','name' => "tab_config_age", 'icon' => "fa-hourglass-half",'number' => 'tab1-4'),
		'4' => array('title' => "Жин",'code' => 'entry_config_weight','name' => "tab_config_weight", 'icon' => "fa-weight",'number' => 'tab1-5'),
		// '5' => array('title' => "Төлбөр",'code' => 'event_entries_fee','name' => "tab_entries_fee", 'icon' => "fa-money-bill",'number' => 'tab1-6'),
		// '6' => array('title' => "Хэрэглэгч",'code' => 'event_event_user','name' => "tab_event_user", 'icon' => "fa-user-check",'number' => 'tab1-7'),
    ],

	'event_team_tabs' => [
        '0' => array('title' => "Тамирчдын жагсаалт",'code' => 'general','name' => "tab_general", 'icon' => "fa-user-check",'number' => 'tab1-1'),
    ],

	'event_stats' => [
        '0' => array('title' => "Ерөнхий статистик",'code' => 'overview','name' => "tab_overview", 'icon' => "fa-calendar-alt",'number' => 'tab1-1'),
        '1' => array('title' => "Гүйлгээнүүд",'code' => 'event_payments','name' => "tab_payments", 'icon' => "fa-list",'number' => 'tab1-2'),
    ],

	'org_type' => [
		'academy' => 'Академи',
		'highschool' => 'Дунд сургууль',
		'university' => 'Их, дээд сургууль, коллеж'
	],

	'event_award' => [
		'1' => 'fas fa-medal icon-4x gold-medal-icon',
		'2' => 'fas fa-medal icon-4x silver-medal-icon',
		'3' => 'fas fa-medal icon-4x bronze-medal-icon',
	],

	'country_alpha' => [
		'1' => 'mn',
		'2' => 'ru',
		'3' => 'cn',
		'4' => 'kr',
	],

)

?>