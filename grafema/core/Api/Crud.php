<?php
namespace Grafema\Api;

// TODO: rewrite to interface
trait Crud {

	/**
	 * @return array
	 */
	abstract public function create(): array;

	/**
	 * @return array
	 */
	abstract public function index(): array;

	/**
	 * @return array
	 */
	abstract public function update(): array;

	/**
	 * @return array
	 */
	abstract public function delete(): array;
}
