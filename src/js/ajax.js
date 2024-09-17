document.addEventListener( 'alpine:init', () => {
	let onloadEvent = () => {};
	let formData    = new FormData();

	/**
	 * Ajax
	 *
	 * @since 1.0
	 */
	const xhr = new XMLHttpRequest();
	Alpine.magic( 'ajax', el => (route, data, progressCallback) => {
		document.addEventListener(route, ({detail: {data, resolve}}) => resolve(data));

		return new Promise(resolve => {
			xhr.open(el.getAttribute('method')?.toUpperCase() ?? 'POST', grafema?.apiurl + route);

			xhr.withCredentials = true;
			xhr.responseType    = 'json';

			// regular ajax sending & request with file uploading
			xhr.onloadstart = xhr.upload.onprogress = event => progressCallback?.(onProgress(event, xhr));
			xhr.onloadend   = event => progressCallback?.(onProgress(event, xhr));
			xhr.onload      = event => {
				try {
					let {data, fragments} = xhr.response;

					document.dispatchEvent(
						new CustomEvent(route, {
							detail: {data: data, event, el, resolve},
							bubbles: true,
							// Allows events to pass the shadow DOM barrier.
							composed: true,
							cancelable: true
						})
					);

					if (fragments) {
						fragments.forEach(({method, fragment, selectors, delay}) => {
							parseFragment(method, fragment, selectors, delay)
						});
					}
				} catch (e) {
					console.error(e);
				}

				onloadEvent && onloadEvent();
			};

			xhr.send(parseFormData(el, data));
		});
	});

	/**
	 *
	 * @param event
	 * @param xhr
	 * @returns {{loaded: *, total: *, blob: Blob, start: boolean, raw: string, progress: boolean, end: boolean, percent: number|number, url: string, status: string}}
	 */
	function onProgress(event, xhr) {
		const { loaded = 0, total = 0, type } = event;
		const { response = '', status = '', responseURL = '' } = xhr;

		let data = {
			blob: new Blob([response]),
			raw: response,
			status,
			url: responseURL,
			loaded: convertTo(loaded),
			total: convertTo(total),
			percent: total > 0 ? Math.round((loaded / total) * 100) : 0,
			start: type === 'loadstart',
			progress: type === 'progress',
			end: type === 'loadend',
		}

		if (data.end) {
			console.log(data);
		}

		return data;
	}

	/**
	 *
	 * @param number
	 * @returns {number}
	 */
	const BYTES_IN_MB = 1048576;
	function convertTo(number) {
		return Math.round(number / BYTES_IN_MB * 100) / 100;
	}

	/**
	 *
	 * @param el
	 * @param data
	 * @returns {FormData}
	 */
	function parseFormData(el, data) {
		switch (el.tagName) {
			case 'BUTTON':
				el.classList.add('btn--load');

				onloadEvent = () => el.classList.remove('btn--load');
				break;
			case 'FORM':
				let buttons = el.querySelectorAll("[type='submit']");

				formData = new FormData(el);

				let inputs = el.querySelectorAll("input[type='file']");
				[...inputs].forEach(input => {
					let files = input.files;
					files && [...files].forEach((file, index) => formData.append(index, file));
				});

				buttons && buttons.forEach(button => button.classList.add('btn--load'));

				onloadEvent = () => buttons && buttons.forEach(button => button.classList.remove('btn--load'));
				break;
			case 'TEXTAREA':
			case 'SELECT':
			case 'INPUT':
				el.type !== 'file' && el.name && formData.append(el.name, el.value);
				break;
		}

		if (typeof data === 'object') {
			for (const [key, value] of Object.entries(data)) {
				formData.append(key, value);
			}
		}

		return formData;
	}

	/**
	 *
	 * @param method
	 * @param fragment
	 * @param selectors
	 * @param delay
	 */
	function parseFragment(method, fragment, selectors = 'body', delay) {
		[...document.querySelectorAll(selectors)].forEach(target => {
			setTimeout(() => {
				switch (method) {
					case 'changeURL':
						window.history.pushState(null, null, fragment || '');
						break;
					case 'redirect':
						window.location = fragment || '';
						break;
					case 'reload':
						window.location.reload();
						break;
					case 'scrollTo':
						window.scrollBy({
							top: target.getBoundingClientRect().top,
							behavior: 'smooth'
						});
						break;
					case 'value':
						target.value = fragment || ''
						break;
					case 'update':
						target.innerHTML = fragment || '';
						break;
					case 'remove':
						target.remove();
						break;
					case 'replace':
						target.outerHTML = fragment || '';
						break;
					case 'afterend':
					case 'beforeend':
					case 'afterbegin':
					case 'beforebegin':
						target.insertAdjacentHTML(method, fragment || '');
						break;
					case 'classList.remove':
						target.classList.remove(fragment || '');
						break;
					case 'classList.add':
						target.classList.add(fragment || '');
						break;
					case 'setAttribute':
						const [name, value] = fragment;
						if(!name) {
							return null;
						}
						target.setAttribute(name, value || '');
						break;
					default:
						// use 'prepend, append, replaceWith, removeAttribute or scrollIntoView' methods
						target[method](fragment || '');
				}
			}, delay || 0);
		});
	}
});