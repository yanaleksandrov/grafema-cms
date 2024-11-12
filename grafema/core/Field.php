<?php
namespace Grafema;

/**
 * Represents a field associated with an object, allowing for retrieval, addition,
 * updating, and deletion of field values in a database.
 *
 * @since 2025.1
 */
final class Field extends Field\Schema {

	/**
	 * The ID of the associated object.
	 *
	 * @var int
	 */
	public int $objectID;

	/**
	 * DB column name.
	 *
	 * @var string
	 */
	public string $column;

	/**
	 * The name of the database table for the object.
	 *
	 * @var string
	 */
	public string $table;

	/**
	 * Cache key prefix.
	 *
	 * @var string
	 */
	public string $cacheGroup;

	/**
	 * Initializes the object ID and the table name based on the provided object.
	 *
	 * @param mixed $object The object associated with the field (e.g., User instance).
	 *
	 * @since 2025.1
	 */
	public function __construct( mixed $object ) {
		match ( true ) {
			$object instanceof User => [
				$this->objectID   = $object->ID,
				$this->column     = 'user_id',
				$this->table      = sprintf( '%s_fields', $object::$table ),
				$this->cacheGroup = sprintf( 'user-fields-%d', $object->ID ),
			],
		};
	}

	/**
	 * Retrieves the value of a specific field for the associated object.
	 *
	 * Benchmark when there are 1 million rows: 1 time - 0.00068 sec, 100000 times - 0.05 sec
	 *
	 * @param string      $key      The key of the field to retrieve. If empty, get all fields of object.
	 * @param bool        $isSingle Whether to limit the result to a single value (default: true).
	 * @return mixed                The field value or null if the object ID is not set.
	 *
	 * @since 2025.1
	 */
	public function get( mixed $key = '', bool $isSingle = true ): mixed {
		if ( ! $this->objectID ) {
			return null;
		}

		return Cache::get( $key, null, $this->cacheGroup, function() use ( $key, $isSingle ) {
			$conditions = [ $this->column => $this->objectID ];

			if ( $key ) {
				$conditions['key'] = $key;
			}

			$fields = $isSingle && $key
				? Db::get( $this->table, [ 'key', 'value' ], $conditions )
				: Db::select( $this->table, [ 'key', 'value' ], $conditions );

			$result = null;
			if ( is_array( $fields ) ) {
				$result = [];
				if ( $isSingle && $key ) {
					return $fields['value'] ?? '';
				}

				foreach ( $fields as $field ) {
					$result[ $field['key'] ][] = $field['value'];
				}
			}

			return $result;
		} );
	}

	/**
	 * Adds a new field value for the associated object.
	 *
	 * Benchmark when there are 1 million rows: 1 time - 0.00029 sec, 100000 times - 26.15 sec
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
			$count = Db::count( $this->table, [ $this->column => $this->objectID, 'key' => $key ] );
			if ( $count > 0 ) {
				return false;
			}
		}

		$isInsert = Db::insert(
			$this->table,
			[
				$this->column => $this->objectID,
				'key'         => $key,
				'value'       => $value
			]
		)->rowCount() > 0;

		if ( $isInsert ) {
			Cache::add( $key, $value, $this->cacheGroup );
		}
		return $isInsert;
	}

	/**
	 * Updates an existing field value for the associated object.
	 *
	 * @param string $key     The key of the field to update.
	 * @param mixed $value    The new value for the field.
	 * @param mixed $oldValue The old value of the field (optional).
	 * @return bool           True if the field was updated successfully, false otherwise.
	 *
	 * @since 2025.1
	 */
	public function update( string $key, mixed $value, mixed $oldValue = '' ): bool {
		if ( ! $this->objectID ) {
			return false;
		}

		$data       = [ 'value' => $oldValue ? [ $oldValue => $value ] : $value ];
		$conditions = [ 'key' => $key, $this->column => $this->objectID ];

		$query = $oldValue
			? Db::replace( $this->table, $data, $conditions )
			: Db::update( $this->table, $data, $conditions );

		$isSuccessful = $query->rowCount() > 0;

		if ( $isSuccessful ) {
			Cache::add( $key, $value, $this->cacheGroup );
		}

		return $isSuccessful;
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

		$conditions = [ $this->column => $this->objectID ];

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

		$oldValue = $this->get( $key );
		if ( ! empty( $oldValue ) ) {
			return $this->update( $key, $value, $oldValue );
		}

		return $this->add( $key, $value, $isUnique );
	}

	/**
	 * Bulk insert fields.
	 *
	 * Benchmark when there are 1 million rows (insert, update and delete in the ratio of each action 33%): 100000 rows - 29.25 sec
	 * TODO: method need refactor
	 *
	 * @param array $fields
	 * @param int $chunkSize
	 * @return array
	 */
	public function import( array $fields, int $chunkSize = 10000 ): array {
		$result = [ 'deleted' => 0, 'inserted' => 0, 'updated' => 0 ];

		$insertData  = [];
		$updateData  = [];
		$updateQuery = [];

		$existsFields = self::get();

		foreach ( $fields as $key => $value ) {
			if ( ! is_string( $key ) ) {
				continue;
			}

			$insertItem = [
				$this->column => $this->objectID,
				'key'         => $key,
				'value'       => $value,
			];

			if ( ! isset( $existsFields[ $key ] ) ) {
				$insertData[] = $insertItem;
			} elseif ( $value !== $existsFields[ $key ] ) {
				$updateData[ $key ] = "WHEN :key_$key THEN :value_$key";

				$updateQuery[":key_{$key}"]   = $key;
				$updateQuery[":value_{$key}"] = $value;
			}
		}

		$deleteDate = array_diff( array_keys( $existsFields ), array_keys( $fields ) );
		if ( $deleteDate ) {
			$deleteDateParts = array_chunk( $deleteDate, $chunkSize, false );
			foreach ( $deleteDateParts as $deleteDatePart ) {
				$result['deleted'] += Db::delete(
					$this->table,
					[
						'AND' => [
							$this->column => $this->objectID,
							'key'         => $deleteDatePart,
						]
					]
				)->rowCount();
			}
		}

		if ( $insertData ) {
			$insertDataParts = array_chunk( $insertData, $chunkSize, false );
			foreach ( $insertDataParts as $i => $insertDataPart ) {
				var_dump( $i );
				$result['inserted'] += Db::insert( $this->table, $insertDataPart )->rowCount();
			}
		}

		if ( $updateData ) {
			$updateDataParts  = array_chunk( $updateData, $chunkSize, true );
			$updateQueryParts = array_chunk( $updateQuery, $chunkSize * 2, true );
			foreach ( $updateDataParts as $i => $updateDataPart ) {
				$whenPart = implode( "\n\t", $updateDataPart );
				$inPart   = implode(', ', array_map( fn( $k ) => ":key_$k", array_keys( $updateDataPart ) ) );

				$sql = "
				UPDATE <{$this->table}> 
					SET value = CASE `key`
						{$whenPart}
					END
					WHERE `key` IN ({$inPart})
				";

				$result['updated'] += Db::query( $sql, $updateQueryParts[ $i ] )->rowCount();
			}
		}

		return $result;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	private function getColumnName( mixed $value ): string {
		return match ( gettype( $value ) ) {
			'integer' => 'value_int',
			'float'   => 'value_float',
			'boolean' => 'value_bool',
			'object'  => function ( mixed $value ) {
				if ( $value instanceof \DateTime ) {
					$time = $value->format( 'H:i:s' );

					if ( $time === '00:00:00' ) {
						return $value->format( 'Y-m-d' ) === '0000-00-00' ? 'value_time' : 'value_date';
					}

					return 'value_datetime';
				}
				return 'value_text';
			},
			default   => 'value_text',
		};
	}
}
