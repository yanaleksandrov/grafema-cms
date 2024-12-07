<?php
namespace Grafema\Post;

use Grafema\Db;
use Grafema\Error;
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\Tree;
use Grafema\Field;

//TODO: нужно переименовать
class Kind {

	private static array $items = [];

	private function __construct(
		public string $key,
		public string $labelName,
		public string $labelNamePlural,
		public string $labelAllItems,
		public string $labelAdd,
		public string $labelEdit,
		public string $labelUpdate,
		public string $labelView,
		public string $labelSearch,
		public string $labelSave,
		public string $table = '',

		public bool $public = true,
		public bool $hierarchical = false,
		public bool $searchable = true,
		public bool $showInMenu = true,
		public bool $showInBar = true,
		public bool $canExport = true,
		public bool $canImport = true,
		public array $capabilities = ['typesEdit'],
		public string $menuIcon = 'ph ph-folders',
		public int $menuPosition = 10,
	) {
		$postType = Sanitizer::kebabcase( $key );
		if ( empty( $postType ) || strlen( $postType ) > 20 ) {
			Error::add( 'post-type-name-length', I18n::_t( 'Post type key is empty or exceeds 20 characters' ) );
		}

		$this->labelName       ??= I18n::_t( 'Page' );
		$this->labelNamePlural ??= I18n::_t( 'Pages' );
		$this->labelAllItems   ??= I18n::_t( 'All Pages' );
		$this->labelAdd        ??= I18n::_t( 'Add Page' );
		$this->labelEdit       ??= I18n::_t( 'Edit Page' );
		$this->labelUpdate     ??= I18n::_t( 'Update Page' );
		$this->labelView       ??= I18n::_t( 'View Page' );
		$this->labelSearch     ??= I18n::_t( 'Search Page' );
		$this->labelSave       ??= I18n::_t( 'Save' );
		$this->table             = Sanitizer::tablename( $key );

		/**
		 * Show in dashboard menu.
		 *
		 * @since 2025.1
		 */
		if ( $this->showInMenu ) {
			Tree::attach( 'dashboard-main-menu', fn ( Tree $tree ) => $tree->addItems(
				[
					[
						'id'           => $postType,
						'url'          => $postType,
						'title'        => $this->labelNamePlural,
						'capabilities' => $this->capabilities,
						'icon'         => $this->menuIcon,
						'position'     => $this->menuPosition,
					],
					[
						'id'           => sprintf( 'type-%s', $postType ),
						'url'          => $postType,
						'title'        => $this->labelAllItems,
						'capabilities' => $this->capabilities,
						'parent_id'    => $postType,
					],
				]
			) );
		}

		/**
		 * DataBase table schema.
		 *
		 * @var array $schema
		 */
		$schema = Db::schema();
		$type   = Sanitizer::snakecase( $key );
		if ( empty( $schema[ $type ] ) ) {
			Schema::migrate( $type );

			Field\Schema::migrate( GRFM_DB_PREFIX . $type, 'post' );
		}
	}

	/**
	 * Registers a post type.
	 *
	 * @param mixed ...$args
	 * @return Kind
	 *
	 * @since 2025.1
	 */
	public static function register( ...$args ): self {
		$type = new self( ...$args );
		if ( empty( $items[ $type->key ] ) ) {
			self::$items[ $type->key ] = $type;
		}
		return $type;
	}

	/**
	 * Unregister post type.
	 *
	 * @param string $key
	 *
	 * @since 2025.1
	 */
	public static function unregister( string $key ): void
	{
		if ( isset( self::$items[ $key ] ) ) {
			unset( self::$items[ $key ] );
		}
	}

	/**
	 * Get registered type.
	 *
	 * @since 2025.1
	 */
	public static function get( string $key ): ?self
	{
		return self::$items[ $key ] ?? null;
	}

	/**
	 * Check if type registered.
	 *
	 * @param string $key
	 * @return bool
	 *
	 * @since 2025.1
	 */
	public static function exist( string $key ): bool {
		return isset( self::$items[ $key ] );
	}

	/**
	 * Retrieves data (objects) of registered record types.
	 * Not the records themselves, but the record type registration data.
	 * You can filter the output by a variety of criteria.
	 *
	 * @param array $args      Array of criteria by which posts types will be selected.
	 *                         For the value of each parameter, see the description of the "Type::register" method.
	 * @param string $operator Optional. The logical operation to perform. 'or' means only one
	 *                         element from the array needs to match; 'and' means all elements
	 *                         must match; 'not' means no elements may match. Default 'and'.
	 *
	 * @return array
	 *
	 * @since 2025.1
	 */
	public static function fetch( array $args = [], string $operator = 'and' ): array
	{
		return Arr::filter( self::$items ?? [], $args, $operator );
	}
}
