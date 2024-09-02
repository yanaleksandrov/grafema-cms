import Selection from './storage/selection';

document.addEventListener( 'alpine:init', () => {

	/**
	 * Intersect event
	 *
	 * @since 1.0
	 */
	Alpine.directive('storage', (el, {value, expression, modifiers}, {evaluateLater, cleanup}) => {
		let selection = new Selection({
			container: document.querySelector('.parent'),
			onSelectEnd: selection => {
				console.log(selection)
			}
		});
		console.log(selection);
	});
});