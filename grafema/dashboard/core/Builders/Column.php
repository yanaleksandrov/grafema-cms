<?php

namespace Dashboard\Builders;

use Grafema\Sanitizer;

class Column
{

	/**
	 * @var string
	 */
	public string $id = '';

	/**
	 * @var bool
	 */
    public bool $sortable = false;

	/**
	 * @var string
	 */
	public string $sortOrder = 'DESC';

	/**
	 * @var bool
	 */
	protected bool $searchable = false;

	/**
	 * Column constructor.
     *
	 * @param $id
	 */
    public function __construct( $id ) {
        $this->id = $id;
    }

	/**
	 * @param string $id
	 * @return static
	 */
	public static function make( string $id ): self {
        return new self( $id );
	}

	/**
	 *
	 */
	public function sortable() {
        $this->sortable = true;
    }

	/**
	 * @param string $order
	 */
	public function sortByDefault( string $order = 'DESC' ): void {
		$order = Sanitizer::uppercase( $order );
        if ( in_array( $order, [ 'ASC', 'DESC' ], true ) ) {
			$this->sortOrder = $order;
        }
		$this->sortOrder = 'DESC';
	}

	/**
	 *
	 */
	public function searchable() {
		$this->searchable = true;
	}
}
