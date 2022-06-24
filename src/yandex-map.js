/**
 * External dependencies.
 */
import { Component, createRef } from '@wordpress/element';

class YandexMap extends Component {
	/**
	 * Keeps references to the DOM node.
	 *
	 * @type {Object}
	 */
	node = createRef();

	/**
	 * Lifecycle hook.
	 *
	 * @return {void}
	 */
	componentDidMount() {
		ymaps.ready(() => {
			this.setupMap();
			this.setupMapEvents();
			this.updateMap( this.props );
		});
	}

	/**
	 * Lifecycle hook.
	 *
	 * @return {void}
	 */
	componentDidUpdate() {
		const {
			lat,
			lng,
			zoom,
		} = this.props;

		this.updateMap( this.props );
	}

	/**
	 * Lifecycle hook.
	 *
	 * @return {void}
	 */
	componentWillUnmount() {
		// this.cancelResizeObserver();
		this.map.destroy();
	}

	getSnapshotBeforeUpdate(prevProps) {
		const { lat, lng, address } = this.props;

		if (prevProps.address !== address) {
			this.map.setCenter([lat, lng]);
			this.placemark.geometry.setCoordinates([lat, lng]);
		}
	}

	/**
	 * Initializes the map into placeholder element.
	 *
	 * @return {void}
	 */
	setupMap() {
		const {
			lat,
			lng,
			zoom
		} = this.props;

		const position = [lat, lng];

		this.map = new ymaps.Map(this.node.current, {
			center: position,
			zoom: zoom,
			controls: ['zoomControl', 'typeSelector'],
		});

		this.map.behaviors.disable('scrollZoom');

		this.placemark = new ymaps.Placemark(position, {
			balloonContent: '',
			iconCaption: '',
		}, { draggable: true });

		// Добавляем первый найденный геообъект на карту.
    this.map.geoObjects.add(this.placemark);
	}

	/**
	 * Adds the listeners for the map's events.
	 *
	 * @return {void}
	 */
	setupMapEvents() {
		this.map.events.add(['boundschange'], () => {
			this.props.onChange({
				zoom: this.map.getZoom(),
			});
		});

		this.map.events.add(['click'], (event) => {
			event.stopPropagation();

			const coords = event.get('coords');

			this.props.onChange({
				lat: coords[0],
				lng: coords[1],
			});

			this.placemark.geometry.setCoordinates(coords);
		});

		this.placemark.events.add(['dragend'], () => {
			const coords = this.placemark.geometry.getCoordinates();

			this.props.onChange({
				lat: coords[0],
				lng: coords[1],
			});
    });
	}

	/**
	 * Re-draws the map.
	 *
	 * @param  {Object} props
	 * @return {void}
	 */
	updateMap( props ) {
		const { lat, lng } = props;
		const coords = [lat, lng];

		setTimeout(() => {
			this.map.container.fitToViewport();
		}, 10);
	}

	/**
	 * Renders the component.
	 *
	 * @return {Object}
	 */
	render() {
		return (
			<div ref={ this.node } className={ this.props.className }></div>
		);
	}
}

export default YandexMap;
