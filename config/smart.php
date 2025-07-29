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
		'refunded' => 'refunded',
	],

	'event_registration_status_class' => [
		'created' => 'warning',
		'approved' => 'success',
		'canceled' => 'danger ',
		'refunded' => 'info',
	],

	'event_registration_status_flow' => [
		'' => ['created'],
		'created' => ['approved', 'canceled'],
		'approved' => ['canceled', 'refunded'],
		'refunded' => ['approved', 'canceled'],
		'canceled' => ['approved'],
	],

	'event_refund_request_status' => [
		'requested' => 'requested',
		'approved' => 'approved',
		'rejected' => 'rejected ',
	],

	'event_refund_request_status_class' => [
		'requested' => 'warning',
		'approved' => 'success',
		'rejected' => 'danger ',
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

	'athlete_position' => [
		'outside hitter' => 'outside hitter',
		'opposite' => 'opposite',
		'setter' => 'setter',
		'middle blocker' => 'middle blocker',
		'libero' => 'libero',
		'defensive specialist' => 'defensive specialist',
		'serving specialist' => 'serving specialist',
	],

	'athlete_role' => [
		'forward' => 'forward',
		'support' => 'support',
	],

	'event_status' => [
		'created' => 'created',
		'verified' => 'verified',
		'canceled' => 'canceled',
	],

	'event_status_class' => [
		'created' => 'warning',
		'verified' => 'success',
		'canceled' => 'danger ',
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
	],

	'rank_category' => [
		'kids' => 'kids',
		'adult' => 'adult',
		'masters' => 'masters',
	],

	'object_types' => [
		0 => 'organization',
		1 => 'event',
		2 => 'product',
		3 => 'menu',
		4 => 'banner'
	],

	'event_organization_role' => [
		'organizer' => 'organizer',
		'co-organizer' => 'co-organizer',
		'sponsor' => 'sponsor',
		'participant' => 'participant',
	],

	'category_type' => [
		'organization' => 'organization',
		'proservice' => 'proservices',
		'event' => 'event',
		'menu' => 'menu',
		'product' => 'product'
	],
)

?>