<?php

return array('items' => array(
		array(
			'title' => 'ERD',
			'url' => Route::get('webdb_erd')->uri(array('dbname' => Request::current()->param('dbname'))),
			'tooltip' => 'Entity-Relationship Diagram for this database',
		),
	));
