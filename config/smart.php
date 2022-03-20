<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Information alert configuration
	|--------------------------------------------------------------------------
	|
	*/
	'gender_code' => [
		1 => 'эрэгтэй',
		2 => 'эмэгтэй'
	],

	'event_registeation_status' => [
		'created' => 'created',
		//'registered' => 'Бүртгэсэн',
		'approved' => 'approved',
		'canceled' => 'canceled',
	],

	'event_registeation_status_class' => [
		'created' => 'primary',
		'registered' => 'info',
		'approved' => 'success',
		'canceled' => 'danger ',
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
 
)

?>