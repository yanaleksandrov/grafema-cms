<?php
namespace Dashboard;

use Grafema\{
	I18n,
	Hook,
	Errors,
	Sanitizer,
};

/**
 * Forms builder.
 *
 * TODO: проверить производительность, только генерация формы профиля
 * занимает в 2 раза больше времени, чем всё остальное приложение
 *
 * @package Grafema
 */
class Form {

	use Form\Traits;
	use \Grafema\Patterns\Multiton;

	/**
	 * Register new form.
	 *
	 * @param string $uniqid    Unique Form ID.
	 * @param array $attributes Html attributes list.
	 * @param array $fields     Fields list. Contains a nested array of arrays.
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function enqueue( string $uniqid, array $attributes = [], array $fields = [] ): void {
		$uniqid = Sanitizer::key( $uniqid );
		if ( ! $uniqid ) {
			new Errors( 'form-register', I18n::_f( 'The $uniqid of the form is empty.', $uniqid ) );
		}

		$form = self::init( $uniqid );
		if ( isset( $form->uniqid ) ) {
			new Errors( 'form-register', I18n::_f( 'The form identified by %s already exists! Potential conflicts detected!', $uniqid ) );
		}

		$form->uniqid     = $uniqid;
		$form->fields     = $fields;
		$form->attributes = [ 'id' => $uniqid, 'method' => 'POST', ...$attributes ];
	}

	/**
	 * Deregister form.
	 * TODO: write this part
	 *
	 * @param string $uniqid Unique Form ID.
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function dequeue( string $uniqid ) {

	}

	/**
	 * Get all data of form by name.
	 *
	 * @param string $uniqid
	 * @param callable|null $function
	 * @return Form
	 *
	 * @since 2025.1
	 */
	public static function override( string $uniqid, ?callable $function = null ): Form {
		$form = self::init( $uniqid );

		if ( is_callable( $function ) ) {
			call_user_func( $function, $form );
		}

		return $form;
	}

	/**
	 * Get form markup.
	 *
	 * @param string $uniqid
	 * @param string $path               Path to register form.
	 * @param bool $without_form_wrapper
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function get( string $uniqid, string $path = '', bool $without_form_wrapper = false ): string {
		if ( file_exists( $path ) ) {
			require_once $path;
		}

		$form   = self::init( $uniqid );
		$fields = $form->fields ?? [];

		/**
		 * Override form fields
		 *
		 * @since 2025.1
		 * @param array $fields Fields list of form.
		 * @param array $uniqid ID of form.
		 */
		$fields = Hook::apply( 'grafema_form_view_' . $uniqid, $fields, $form );
		if ( ! array( $fields ) ) {
			new Errors( 'form-view', I18n::_f( 'Form fields with ID "%s" is incorrect.', $uniqid ) );

			return '';
		}

		/**
		 * Override form html
		 *
		 * @since 2025.1
		 * @param string $content Parsed fields html
		 */
		$content = Hook::apply( 'grafema_form_view_html_' . $uniqid, $form->parseFields( $fields ) );

		/**
		 * Return only the contents of the form without its wrapper
		 *
		 * @since 2025.1
		 */
		if ( $without_form_wrapper ) {
			return $content;
		}

		// add form wrapper & return form
		return $form->wrap( $form->attributes, $content );
	}

	/**
	 * Output form markup.
	 *
	 * @param string $uniqid
	 * @param string $path               Path to register form.
	 * @param bool $without_form_wrapper
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function print( string $uniqid, string $path = '', bool $without_form_wrapper = false ): void {
		echo self::get( $uniqid, $path, $without_form_wrapper );
	}

	/**
	 * Adding a new field to any place of the fields list. TODO: Support nested arrays with using "dot" notation.
	 *
	 * @param array $field
	 * @return void
	 *
	 * @since 2025.1
	 */
	public function insert( array $field ): void {
		$name = Sanitizer::key( $field['name'] ?? '' );
		if ( empty( $name ) ) {
			new Errors( 'form-add-field', I18n::_t( 'It is not possible to add a field with an empty "name".' ) );
		}

		$this->insertField( $this->fields, $field, $this );

		$this->after   = '';
		$this->before  = '';
		$this->instead = '';
	}

	/**
	 * Bulk adding fields.
	 *
	 * @param array $fields
	 * @return void
	 *
	 * @since 2025.1
	 */
	public function attach( array $fields ): void {
		foreach ( $fields as $field ) {
			$this->insertField( $this->fields, $field, $this );
		}

		$this->after   = '';
		$this->before  = '';
		$this->instead = '';
	}

	/**
	 * Override form attributes.
	 *
	 * @param array $attributes
	 * @return void
	 *
	 * @since 2025.1
	 */
	public function attributes( array $attributes ): void {
		$this->attributes = [ ...$this->attributes, ...$attributes ];
	}

	/**
	 * Insert a new field after the specified one.
	 *
	 * @param string $field_name
	 * @return Form
	 *
	 * @since 2025.1
	 */
	public function after( string $field_name ): Form {
		$this->after = $field_name;

		return $this;
	}

	/**
	 * Insert a new field before the specified one.
	 *
	 * @param string $field_name
	 * @return Form
	 *
	 * @since 2025.1
	 */
	public function before( string $field_name ): Form {
		$this->before = $field_name;

		return $this;
	}

	/**
	 * Insert a new field instead the specified one.
	 *
	 * @param string $field_name
	 * @return Form
	 *
	 * @since 2025.1
	 */
	public function instead( string $field_name ): Form {
		$this->instead = $field_name;

		return $this;
	}
}
