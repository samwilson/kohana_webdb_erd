<?php defined('SYSPATH') OR die('No direct script access.') ?>
digraph <?php echo $database->get_name() ?>_ERD {
	rankdir=LR
	node [shape=record, rankdir=LR];
<?php foreach ($database->get_tables() as $table): ?>

	<?php
	if ( ! in_array($table->get_name(), $selected_tables)) continue;
	echo $table->get_name().' [label="'.$table->get_name().'|';
	$cols = array();
	foreach ($table->get_columns() as $col)
	{
		$c = '<'.$col->get_name().'> '.$col->get_name().' '.strtoupper($col->get_type());
		if ($size = $col->get_size()) $c .= '('.$size.')';
		$cols[] = $c;
	}
	echo join('|', $cols);
	echo '"];'."\n\t";

	foreach ($table->get_columns() as $col)
	{
		if ($col->is_foreign_key() AND in_array($col->get_referenced_table()->get_name(), $selected_tables))
		{
			echo $table->get_name().':'.$col->get_name();
			echo ' -> ';
			echo $col->get_referenced_table()->get_name().':'.$col->get_referenced_table()->get_pk_column()->get_name();
			echo ";\n\t\t";
		}
	}
	?>

<?php endforeach ?>

}

