/**
 * External dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { debounce } from 'lodash';

/**
 * The internal dependencies.
 */
import './style.css';
import SearchInput from './search-input';
import YandexMap from './yandex-map';

class MapYandexField extends Component {
	/**
	 * Handles the change of search.
	 *
	 * @param  {string} address
	 * @return {void}
	 */
	handleSearchChange = debounce( ( address ) => {
		if ( address ) {
			const {
				id,
				value,
				onChange
			} = this.props;

			ymaps.geocode(address, {
				results: 1,
				}).then((res) => {
					const coords = res.geoObjects.get(0).geometry.getCoordinates();

					onChange( id, {
						...value,
						address: address,
						value: `${coords[0]},${coords[1]}`,
						lat: coords[0],
						lng: coords[1],
					} );
				});
		}
	}, 250 );

	/**
	 * Handles the change of map location.
	 *
	 * @param  {Object} location
	 * @return {void}
	 */
	handleMapChange = ( location ) => {
		const {
			id,
			value,
			onChange
		} = this.props;

		onChange( id, {
			...value,
			...location
		} );
	}

	/**
	 * Renders the component.
	 *
	 * @return {Object}
	 */
	render() {
		const {
			id,
			name,
			value
		} = this.props;

		return (
			<Fragment>
				<SearchInput
					id={ id }
					className="cf-map__search"
					name={ `${ name }[address]` }
					value={ value.address }
					onChange={ this.handleSearchChange }
				/>

				<YandexMap
					className="cf-map__canvas"
					lat={ value.lat }
					lng={ value.lng }
					zoom={ value.zoom }
					address={ value.address }
					onChange={ this.handleMapChange }
				/>

				<input
					type="hidden"
					name={ `${ name }[lat]` }
					value={ value.lat }
					readOnly
				/>

				<input
					type="hidden"
					name={ `${ name }[lng]` }
					value={ value.lng }
					readOnly
				/>

				<input
					type="hidden"
					name={ `${ name }[zoom]` }
					value={ value.zoom }
					readOnly
				/>
			</Fragment>
		);
	}
}

export default MapYandexField;
