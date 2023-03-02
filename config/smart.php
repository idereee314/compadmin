<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Information alert configuration
	|--------------------------------------------------------------------------
	|
	*/

	'cloud_image_url' => env('AWS_URL', 'http://sport.uniq.mn/images'),

	'gender_code' => [
		1 => 'эрэгтэй',
		2 => 'эмэгтэй'
	],

	'event_registration_status' => [
		'created' => 'created',
		'approved' => 'approved',
		'canceled' => 'canceled',
	],

	'event_registration_status_class' => [
		'created' => 'warning',
		'approved' => 'success',
		'canceled' => 'danger ',
	],

	'event_registration_status_flow' => [
		'' => ['created'],
		'created' => ['approved', 'canceled'],
		'approved' => ['canceled'],
		'canceled' => ['approved'],
	],

	'event_registration_source_type' => [
		'app' => 'app',
		'admin' => 'admin'
	],

	'member_status_class' => [
		'created' => 'primary',
		'registered' => 'info',
		'approved' => 'success',
		'canceled' => 'danger ',
	],

	'member_status' => [
		'created' => 'created',
		'approved' => 'approved',
		'canceled' => 'canceled',
	],

	'athlete_role' => [
		'outside hitter' => 'outside hitter',
		'opposite' => 'opposite',
		'setter' => 'setter',
		'middle blocker' => 'middle blocker',
		'libero' => 'libero',
		'defensive specialist' => 'defensive specialist',
		'serving specialist' => 'serving specialist',
	],

	'menu' => [
		'home' => [
			'icon' => 'flaticon2-architecture-and-city', 
			'url' => '/home',
			'label' => 'Нүүр хуудас',
			'permission' => [ 'visit']
		 ],
		'user_management' => [
			'icon' => 'flaticon2-laptop',
			'label' => 'Удирдлага',
			'children' => [
				 'uq_compad_user' => [
					 'url' => '/user', 
					 'label' => 'Хэрэглэгч', 
					 'permission' => [ 'visit', 'editable']
				 ],
				 'uq_compad_role' => [
					'url' => '/role', 
					'label' => 'Дүр', 
					'permission' => [ 'visit', 'editable']
				],
			]
		],
		'reference' => [
			'icon' => 'flaticon2-browser-2',
			'label' => 'Лавлах',
			'children' => [
				 'uq_academy' => [
					 'url' => '/academy', 
					 'label' => 'Академи',
					 'permission' => [ 'visit', 'editable']
				 ],
			]
		],
		'comp_registration' => [
			 'icon' => 'flaticon2-browser-2',
			 'label' => 'Бүртгэл',
			 'children' => [
				 'uq_member' => [
					 'url' => '/member', 
					 'label' => 'Оролцогч',
					 'permission' => [ 'visit', 'editable']
				 ],
				 'uq_event_registration' => [
					 'url' => '/event/registration', 
					 'label' => 'Тэмцээн',
					 'permission' => [ 'visit', 'editable']
				 ],
				 'uq_event_config' => [
					'url' => '/event/config', 
					'label' => 'Тэмцээний тохиргоо',
					'permission' => [ 'visit', 'editable']
				],
			 ]
		 ],
	 ],

	'member_image_size' => [
		'profile' => [
			'cropped' => [250, 250],
		],
		'id' => [
			'cropped' => [1200, 1200],
		]
	],

	'org_type' => [
		'academy' => 'academy',
		'highschool' => 'highschool',
		'university' => 'university'
	]
 
)

?>