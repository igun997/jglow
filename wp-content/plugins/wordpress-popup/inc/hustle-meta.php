<?php
/**
 * Abstract Hustle_Meta class.
 * Extended by each handler of the modules' metas.
 *
 * When creating a new meta property:
 * -Booleans properties must be '0' and '1', as strings. Make sure they're also stored in this way when saving.
 */
abstract class Hustle_Meta {

	protected $data;

	/**
	 * Current module.
	 *
	 * @since unknown
	 * @var Hustle_Model
	 */
	protected $module;

	protected $defaults = array();

	public function __construct( array $data, Hustle_Model $model ) {
		$this->data     = $data;
		$this->module   = $model;
		$this->defaults = apply_filters( 'hustle_meta_get_defaults', $this->get_defaults(), $this, $model, $data );
	}

	public function to_array() {

		$defaults = $this->get_defaults();
		if ( $defaults ) {
			if ( isset( $defaults['form_elements'] ) && ! empty( $this->data['form_elements'] ) ) {
				unset( $defaults['form_elements'] );
			}

			return array_replace_recursive( $defaults, $this->data );
		}

		return $this->data;
	}

	/**
	 * Return an array with the default values.
	 * Can be overridden to return an array of default values
	 * without restricting to static values.
	 *
	 * @since 4.0
	 *
	 * @return array|false
	 */
	public function get_defaults() {

		if ( isset( $this->defaults ) && is_array( $this->defaults ) ) {
			return $this->defaults;
		}

		return false;
	}

}
