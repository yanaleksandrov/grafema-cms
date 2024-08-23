<?php

namespace Dashboard\Builders;

use Grafema\Sanitizer;

final class Column
{
	use Traits\Column;

	/**
	 * Add new column.
	 *
	 * @param string $key
	 * @return Column
	 */
	public static function add( string $key ): Column {
        return new self( $key );
	}

	/**
	 * Set column title.
	 *
	 * @param string $title
	 * @return Column
	 */
	public function title( string $title ): Column {
		$this->title = $title;

		return $this;
	}

	/**
	 * Make column sortable.
	 *
	 * @return Column
	 */
	public function sortable(): Column {
        $this->sortable = true;

		return $this;
    }

	/**
	 * Default sort ordering.
	 *
	 * @param string $order
	 * @return Column
	 */
	public function sortOrder( string $order = 'DESC' ): Column {
		$order = Sanitizer::uppercase( $order );
        if ( in_array( $order, [ 'ASC', 'DESC' ], true ) ) {
			$this->sortOrder = $order;
        }
		$this->sortOrder = 'DESC';

		return $this;
	}

	/**
	 * Make column searchable.
	 *
	 * @return Column
	 */
	public function searchable(): Column {
		$this->searchable = true;

		return $this;
	}

	/**
	 * Set column width.
	 *
	 * @param string $width
	 * @return Column
	 */
	public function fixedWidth( string $width ): Column {
		$this->flexible = false;
		$this->width    = $width;

		return $this;
	}

	/**
	 * Set column width flexible.
	 *
	 * @param string $width
	 * @return Column
	 */
	public function flexibleWidth( string $width ): Column {
		$this->flexible = true;
		$this->width    = $width;

		return $this;
	}

	/**
	 * Get view template.
	 *
	 * @param string $template
	 * @return Column
	 */
	public function view( string $template ): Column {
		$this->view = $template;

		return $this;
	}
}
