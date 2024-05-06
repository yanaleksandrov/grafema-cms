<?php
use Grafema\I18n;
use Grafema\View;

/*
 * User profile page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/profile.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php
	View::part(
		'templates/form/image',
		[
			'type'        => 'image',
			'name'        => 'avatar',
			'label'       => I18n::__( 'Upload avatar' ),
			'label_class' => '',
			'class'       => 'dg p-8 pb-4 bg-gray-lt',
			'description' => I18n::__( 'Click to upload or drag & drop' ),
			'tooltip'     => I18n::__( 'This is tooltip' ),
			'attributes'  => [
				'required' => false,
				'@change'  => '[...$refs.uploader.files].map(file => $ajax("upload/media").then(response => files.unshift(response[0])))',
			],
		]
	);

	echo Dashboard\Form::view( 'grafema-user-profile' );
	?>
</div>
