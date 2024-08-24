<?php

namespace Dashboard\Builders;

use Grafema\Helpers\Arr;
use Grafema\Sanitizer;
use Grafema\View;

final class Row
{
	use Traits\Row;

	/**
	 * Add new row.
	 *
	 * @return Row
	 */
	public static function add(): Row {
        return new self();
	}

	/**
	 * Set row tag.
	 *
	 * @param string $tag
	 * @return Row
	 */
	public function tag( string $tag ): Row {
		$tag = Sanitizer::key( $tag );
		if ( $tag ) {
			$this->tag = $tag;
		}
		return $this;
	}

	/**
	 * Set row attribute.
	 *
	 * @param string $attribute
	 * @param string|int $value
	 * @return Row
	 */
	public function attribute( string $attribute, string|int $value = '' ): Row {
		$attribute = Sanitizer::key( $attribute );
		$value     = Sanitizer::attribute( $value );
		if ( $attribute && $value ) {
			$this->attributes[ $attribute ] = $value;
		}
		return $this;
	}

	/**
	 * Render row markup.
	 *
	 * @param array $columns
	 * @param array $data
	 * @return Row
	 */
	public function render( array $columns, array $data ): string {
		ob_start();
//		echo '<pre>';
//		print_r( $data );
//		print_r( $columns );
//		echo '</pre>';
		?>
		<<?php echo trim( sprintf( '%s %s', $this->tag, Arr::toHtmlAtts( $this->attributes ) ) ); ?>>
			<?php
			foreach ( $columns as $key => $column ) {
				View::print( 'templates/table/cell-' . $column->view, (array) $column );
			}
			?>
		</<?php echo $this->tag; ?>>
		<?php
		return ob_get_clean();
	}
}
