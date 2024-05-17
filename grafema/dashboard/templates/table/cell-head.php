<?php

use Dashboard\Tables\Column;
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/text.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$columns = Sanitizer::array( $args ?? [] );
if ( ! $columns ) {
    return;
}
?>
<div class="table__head">
    <?php
	foreach ( $columns as $column ) {
		[ $key, $cell, $title, $sortable ] = (
            new Sanitizer(
                (array) $column,
                [
                    'key'      => 'key',
                    'cell'     => 'key',
                    'title'    => 'trim',
                    'sortable' => 'bool',
                ]
            )
		)->values();
	    ?>
        <div class="<?php echo $key; ?> df aic g-1">
			<?php
			if ( $title ) :
				echo $title;
			endif;
			if ( $sortable ) :
				?>
                <i class="ph ph-sort-ascending"></i>
			<?php endif; ?>
        </div>
        <?php
    }
    ?>
</div>
