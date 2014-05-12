<?php

class WebDB_ERD {

	public function link($database, $table)
	{
		if ($database)
		{
			$uri = Route::get('webdb_erd')->uri(array('dbname' => $database->get_name()));
			echo HTML::anchor($uri, 'ERD', array('class'=>'tool', 'title'=>'Entity-Relationship Diagram for this database'));
		}
	}

}
