<?php

namespace Carbon_Field_Map_Yandex;

use Carbon_Fields\Field\Field;
use Carbon_Fields\Value_Set\Value_Set;

class Map_Yandex_Field extends Field {

	/**
	 * {@inheritDoc}
	 */
	protected $default_value = array(
		Value_Set::VALUE_PROPERTY => '40.346544,-101.645507',
		'lat'                     => 40.346544,
		'lng'                     => -101.645507,
		'zoom'                    => 10,
		'address'                 => '',
	);

	/**
	 * Create a field from a certain type with the specified label.
	 *
	 * @param string $type  Field type.
	 * @param string $name  Field name.
	 * @param string $label Field label.
	 */
	public function __construct( $type, $name, $label ) {
		$this->set_value_set(
			new Value_Set(
				Value_Set::TYPE_MULTIPLE_PROPERTIES,
				array(
					'lat'     => '',
					'lng'     => '',
					'zoom'    => '',
					'address' => '',
				)
			)
		);

		parent::__construct( $type, $name, $label );
	}

	/**
	 * Enqueue scripts and styles in admin
	 * Called once per field type
	 */
	public static function admin_enqueue_scripts() {
		$root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( \Carbon_Field_Map_Yandex\DIR );
		$api_key  = apply_filters( 'carbon_fields_map_yandex_field_api_key', false );
		$url      = apply_filters(
			'carbon_fields_map_yandex_field_api_url',
			'https://api-maps.yandex.ru/2.1/?apikey=' . $api_key . '&lang=ru_RU',
			$api_key
		);

		wp_enqueue_script( 'carbon-yandex-maps', $url, array(), '2.1', false );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Enqueue field scripts.
		wp_enqueue_style(
			'carbon-field-map-yandex',
			"{$root_uri}/build/bundle{$suffix}.css",
			array(),
			'1.0.0'
		);

		// Enqueue field scripts.
		wp_enqueue_script(
			'carbon-field-map-yandex',
			"{$root_uri}/build/bundle{$suffix}.js",
			array( 'carbon-fields-core' ),
			'1.0.0',
			true
		);
	}

	/**
	 * Convert lat and lng to a comma-separated list
	 */
	protected function lat_lng_to_latlng( $lat, $lng ) {
		return ( ! empty( $lat ) && ! empty( $lng ) ) ? $lat . ',' . $lng : '';
	}

	/**
	 * Load the field value from an input array based on its name
	 *
	 * @param  array $input Array of field names and values.
	 * @return self  $this
	 */
	public function set_value_from_input( $input ) {
		if ( ! isset( $input[ $this->get_name() ] ) ) {
			$this->set_value( null );
			return $this;
		}

		$value_set = array(
			'lat'     => '',
			'lng'     => '',
			'zoom'    => '',
			'address' => '',
		);

		foreach ( $value_set as $key => $v ) {
			if ( isset( $input[ $this->get_name() ][ $key ] ) ) {
				$value_set[ $key ] = $input[ $this->get_name() ][ $key ];
			}
		}

		$value_set['lat']  = (float) $value_set['lat'];
		$value_set['lng']  = (float) $value_set['lng'];
		$value_set['zoom'] = (int) $value_set['zoom'];

		$value_set[ Value_Set::VALUE_PROPERTY ] = $this->lat_lng_to_latlng( $value_set['lat'], $value_set['lng'] );

		$this->set_value( $value_set );
		return $this;
	}

	/**
	 * Returns an array that holds the field data, suitable for JSON representation.
	 *
	 * @param bool $load  Should the value be loaded from the database or use the value from the current instance.
	 * @return array
	 */
	public function to_json( $load ) {
		$field_data = parent::to_json( $load );

		$value_set = $this->get_value();

		$field_data = array_merge(
			$field_data,
			array(
				'value' => array(
					'lat'     => floatval( $value_set['lat'] ),
					'lng'     => floatval( $value_set['lng'] ),
					'zoom'    => intval( $value_set['zoom'] ),
					'address' => $value_set['address'],
					'value'   => $value_set[ Value_Set::VALUE_PROPERTY ],
				),
			)
		);

		return $field_data;
	}

	/**
	 * Set the coords and zoom of this field.
	 *
	 * @param  string $lat  Latitude.
	 * @param  string $lng  Longitude.
	 * @param  int    $zoom Zoom level.
	 * @return $this
	 */
	public function set_position( $lat, $lng, $zoom ) {
		return $this->set_default_value(
			array_merge(
				$this->get_default_value(),
				array(
					Value_Set::VALUE_PROPERTY => $this->lat_lng_to_latlng( $lat, $lng ),
					'lat'                     => $lat,
					'lng'                     => $lng,
					'zoom'                    => $zoom,
				)
			)
		);
	}
}
