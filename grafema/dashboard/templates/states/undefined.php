<?php
use Grafema\Sanitizer;

/**
 * Not found part.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/states/undefined.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $class, $title, $description ] = (
	new Sanitizer(
		$args ?? [],
		[
			'class'       => 'class:m-auto t-center p-5 pt-8 mt-8 mw-320',
			'title'       => 'trim',
			'description' => 'trim',
		]
	)
)->values();
?>
<div class="<?php echo $class; ?>">
	<svg width="196" height="196" viewBox="0 0 196 196" fill="none" xmlns="http://www.w3.org/2000/svg">
		<rect width="196" height="196" rx="98" fill="#8E8E8E" fill-opacity=".07"/>
		<path opacity=".99" d="M63 69.01V98H53V74c0-3 2-5 5-4.99h5ZM132 78.01l6-.01c2 0 4 2 4 4v5h-10v-8.99Z" fill="#B3B8C2"/>
		<rect x="68" y="56" width="64" height="54" rx="1" fill="#E8ECED"/>
		<rect x="63" y="61" width="64" height="49" rx="1" fill="#fff"/>
		<path d="M71 69h48v2H71v-2ZM71 79h48v2H71v-2ZM71 89h48v2H71v-2Z" fill="#E8ECED"/>
		<path d="m77 97 3-2 6-6 3-2h52c3 0 5 2 5 5l-4 44c0 3-2 5-5 5H56c-2 0-5-2-5-5l-1-22-1-12c0-3 2-5 5-5h23Z" fill="#D3D9DD"/>
	</svg>
	<?php if ( $title ) { ?>
		<h4 class="mt-4"><?php echo $title; ?></h4>
		<?php
	}
	if ( $description ) {
		?>
		<p class="t-muted"><?php echo $description; ?></p>
	<?php } ?>
</div>
