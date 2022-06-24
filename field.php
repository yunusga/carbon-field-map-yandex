<?php

use Carbon_Fields\Carbon_Fields;
use Carbon_Field_Map_Yandex\Map_Yandex_Field;

define( 'Carbon_Field_Map_Yandex\\DIR', __DIR__ );

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
