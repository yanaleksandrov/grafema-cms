<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/*
 * Password field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/password.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $placeholder, $class, $instruction, $tooltip, $copy, $attributes, $conditions, $switcher, $indicator, $generator, $characters] = (
    new Sanitizer(
        $args ?? [],
        [
			'label'       => 'trim',
			'name'        => 'name',
			'value'       => 'attribute|trim',
			'placeholder' => 'trim',
			'class'       => 'class:df aic jcsb fw-600',
			'instruction' => 'trim',
			'tooltip'     => 'trim|attribute',
			'copy'        => 'bool:false',
			'attributes'  => 'array',
			'conditions'  => 'array',
			'switcher'    => 'bool:false',
			'indicator'   => 'bool:false',
			'generator'   => 'bool:false',
			'characters'  => 'array',
        ]
    )
)->values();

$variable   = Sanitizer::dot( $name );
$attributes = [
	...$attributes,
	'name'          => $name,
	':type'         => "show ? 'password' : 'text'",
	'@input.window' => $generator ? 'data = $password.check(' . $variable . ')' : '',
];
?>
<div class="dg g-1" x-data="{show: true, data: {}}">
	<div class="dg g-1">
		<div class="<?php echo $class; ?>">
			<?php
			Esc::html( $label );
            if ( $generator ) {
                ?>
				<span class="fw-400 fs-13 t-muted" @click="<?php Esc::attr( $variable ); ?> = $password.generate(); $dispatch('input')"><?php I18n::t( 'Generate' ); ?></span>
			<?php } ?>
		</div>
		<div class="field">
			<?php
			printf( '<input%s>', Arr::toHtmlAtts( $attributes ) );
            if ( $switcher ) {
                ?>
				<i class="ph" :class="show ? 'ph-eye-closed' : 'ph-eye'" @click="show = $password.switch(show)"></i>
				<?php
            }
            if ( $copy ) {
                ?>
				<i class="ph ph-copy" title="<?php Esc::attr( I18n::__( 'Copy' ) ); ?>" x-copy="<?php Esc::attr( $variable ); ?>"></i>
			<?php } ?>
		</div>
	</div>
	<?php if ( $instruction ) { ?>
		<div class="fs-13 t-muted lh-xs"><?php echo $instruction; ?></div>
		<?php
	}

	if ( $indicator ) {
		?>
		<div class="dg g-2 gtc-5 mt-2">
			<i class="pt-1" :class="data.progress > <?php echo 100 / 5; ?> ? 'bg-red' : 'bg-muted-lt'"></i>
			<i class="pt-1" :class="data.progress > <?php echo 100 / 5 * 2; ?> ? 'bg-amber' : 'bg-muted-lt'"></i>
			<i class="pt-1" :class="data.progress > <?php echo 100 / 5 * 3; ?> ? 'bg-orange' : 'bg-muted-lt'"></i>
			<i class="pt-1" :class="data.progress > <?php echo 100 / 5 * 4; ?> ? 'bg-green' : 'bg-muted-lt'"></i>
			<i class="pt-1" :class="data.progress === 100 ? 'bg-green' : 'bg-muted-lt'"></i>
		</div>
	<?php } ?>

	<?php if ( ! empty( $characters ) ) { ?>
		<div class="dg g-2 gtc-2 t-muted fs-13 mt-3 lh-xs">
			<?php
			$messages = [
				'lowercase' => I18n::__( '%d lowercase letters' ),
				'uppercase' => I18n::__( '%d uppercase letters' ),
				'special'   => I18n::__( '%d special characters' ),
				'length'    => I18n::__( '%d characters minimum' ),
				'digit'     => I18n::__( '%d numbers' ),
			];

            foreach ( $characters as $character => $count ) {
                if ( empty( $character ) || empty( $messages[$character] ) || $count <= 0 ) {
                    continue;
                }
                ?>
				<div class="df aifs g-2" :class="data.<?php echo $character; ?> && 't-green'">
					<i class="ph" :class="data.<?php echo $character; ?> ? 'ph-check' : 'ph-x'"></i> <span><?php printf( $messages[$character], $count ); ?></span>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
</div>
