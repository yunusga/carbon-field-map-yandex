# Carbon Fields Map Yandex

The map field provides a Yandex-powered map with an address search field.

```php
Field::make( 'map_yandex', 'crb_map', __( 'Yandex Map' ) )

## Install via Composer

```bash
composer require yunusga/carbon-field-map-yandex
```

## Information

The field stores several pieces of related information:

| Atribute    | Description          | Value Type   | Value                                  |
| ------------| -------------------- | ------------ | -------------------------------------- |
| `'value'`   | Latitude & Longitude | `(string)`   | 11.370946,142.591472                   |
| `'lat'`     | Latitude             | `(float)`    | 11.370946                              |
| `'lng'`     | Longitude            | `(float)`    | 142.591472                             |
| `'address'` | Address              | `(string)`   | The lowest point of the Mariana Trench |
| `'zoom'`    | Zoom level           | `(int)`      | 4                                      |

### Yandex Maps JS Key setup

As of October 11, 2018, Yandex requires users to generate an API key in order to use the Yandex Maps API: <https://yandex.ru/blog/mapsapi/novye-pravila-dostupa-k-api-kart?from=club>

You can get your API key here: https://developer.tech.yandex.ru/services/3/

Once you're ready with the process of generating a key, you'll need to provide the key to Carbon Fields through a filter:

```php
/**
 * Provide the Yandex Maps API key to Carbon Fields
 * 
 * @param string $key Yandex JS API key.
 */
function crb_get_yandex_maps_api_key( $key ) {
	return 'your key goes here';
}
add_filter( 'carbon_fields_map_yandex_field_api_key', 'crb_get_yandex_maps_api_key' );
```

## Config methods

### `set_position( $lat, $lng, $zoom )`

Set the default position on the map specified by `$lat` and `$lng` and the default zoom level to `$zoom` (zoom `0` corresponds to a map of the Earth fully zoomed out).

```php
Field::make( 'map_yandex', 'crb_company_location', __( 'Location' ) )
	->set_help_text( __( 'drag and drop the pin on the map to select location' ) )
```

## Usage

To get all the location data as an array, you can use the `map_yandex` type in the retrieval functions. Example:

```php
/* Get the location data */
$map_yandex = carbon_get_post_meta( $post_id, $field_name ); // array( 'value' => '40.74866,-73.97982', lat' => 40.74866, 'lng' => -73.97982, 'address' => '45 Park Avenue,  New York, NY 10016', 'zoom' => 8)
```

## Value Format

```php
array(
	'value'   => '11.370946,142.591472',
	'lat'     => 11.370946,
	'lng'     => 142.591472,
	'zoom'    => 4,
	'address' => 'The lowest point of the Mariana Trench',
)
```
