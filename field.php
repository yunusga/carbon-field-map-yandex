<?php
/**
 * Main custom field file.
 *
 * @package carbon-field-map-yandex
 */

use Carbon_Fields\Carbon_Fields;
use Carbon_Field_Map_Yandex\Map_Yandex_Field;

define( 'Carbon_Field_Map_Yandex\\DIR', __DIR__ );

/**
 * Activate field when carbon_fields_register_fields hook fired
 */
function carbon_fields_extend_with_map_yandex_field() {
	Carbon_Fields::extend(
		Map_Yandex_Field::class,
		function( $container ) {
			return new Map_Yandex_Field(
				$container['arguments']['type'],
				$container['arguments']['name'],
				$container['arguments']['label']
			);
		}
	);
}
add_action( 'carbon_fields_register_fields', 'carbon_fields_extend_with_map_yandex_field', 10 );
