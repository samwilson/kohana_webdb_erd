<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_WebDB_ERD extends Controller {

	/** @var WebDB_DBMS_Database The current database */
	protected $database;

	public function before()
	{
		$dbms = new WebDB_DBMS;
		$dbms->connect();
		$dbname = $this->request->param('dbname');
		$this->database = $dbms->get_database($dbname);

		$this->selected_tables = array();
		foreach ($this->database->get_tables() as $table)
		{
			// If any tables are requested, only show them
			if (count($_GET) > 0)
			{
				if (isset($_GET[$table->get_name()]))
				{
					$this->selected_tables[] = $table->get_name();
				}
			}
			else // Otherwise, default to all linked tables
			{
				$referenced = count($table->get_referencing_tables()) > 0;
				$referencing = count($table->get_referenced_tables()) > 0;
				if ($referenced OR $referencing)
				{
					$this->selected_tables[] = $table->get_name();
				}
			}
		}
	}

	public function action_html()
	{
		$view = View::factory('webdb/erd/html');
		$view->database = $this->database;
		$view->selected_tables = $this->selected_tables;

		// Template
		$template = View::factory('template');
		$template->database = $this->database;
		$template->databases = array();
		$template->tables = array();
		$template->table = '';
		$template->controller = 'ERD';
		$template->action = 'ERD';
		$template->content = $view->render();

		// Response
		$this->response->body($template->render());
	}

	public function action_dot()
	{
		$view = View::factory('webdb/erd/dot');
		$view->database = $this->database;
		$view->selected_tables = $this->selected_tables;
		$this->response->headers('Content-Type', 'text/plain');
		$this->response->body($view->render());
	}

	public function action_png()
	{
		$dbname = $this->database->get_name();
		$graph = Request::factory($dbname.'/erd.dot')
			->execute()
			->body();
		$this->cache_dir = Kohana::$cache_dir.DIRECTORY_SEPARATOR.'webdb'.DIRECTORY_SEPARATOR.'erd';
		if ( ! is_dir($this->cache_dir))
		{
			mkdir($this->cache_dir, 0777, TRUE);
		}
		$dot_filename = $this->cache_dir.DIRECTORY_SEPARATOR.$dbname.'.dot';
		$png_filename = $this->cache_dir.DIRECTORY_SEPARATOR.$dbname.'.png';
		file_put_contents($dot_filename, $graph);
		$dot = Kohana::$config->load('webdb/erd')->get('dot');
		$cmd = '"'.$dot.'"'.' -Tpng';
		$cmd .= ' -o'.escapeshellarg($png_filename); //output
		$cmd .= ' '.escapeshellarg($dot_filename); //input
		$cmd .= ' 2>&1';
		exec($cmd, $out, $error);
		if ($error != 0)
		{
			throw new HTTP_Exception_500('Unable to produce PNG. Command was: '.$cmd.' Output was: '.implode(PHP_EOL, $out));
		}
		else
		{
			$this->response->send_file($png_filename, $dbname.'_erd.png', array('inline' => TRUE));
		}
	}

}
