<?php
use Grafema\Sanitizer;

/**
 * Form tab markup
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/tab.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$fields    = Sanitizer::array( $args['fields'] ?? [] );
$fields    = array_filter( $fields, fn( $field ) => $field['type'] === 'tab' );
$classMenu = array_filter( array_column( $fields, 'class_menu' ), fn ( $field ) => $field )[0] ?? '';

if ( count( $fields ) === 0 ) {
	return;
}
?>
<ul class="tab__nav <?php echo $classMenu; ?>" x-sticky>
	<?php
	foreach ( $fields as $field ) :
		[ $type, $name, $property, $label, $icon, $class ] = (
            new Sanitizer(
				$field,
                [
					'type'         => 'key',
					'name'         => 'key',
					'property'     => 'key:tab',
					'label'        => 'trim',
					'icon'         => 'trim',
					'class_button' => 'class',
                ]
            )
		)->values();
		?>
		<li class="tab__title <?php echo $class; ?>" x-bind="tabButton('<?php echo $name; ?>')">
            <?php if ( $icon ) : ?>
                <i class="<?php echo $icon; ?>"></i>
                <?php
            endif;
            echo $label;
            ?>
		</li>
	<?php endforeach; ?>
</ul>
