import Selection from './storage/selection';

document.addEventListener( 'alpine:init', () => {

	/**
	 * Storage functions
	 *
	 * @since 1.0
	 */
	Alpine.directive('storage', (el, {value, expression, modifiers}, {evaluateLater, cleanup}) => {
		let selection = new Selection({
			container: document.querySelector('.storage'),
			selector: '.storage__item',
			classSelected: 'active',
			onSelectEnd: selection => {
				console.log(selection)
			}
		});
		console.log(selection);
	});
});