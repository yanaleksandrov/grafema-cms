<?php
namespace Dashboard\Form;

use Dashboard\Form;

use Grafema\Helpers\Arr;
use Grafema\Hook;
use Grafema\Json;
use Grafema\Sanitizer;
use Grafema\View;

trait Traits {

	/**
	 * Unique ID of form class instance.
	 *
	 * @since 2025.1
	 * @var   string
	 */
	private string $uniqid;

	/**
	 * List of all form fields
	 *
	 * @since 2025.1
	 * @var   array
	 */
	private array $fields = [];

	/**
	 * Form default attributes
	 *
	 * @since 2025.1
	 * @var   array
	 */
	private array $attributes = [];

	/**
	 * ID of the field before which the new field will be added
	 *
	 * @since 2025.1
	 * @var   string
	 */
	private string $before = '';

	/**
	 * ID of the field after which the new field will be added
	 *
	 * @since 2025.1
	 * @var   string
	 */
	private string $after = '';

	/**
	 * ID of the field to be replaced with new field
	 *
	 * @since 2025.1
	 * @var   string
	 */
	private string $instead = '';

	/**
	 * Add 'form' tag wrapper for form content.
	 *
	 * @param array $attributes
	 * @param string $content
	 * @return string
	 */
	private function wrap( array $attributes, string $content = '' ): string {
		$wrapper = sprintf( "<form%s>\n%s</form>\n", Arr::toHtmlAtts( $attributes ), $content );

		/**
		 * Override form wrapper
		 *
		 * @param array  $wrapper    Form wrapper markup.
		 * @param array  $attributes Form html attributes.
		 * @param string $content    Parsed form content.
		 *
		 * @since 2025.1
		 */
		return Hook::apply( 'grafema_form_wrap', $wrapper, $attributes, $content );
	}

	/**
	 * Insert new fields to any place in existing form.
	 *
	 * @param array $fields
	 * @param array $field
	 * @param Form $form
	 */
	private function insertField( array &$fields, array $field, Form $form ): void {
		$index    = false;
		$location = current( array_filter( [ $form->after, $form->before, $form->instead ] ) );
		if ( $location ) {
			$index = array_search( $location, array_column( $fields, 'uid' ), true );
		}

		if ( $index !== false ) {
			match ( true ) {
				!!$form->after   => array_splice( $fields, $index + 1, 0, [ $field ] ),
				!!$form->before  => array_splice( $fields, $index, 0, [ $field ] ),
				!!$form->instead => $fields[ $index ] = $field,
			};
		} else  {
			$fields[] = $field;
		}
	}

	/**
	 * Get fields html from array.
	 *
	 * @param array $fields
	 * @param int $step
	 * @return string
	 */
	private function parseFields( array $fields, int $step = 1 ): string {
		$content = '';
		foreach ( $fields as $field ) {
			$type     = Sanitizer::key( $field['type'] ?? '' );
			$name     = Sanitizer::name( $field['uid'] ?? '' );
			$uid      = Sanitizer::dot( $name );
			$callback = $field['callback'] ?? null;

			if ( $type === 'tab' && ! isset( $startTab ) ) {
				$startTab = true;
				$content .= View::get( GRFM_DASHBOARD . 'templates/form/layout/tab-menu', [ 'fields' => $fields ] );
			}

			// add required attributes & other manipulations
			$field['attributes'] = Sanitizer::array( $field['attributes'] ?? [] );
			if ( ! in_array( $type, [ 'tab', 'group' ], true ) ) {
				$field['attributes'] = [ 'type' => $type, 'name' => $name, ...$field['attributes'] ];
				if ( ! in_array( $type, [ 'step' ], true ) ) {
					$field['attributes']['x-model.fill'] = $uid;
				}

				match ( $type ) {
					'step'     => $field['attributes']['x-wizard:step'] ??= '',
					'textarea' => $field['attributes']['x-textarea'] ??= '',
					'select'   => $field['attributes']['x-select'] ??= '',
					'date'     => $field['attributes']['x-datepicker'] ??= '',
					default    => '',
				};

				if ( in_array( $type, [ 'select', 'submit' ], true ) ) {
					unset( $field['attributes']['type'] );
				}

				if ( in_array( $type, [ 'color', 'date', 'datetime-local', 'email', 'hidden', 'month', 'range', 'search', 'tel', 'text', 'time', 'url', 'week' ], true ) ) {
					$type = 'input';
				}
			}

			// parse conditions
			if ( ! empty( $field['conditions'] ) ) {
				$field['conditions'] = $this->parseConditions( $field['conditions'] );
			}

			$content .= match ( $type ) {
				'tab',
				'step',
				'group' => View::get(
					GRFM_DASHBOARD . "templates/form/layout/{$type}",
					[
						'columns'    => 2,
						'width'      => 100,
						'content'    => $this->parseFields( $field['fields'] ?? [], $step + 1 ),
						'step'       => $step++,
						'attributes' => [
							'x-wizard:step' => '',
							...$field['attributes']
						],
						...$field
					]
				),
				'custom' => is_callable( $callback ) ? call_user_func( $callback ) : '',
				default  => View::get( GRFM_DASHBOARD . "templates/form/{$type}", $field ),
			};
		}

		return $content;
	}

	/**
	 * Generate conditions attributes.
	 *
	 * @param $conditions_list
	 * @return string
	 */
	private function parseConditions( $conditions_list ): string {
		$jsString = '';
		if ( $conditions_list ) {
			$conditions = [];

			foreach ( $conditions_list as $condition ) {
				[ 'field' => $field, 'operator' => $operator, 'value' => $value ] = $condition;

				$condition = '';

				switch ( $operator ) {
					case '!==':
						$condition = "$field !== ''";
						break;
					case '===':
						if ( is_array( $value ) ) {
							$values    = Json::encode( $value );
							$condition = "$values.includes($field)";
						} else {
							$condition = "$field === '$value'";
						}
						break;
					case 'contains':
						$values    = str_replace( '"', "'", Json::encode( $value ) );
						$condition = "$values.includes($field)";
						break;
					case 'pattern':
						$values    = Json::encode( $value );
						$condition = "$values.some(value => value.test($field))";
						break;
				}

				if ( $condition !== '' ) {
					$conditions[] = $condition;
				}
			}

			$jsString = implode( ' && ', $conditions );
		}
		return $jsString;
	}
}
