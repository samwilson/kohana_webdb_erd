<h2>Entity Relationship Diagram for <?php echo WebDB_Text::titlecase($database->get_name()) ?></h2>

<p class="webdb erd">
	<img src="<?php echo Route::url('webdb_erd', array('dbname'=>$database->get_name(), 'action'=>'png')).URL::query() ?>"
		 alt="Entity Relationship Diagram for <?php echo $database->get_name() ?>" />
</p>

<h2>Change displayed tables</h2>
<?php echo Form::open(NULL, array('method'=>'get')) ?>
<ol class="columnar">
	<?php foreach ($database->get_tables() as $table): ?>
	<li>
		<label>
		<?php
		$selected = in_array($table->get_name(), $selected_tables);
		echo Form::checkbox($table->get_name(), '', $selected);
		echo WebDB_Text::titlecase($table->get_name());
		?>
		</label>
	</li>
	<?php endforeach ?>
</ol>
<?php echo Form::submit(NULL, 'Show only the selected tables') ?>
<?php echo Form::close() ?>
