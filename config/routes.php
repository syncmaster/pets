<?php

return [
	[
		'default' => true,
		'name' => 'home',
		'route' => '',
		'callback' => 'HomeController@index',
	],
	[
		'name' => 'search',
		'route' => 'search',
		'callback' => 'PetsController@searchItems'
	],
	[
		'name' => 'list',
		'route' => 'list',
		'callback' => 'PetsController@listItems'
	],
	[
		'name' => 'add',
		'route' => 'add',
		'callback' => 'PetsController@addItems'
	],
];