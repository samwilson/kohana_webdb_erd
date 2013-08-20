<?php defined('SYSPATH') OR die('No direct script access.') ?>
digraph <?php echo $database->get_name() ?>_ERD {
rankdir=LR
	/* Tables */
	node [shape=record, rankdir=LR];
<?php foreach ($database->get_tables() as $table): ?>

	<?php /*subgraph cluster_<?php echo $table->get_name() ?> {
		label="<?php echo $table->get_name() ?>";
		node [shape=none];
		<?php
		foreach ($table->get_columns() as $col)
		{
			echo $table->get_name().'_'.$col->get_name();
			echo ' [label="'.$col->get_name().' '.strtoupper($col->get_type()).'';
			if ($size = $col->get_size()) echo '('.$size.')';
			echo '"]',";\n\t\t";
			
			if ($col->is_foreign_key())
			{
				echo $table->get_name().'_'.$col->get_name();
				echo ' -> ';
				echo $col->get_referenced_table()->get_name().'_'.$col->get_referenced_table()->get_pk_column()->get_name();
				echo ";\n\t\t";
			}
		} ?>
	}*/ ?>

	/* Table */
	<?php
	if (count($table->get_referenced_tables()) == 0 AND count($table->get_referencing_tables()) == 0) continue;
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
		if ($col->is_foreign_key())
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

