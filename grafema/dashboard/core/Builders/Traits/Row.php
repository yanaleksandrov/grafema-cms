<?php

namespace Dashboard\Builders\Traits;

trait Row
{

	/**
	 * Tag for row wrapper.
	 *
	 * @var string
	 */
	protected string $tag = 'div';

	/**
	 * Attributes list.
	 *
	 * @var array
	 */
	protected array $attributes = [];
}
