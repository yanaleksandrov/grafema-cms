<?php
namespace Grafema;

/**
 * Represents a field associated with an object, allowing for retrieval, addition,
 * updating, and deletion of field values in a database.
 *
 * @since 2025.1
 */
final class Field {

	/**
	 * Object form manipulations.
	 *
	 * @var int
	 */
	public mixed $object;

	/**
	 * The ID of the associated object.
	 *
	 * @var int
	 */
	public int $objectID;

	/**
	 * The name of the database table for the object.
	 *
	 * @var string
	 */
	public string $table;

	/**
	 * Initializes the object ID and the table name based on the provided object.
	 *
	 * @param mixed $object The object associated with the field (e.g., User instance).
	 * @since 2025.1
	 */
	public function __construct( mixed $object ) {
		match ( true ) {
			$object instanceof User => [
				$this->object   = $object,
				$this->objectID = $object->ID,
				$this->table    = sprintf('%s_fields', $object::$table),
			],
		};
	}

	/**
	 * Retrieves the value of a specific field for the associated object.
	 *
	 * @param string      $name   The name of the field to retrieve. If empty, get all fields of object.
	 * @param bool        $single Whether to limit the result to a single value (default: true).
	 * @return array|null         The field value as an array, or null if the object ID is not set.
	 *
	 * @since 2025.1
	 */
	public function get( mixed $name = '', bool $single = true ): ?array {
		if ( ! $this->objectID ) {
			return null;
		}

		$conditions = [ 'ID' => $this->objectID ];

		if ( $name ) {
			$conditions['name'] = $name;
		}

		$fields = $single
			? Db::get( $this->table, [ 'name', 'value' ], $conditions )
			: Db::select( $this->table, [ 'name', 'value' ], $conditions );

		if ( $single ) {
			return [ $fields['name'] => $fields['value'] ];
		}

		$result = [];
		foreach ( $fields as $field ) {
			$result[ $field['name'] ][] = $field['value'];
		}

		return $result;
	}

	/**
	 * Adds a new field value for the associated object.
	 *
	 * @param string $name     The name of the field to add.
	 * @param mixed  $value    The value to be added.
	 * @param bool   $isUnique Whether the field name must be unique (default: true).
	 * @return bool            True if the field was added successfully, false otherwise.
	 *
	 * @since 2025.1
	 */
	public function add( string $name, mixed $value, $isUnique = true ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		if ( $isUnique ) {
			$count = Db::count( $this->table, [ 'ID' => $this->objectID, 'name' => $name ] );
			if ( $count > 0 ) {
				return false;
			}
		}

		return Db::insert(
			$this->table,
			[
				'ID'    => $this->objectID,
				'name'  => $name,
				'value' => $value
			]
		)->rowCount() > 0;
	}

	/**
	 * Updates an existing field value for the associated object.
	 *
	 * @param string $name The name of the field to update.
	 * @param mixed $value The new value for the field.
	 * @param mixed $oldValue The old value of the field (optional).
	 * @return bool True if the field was updated successfully, false otherwise.
	 * @since 2025.1
	 */
	public function update( string $name, mixed $value, mixed $oldValue = '' ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		if ( $oldValue ) {
			return Db::replace(
				$this->table,
				[
					'value' => [ $oldValue => $value ],
				],
				[
					'ID'   => $this->objectID,
					'name' => $name,
				],
			)->rowCount() > 0;
		}

		return Db::update(
			$this->table,
			[
				'value' => $value
			],
			[
				'ID'   => $this->objectID,
				'name' => $name,
			],
		)->rowCount() > 0;
	}

	/**
	 * Deletes a field for the associated object.
	 *
	 * @param string $name The name of the field to delete.
	 * @param mixed $value The value of the field to delete (optional).
	 * @return bool True if the field was deleted successfully, false otherwise.
	 * @since 2025.1
	 */
	public function delete( string $name = '', mixed $value = '' ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		$conditions = [ 'ID' => $this->objectID ];

		if ( $name ) {
			$conditions['name'] = $name;
		}

		if ( $value ) {
			$conditions['value'] = $value;
		}

		return Db::delete( $this->table, $conditions )->rowCount() > 0;
	}
}
