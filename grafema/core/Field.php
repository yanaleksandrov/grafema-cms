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
				$this->table    = sprintf( '%s_fields', $object::$table ),
			],
		};
	}

	/**
	 * Retrieves the value of a specific field for the associated object.
	 *
	 * @param string      $key      The key of the field to retrieve. If empty, get all fields of object.
	 * @param bool        $isSingle Whether to limit the result to a single value (default: true).
	 * @return array|null           The field value as an array, or null if the object ID is not set.
	 *
	 * @since 2025.1
	 */
	public function get( mixed $key = '', bool $isSingle = true ): ?array {
		if ( ! $this->objectID ) {
			return null;
		}

		$conditions = [ 'id' => $this->objectID ];

		if ( $key ) {
			$conditions['key'] = $key;
		}

		$fields = $isSingle
			? Db::get( $this->table, [ 'key', 'value' ], $conditions )
			: Db::select( $this->table, [ 'key', 'value' ], $conditions );

		$result = [];
		if ( is_array( $fields ) ) {
			if ( $isSingle ) {
				return [ $fields['key'] => $fields['value'] ];
			}

			foreach ( $fields as $field ) {
				$result[ $field['key'] ][] = $field['value'];
			}
		}

		return $result;
	}

	/**
	 * Adds a new field value for the associated object.
	 *
	 * @param string $key      The key of the field to add.
	 * @param mixed  $value    The value to be added.
	 * @param bool   $isUnique Whether the field key must be unique (default: true).
	 * @return bool            True if the field was added successfully, false otherwise.
	 *
	 * @since 2025.1
	 */
	public function add( string $key, mixed $value, bool $isUnique = true ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		if ( $isUnique ) {
			$count = Db::count( $this->table, [ 'id' => $this->objectID, 'key' => $key ] );
			if ( $count > 0 ) {
				return false;
			}
		}

		return Db::insert(
			$this->table,
			[
				'id'    => $this->objectID,
				'key'   => $key,
				'value' => $value
			]
		)->rowCount() > 0;
	}

	/**
	 * Updates an existing field value for the associated object.
	 *
	 * @param string $key     The key of the field to update.
	 * @param mixed $value    The new value for the field.
	 * @param mixed $oldValue The old value of the field (optional).
	 * @return bool           True if the field was updated successfully, false otherwise.
	 * @since 2025.1
	 */
	public function update( string $key, mixed $value, mixed $oldValue = '' ): bool {
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
					'id'  => $this->objectID,
					'key' => $key,
				],
			)->rowCount() > 0;
		}

		return Db::update(
			$this->table,
			[
				'value' => $value
			],
			[
				'id'  => $this->objectID,
				'key' => $key,
			],
		)->rowCount() > 0;
	}

	/**
	 * Deletes a field for the associated object.
	 *
	 * @param string $key  The key of the field to delete.
	 * @param mixed $value The value of the field to delete (optional).
	 * @return bool        True if the field was deleted successfully, false otherwise.
	 * @since 2025.1
	 */
	public function delete( string $key = '', mixed $value = '' ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		$conditions = [ 'id' => $this->objectID ];

		if ( $key ) {
			$conditions['key'] = $key;
		}

		if ( $value ) {
			$conditions['value'] = $value;
		}

		return Db::delete( $this->table, $conditions )->rowCount() > 0;
	}

	/**
	 * Add, update or delete field.
	 *
	 * @param string $key
	 * @param mixed|string $value
	 * @param bool $isUnique
	 * @return bool
	 */
	public function mutate( string $key = '', mixed $value = '', bool $isUnique = true ): bool {
		if ( empty( $value ) ) {
			return $this->delete( $key );
		}

		$oldValues = $this->get( $key );
		if ( ! empty( $oldValues[ $key ] ) ) {
			return $this->update( $key, $value, $oldValues[ $key ] );
		}
		
		return $this->add( $key, $value, $isUnique );
	}
}
