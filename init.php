<?php defined('SYSPATH') OR die('No direct script access.');

Route::set('webdb_erd', '<dbname>/erd(.<action>)')->defaults(array(
	'directory'  => 'WebDB',
	'controller' => 'ERD',
	'action' => 'html',
	'dbname' => '',
));
