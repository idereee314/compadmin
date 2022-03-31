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

	'event_registeation_status' => [
		'created' => 'created',
		'approved' => 'approved',
		'checked' => 'Шалгасан',
		'canceled' => 'canceled',
	],

	'event_registeation_status_class' => [
		'created' => 'primary',
		'approved' => 'success',
		'checked' => 'info',
		'canceled' => 'danger ',
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

	'menu' => [
		'home' => [
			'icon' => 'flaticon2-architecture-and-city', 
			'url' => '/home',
			'label' => 'Нүүр хуудас',
			// 'permission' => [ 'visit']
		 ],
		'user_management' => [
			'icon' => 'flaticon2-laptop',
			'label' => 'Системийн удирдлага',
			'children' => [
				 'uq_compad_user' => [
					 'url' => '/user', 
					 'label' => 'Хэрэглэгч', 
					//  'permission' => [ 'visit', 'editable']
				 ],
			]
		],
		'reference' => [
			'icon' => 'flaticon2-browser-2',
			'label' => 'Лавлах',
			'children' => [
				 'uq_academy' => [
					 'url' => '/academy', 
					 'label' => 'Асадеми',
					//  'permission' => [ 'visit', 'editable']
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
					//  'permission' => [ 'visit', 'editable']
				 ],
				 'uq_event_registration' => [
					 'url' => '/event/registration', 
					 'label' => 'Тэмцээн',
					//  'permission' => [ 'visit', 'editable']
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
 
)

?>