document.addEventListener( 'alpine:init', () => {

	/**
	 * Updates the URL parameters based on the provided FormData.
	 * Removes missing parameters and adds new parameters.
	 *
	 * @param {FormData} formData - The FormData containing the new parameters.
	 * @param {string} [url=window.location.href] - The URL to update. Defaults to the current window location.
	 * @returns {void}
	 */
	function updateUrlParams(formData, url = window.location.href) {
		const urlObj = new URL(url);
		const params = new URLSearchParams(urlObj.search);

		// remove params
		for (const [key] of params) {
			if (!formData.has(key)) {
				params.delete(key);
			}
		}

		// add new params
		for (const [key, value] of formData.entries()) {
			params.set(key, value);
		}

		urlObj.search = params.toString();

		window.history.replaceState({}, '', urlObj.toString());
	}

	/**
	 * Intersect event
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'intersect', (el, { value, expression, modifiers }, { evaluateLater, cleanup }) => {
		function getThreshold(modifiers) {
			if (modifiers.includes("full"))
				return 0.99;
			if (modifiers.includes("half"))
				return 0.5;
			if (!modifiers.includes("threshold"))
				return 0;
			let threshold = modifiers[modifiers.indexOf("threshold") + 1];
			if (threshold === "100")
				return 1;
			if (threshold === "0")
				return 0;
			return Number(`.${threshold}`);
		}
		function getLengthValue(rawValue) {
			let match = rawValue.match(/^(-?[0-9]+)(px|%)?$/);
			return match ? match[1] + (match[2] || "px") : void 0;
		}
		function getRootMargin(modifiers) {
			const key = "margin";
			const fallback = "0px 0px 0px 0px";
			const index = modifiers.indexOf(key);
			if (index === -1)
				return fallback;
			let values = [];
			for (let i = 1; i < 5; i++) {
				values.push(getLengthValue(modifiers[index + i] || ""));
			}
			values = values.filter((v) => v !== void 0);
			return values.length ? values.join(" ").trim() : fallback;
		}

		let evaluate = evaluateLater(expression);
		let options = {
			rootMargin: getRootMargin(modifiers),
			threshold: getThreshold(modifiers)
		};
		let observer = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting === (value === "leave")) {
					return;
				}
				evaluate();
				modifiers.includes("once") && observer.disconnect();
			});
		}, options);
		observer.observe(el);
		cleanup(() => observer.disconnect());
	});

	/**
	 * Sticky sidebar
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'sticky', el => {
		let style = el.parentElement.currentStyle || window.getComputedStyle(el.parentElement);
		if (style.position !== 'relative') {
			return false;
		}

		let rect  = el.getBoundingClientRect();
		let diff  = rect.height - document.scrollingElement.offsetHeight;

		let paddingTop    = parseInt(style.paddingTop) + 42;
		let paddingBottom = parseInt(style.paddingBottom);

		let lastScroll  = 0;
		let bottomPoint = 0;
		let value       = 'top: ' + paddingTop + 'px';

		function calcPosition() {
			if ( diff > 0 ) {
				let y = document.scrollingElement.scrollTop;
				// scroll to down
				if ( window.scrollY > lastScroll ) {
					if (y > diff) {
						bottomPoint = ( diff * -1 - paddingBottom );

						value = 'top: ' + bottomPoint + 'px';
					} else {
						value = 'top: ' + ( y * -1 - paddingBottom ) + 'px';
					}
				} else {
					bottomPoint = bottomPoint + (lastScroll - window.scrollY);
					if (bottomPoint < paddingTop) {
						value = 'top: ' + bottomPoint + 'px';
					}
				}
			}
			el.setAttribute('style', 'position: sticky;' + value);

			lastScroll = window.scrollY;
		}

		window.addEventListener('load', () => calcPosition());
		window.addEventListener('scroll', () => calcPosition());
		window.addEventListener('resize', () => calcPosition());
	});

	/**
	 * Disable autocomplete
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'autocomplete', el => {
		el.setAttribute('readonly', true);
		el.onfocus = () => setTimeout(() => el.removeAttribute('readonly'), 10);
		el.onblur  = () => el.setAttribute('readonly', true);
	});

	/**
	 * Code syntax highlight
	 *
	 * @since 1.0
	 */
	Alpine.directive('highlight', (el, { modifiers }) => {
		let lang    = modifiers[0] || 'html',
			wrapper = document.createElement('code');

		wrapper.classList.add('language-' + lang);
		wrapper.innerHTML = el.innerHTML;

		el.classList.add('line-numbers');
		el.innerHTML = '';
		el.setAttribute('data-lang', lang.toUpperCase());
		el.appendChild(wrapper);
	});

	/**
	 * Allows to expand and collapse elements using smooth animations.
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'collapse', ( el, { modifiers }) => {
		let duration = ( ( modifiers, key = 'duration', fallback = 350 ) => {
			if ( modifiers.indexOf( key ) === -1 )
				return fallback;
			const rawValue = modifiers[modifiers.indexOf(key) + 1];
			if ( !rawValue )
				return fallback;
			if ( key === 'duration' ) {
				let match = rawValue.match(/([0-9]+)ms/);
				if ( match )
					return match[1];
			}
			return rawValue;
		} )( modifiers ) / 1e3;
		let floor = 0;
		if ( !el._x_isShown ) {
			el.style.height = `${floor}px`;
			el.style.overflow = 'hidden';
			el.hidden = true;
		}
		let setFunction = ( el2, styles ) => {
			let revertFunction = Alpine.setStyles( el2, styles );
			return styles.height ? () => {} : revertFunction;
		}
		let transitionStyles = {
			overflow: 'hidden',
			transitionProperty: 'height',
			transitionDuration: `${duration}s`,
			transitionTimingFunction: 'cubic-bezier(0.4, 0.0, 0.2, 1)'
		}
		el._x_transition = {
			in(
				before = () => {},
				after = () => {}
			) {
				el.hidden = false;
				let current = el.getBoundingClientRect().height;
				Alpine.setStyles( el, {
					display: null,
					height: 'auto'
				} );
				let full = el.getBoundingClientRect().height;
				Alpine.setStyles( el, {
					overflow: 'hidden'
				} );
				if ( current === full ) {
					current = floor;
				}
				Alpine.transition( el, Alpine.setStyles, {
					during: transitionStyles,
					start: {
						height: current + 'px'
					},
					end: {
						height: full + 'px'
					}
				}, () => el._x_isShown = true, () => {
				} )
			},
			out(
				before = () => {},
				after = () => {}
			) {
				let full = el.getBoundingClientRect().height;
				Alpine.transition( el, setFunction, {
					during: transitionStyles,
					start: {
						height: full + 'px'
					},
					end: {
						height: floor + 'px'
					}
				}, () => {
				}, () => {
					el._x_isShown = false;
					if ( el.style.height === `${floor}px` ) {
						Alpine.nextTick( () => {
							Alpine.setStyles( el, {
								display: 'none',
								overflow: 'hidden'
							} );
							el.hidden = true;
						} );
					}
				} )
			}
		};
	} )

	/**
	 * Copy data to clipboard.
	 *
	 * @since 1.0
	 */
	Alpine.magic( 'copy', el => subject => {
		window.navigator.clipboard.writeText(subject).then(
			() => {
				let classes = 'ph-copy ph-check'.split(' ');

				classes.forEach(s => el.classList.toggle(s));
				setTimeout( () => classes.forEach(s => el.classList.toggle(s)), 1000 )
			},
			() => {
				console.log( 'Oops, your browser is not support clipboard!' );
			}
		);
	});

	/**
	 * Countdown magic
	 *
	 * @since 1.0
	 */
	let seconds = 0, isCountingDown = false;
	Alpine.magic( 'countdown', () => {
		return {
			start: (initialSeconds, processCallback, endCallback) => {
				if (isCountingDown) {
					return;
				}
				seconds = initialSeconds;
				isCountingDown = true;
				function countdown() {
					processCallback && processCallback(true);
					if (seconds === 0) {
						endCallback && endCallback(true);
						isCountingDown = false;
					} else {
						seconds--;
						setTimeout(countdown, 1000);
					}
				}
				countdown();
			},
			second: seconds,
		};
	});

	/**
	 * Selfie
	 *
	 * @since 1.0
	 */
	let stream = null;
	Alpine.magic( 'stream', () => {
		return {
			check(refs) {
				let canvas = refs.canvas,
					video  = refs.video,
					image  = refs.image;

				if (!canvas) {
					console.error('Canvas element is undefined');
					return false;
				}

				if (!video) {
					console.error('Video for selfie preview is undefined');
					return false;
				}

				if (!image) {
					console.error('Image for output selfie is undefined');
					return false;
				}
			},
			isVisible(element) {
				const styles = window.getComputedStyle(element);
				if (styles) {
					return !(styles.visibility === 'hidden' || styles.display === 'none' || parseFloat(styles.opacity) === 0);
				}
				return false;
			},
			start(refs) {
				let video = refs.video;
				const observer = new MutationObserver( mutations => {
					for (let mutation of mutations) {
						if (mutation.target === document.body && !stream ) {
							setTimeout(async () => {
								if (this.isVisible(video)) {
									if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
										video.srcObject = stream = await navigator.mediaDevices.getUserMedia({video: true});
									} else {
										console.error('The browser does not support the getUserMedia API');
									}
								}
							}, 500);
						}
					}
				});
				observer.observe(document, {childList: true,subtree: true,attributes: true});
			},
			snapshot(refs) {
				this.check(refs);
				this.start(refs);

				let canvas = refs.canvas,
					video  = refs.video,
					image  = refs.image;

				let width  = video.offsetWidth,
					height = video.offsetHeight;

				let imageStyles = window.getComputedStyle(image),
					imageWidth  = parseInt(imageStyles.width, 10),
					imageHeight = parseInt(imageStyles.height, 10);

				canvas.width  = imageWidth;
				canvas.height = imageHeight;

				let offsetTop  = ( height - imageHeight ) / 2,
					offsetLeft = ( width - imageWidth ) / 2;

				let ctx = canvas.getContext('2d');

				ctx.imageSmoothingQuality = 'low';

				let scale = height / imageHeight;
				console.log((offsetTop + offsetLeft) / 2)
				//ctx.drawImage(video, 0, 0, width * 2, height * 2, 0, 0, width, height);
				//ctx.drawImage(video, 0, 0, imageWidth, imageHeight);
				ctx.drawImage(video, offsetLeft * 1.5, offsetTop * 1.5, height * 1.5, height * 1.5, 0, 0, imageWidth, imageHeight);

				let imageData = canvas.toDataURL('image/png');
				if ( imageData ) {
					image.src = imageData;
					ctx.clearRect(0, 0, canvas.width, canvas.height);
				}
				return imageData;
			},
			stop() {
				if (stream) {
					stream.getTracks().forEach(track => track.stop());
				}
				stream = null;
			}
		}
	});

	/**
	 * Smooth scrolling to the anchor
	 * TODO: придостижении верха страницы, удалять анкор, то же при загрузке старницы
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'anchor', ( el, { value, expression, modifiers }, { evaluateLater, cleanup } ) => {
		let hash   = window.location.hash.replace( '#', '' ),
			anchor = el.innerText.toLowerCase().replaceAll( ' ', '-' );

		// scroll when init page
		if ( hash && hash === anchor ) {
			el.scrollIntoView({
				behavior: 'smooth',
			})
		}

		// click for copy url with hash
		el.addEventListener( 'click', e => {
			e.preventDefault();
			window.location.hash = anchor;
			el.scrollIntoView({
				behavior: 'smooth',
			})
		}, false )

		// watch the appearance of an anchor on the page and automatically add it to url
		let evaluate = evaluateLater( expression || null );
		let observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( entry => {
				if ( ! entry.isIntersecting || entry.intersectionRatio !== 1 ) {
					return;
				}
				evaluate();
				window.location.hash = anchor;
			});
		}, {
			threshold: 0.5
		} );
		observer.observe(el);
		cleanup(() => observer.disconnect());
	} )

	/**
	 * Listen audio
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'listen', ( el, { value, expression, modifiers }, { evaluateLater, effect } ) => {
		console.log(el)
		console.log(value)
		console.log(expression)
		console.log(modifiers)
		if ( ! expression ) {
			return false;
		}

		let evaluate = evaluateLater( expression )
		effect( () => {
			evaluate( content => {
				if ( content ) {
					let name = "listen-node";

					function _play( aud, icn ) {
						icn.classList.add("playing");
						aud.play();
						aud.setAttribute( "data-playing", "true" );
						aud.addEventListener("ended", function() {
							_pause( aud, icn );
							aud.parentNode.style.background = null;
							return false;
						});
					}

					function _pause( aud, icn ) {
						aud.pause();
						aud.setAttribute( "data-playing", "false" );
						icn.classList.remove("playing");
					}

					let aud, icn;
					let css = document.createElement("style");
					css.type = "text/css";
					css.innerHTML = ".listen-node {display: inline-block; background:rgba(0, 0, 0, 0.05); padding: 1px 8px 2px; border-radius:3px; cursor: pointer;} .listen-node i {font-size: 0.65em; border: 0.5em solid transparent; border-left: 0.75em solid; display: inline-block; margin-right: 2px;margin-bottom: 1px;} .listen-node .playing { border: 0; border-left: 0.75em double; border-right: 0.5em solid transparent; height: 1em;}";
					document.getElementsByTagName("head")[0].appendChild(css);

					aud = document.createElement( 'audio' );
					icn = document.createElement( 'i' );

					aud.src = el.getAttribute( "data-src" );
					aud.setAttribute( "data-playing", "false" );

					el.id = name + "-" + i;
					el.insertBefore( icn, el.firstChild );
					el.appendChild( aud );

					document.addEventListener( 'click', e => {
						let aud, elm, icn;
						if ( e.target.className === name ) {
							aud = e.target.children[1];
							elm = e.target;
							icn = e.target.children[0];
						}
						else if ( e.target.parentElement && e.target.parentElement.className === name ) {
							aud = e.target.parentElement.children[1];
							elm = e.target.parentElement;
							icn = e.target;
						}

						if (aud && elm && icn) {
							aud.srt = parseInt( elm.getAttribute( 'data-start' ) ) || 0;
							aud.end = parseInt( elm.getAttribute( 'data-end' ) ) || aud.duration;

							if ( aud && aud.getAttribute( "data-playing" ) === "false" ) {
								if ( aud.srt > aud.currentTime || aud.end < aud.currentTime ) {
									aud.currentTime = aud.srt;
								}
								_play( aud, icn );
							} else {
								_pause( aud, icn );
							}

							(function loop() {
								let d = requestAnimationFrame( loop );
								let percent = (((aud.currentTime - aud.srt) * 100) / (aud.end - aud.srt));
								percent = percent < 100 ? percent : 100;
								elm.style.background = "linear-gradient(to right, rgba(0, 0, 0, 0.1)" + percent + "%, rgba(0, 0, 0, 0.05)" + percent + "%)";

								if ( aud.end < aud.currentTime ) {
									_pause( aud, icn );
									cancelAnimationFrame( d );
								}
							})();
						}
					} );

					el.addEventListener( 'click', () => {

					}, false )
				}
			} )
		} )
	} )

	/**
	 * Automatically adjust the height of the textarea while typing.
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'textarea', ( el, { expression } ) => {
		if ( 'TEXTAREA' !== el.tagName.toUpperCase() ) {
			return false;
		}
		el.addEventListener( 'input', () => {
			let max  = parseInt(expression) || 99,
				rows = parseInt( el.value.split( /\r|\r\n|\n/ ).length );
			if ( rows > max ) {
				return false;
			}

			el.style.height = 'auto';
			el.style.height = ( el.scrollHeight + 1 ) + 'px';
		}, false );
	});

	/**
	 * Tooltips
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'tooltip', ( el, { value, expression, modifiers }, { evaluateLater, effect } ) => {
		let evaluate = evaluateLater(expression);
		effect(() => {
			evaluate( content => {
				let position, trigger;
				if (modifiers) {
					modifiers.forEach( modifier => {
						position = [ 'top', 'right', 'bottom', 'left' ].includes( modifier ) ? modifier : 'top';
						trigger  = [ 'hover', 'click' ].includes( modifier ) ? modifier : 'hover';
					});
				}

				if (position && trigger) {
					new Drooltip({
						element: el,
						trigger: trigger,
						position: position,
						background: '#fff',
						color: 'var(--grafema-dark)',
						animation: 'bounce',
						content: content || null,
						callback: null
					});
				}
			});
		});
	});

	/**
	 * Multistep
	 *
	 * @since 1.0
	 * @see based on https://github.com/glhd/alpine-wizard
	 */
	Alpine.directive("wizard", (el, { value, expression, modifiers }, { Alpine: Alpine2, evaluate, cleanup }) => {
		const wizard2 = getWizard(el, Alpine2);
		const step = wizard2.getStep(el);
		cleanup(() => step.cleanup());
		const evaluateCheck = () => [!!evaluate(expression), {}];
		Alpine2.effect(() => {
			step.evaluate = content => evaluate(content);
			if (expression !== "") {
				if (value === "step") {
					const [passes, errors] = evaluateCheck();
					step.is_complete = passes;
					step.errors = errors;
				}
				if (value === "action") {
					step.action = expression;
				}
			}
			if (value === "if") {
				step.is_applicable = evaluateCheck()[0];
			}
			if (value === "title") {
				if (modifiers.includes("dynamic")) {
					step.title = `${evaluate(expression)}`;
				} else {
					step.title = expression;
				}
			}
		});
	});
	Alpine.magic('wizard', (el, { Alpine: Alpine2 }) => {
		return getWizard(el, Alpine2);
	});
	let wizards = new WeakMap();
	let getWizard = (el, Alpine) => {
		const root = Alpine.closestRoot(el);
		if (!wizards.has(root)) {
			wizards.set(root, initWizardRoot(Alpine));
		}
		return wizards.get(root);
	};
	let initWizardRoot = (Alpine) => {
		return Alpine.reactive({
			steps: [],
			current_index: 0,
			progress() {
				let current = 0;
				let complete = 0;
				let total = 0;
				for (let index = 0; index < this.steps.length; index++) {
					const step = this.steps[index];
					if (!step.is_applicable) {
						continue;
					}
					total++;
					if (index <= this.current_index) {
						current++;
					}
					if (index <= this.current_index && step.is_complete) {
						complete++;
					}
				}
				return {
					total,
					complete,
					current,
					incomplete: total - complete,
					progress: `${Math.floor(current / total * 100)}%`,
					completion: `${Math.floor(complete / total * 100)}%`,
					percentage: Math.floor(complete / total * 100)
				};
			},
			current() {
				return this.steps[this.current_index] || { el: null, title: null };
			},
			previous() {
				return this.steps[this.previousIndex()] || { el: null, title: null };
			},
			next() {
				return this.steps[this.nextIndex()] || { el: null, title: null };
			},
			previousIndex() {
				return findNextIndex(this.steps, this.current_index, -1);
			},
			nextIndex() {
				return findNextIndex(this.steps, this.current_index, 1);
			},
			isStep(index) {
				if (!Array.isArray(index)) {
					index = [index]
				}
				return index.includes(this.current_index);
			},
			isFirst() {
				return this.previousIndex() === null;
			},
			isNotFirst() {
				return !this.isFirst();
			},
			isLast() {
				return this.nextIndex() === null;
			},
			isNotLast() {
				return !this.isLast();
			},
			isCompleted() {
				return this.current().is_complete && this.nextIndex() === null;
			},
			isUncompleted() {
				return !this.isCompleted();
			},
			goNext() {
				this.goTo(this.nextIndex());
			},
			canGoNext() {
				return this.current().is_complete && this.nextIndex() !== null;
			},
			cannotGoNext() {
				return !this.canGoNext();
			},
			goBack() {
				this.goTo(this.previousIndex());
			},
			canGoBack() {
				return this.previousIndex() !== null;
			},
			cannotGoBack() {
				return !this.canGoBack();
			},
			goTo(index) {
				if (index !== null && this.steps[index] !== void 0) {
					this.current_index = index;

					let action = this.steps[index].action || '';
					if (action) {
						this.steps[index].evaluate(action);
					}
				}
				return this.current();
			},
			getStep(el) {
				let step = this.steps.find((step2) => step2.el === el);
				if (!step) {
					el.setAttribute("x-show", "$wizard.current().el === $el");
					step = Alpine.reactive({
						el,
						title: "",
						is_applicable: true,
						is_complete: true,
						errors: {},
						cleanup: () => {
							this.steps = this.steps.filter((step2) => step2.el === el);
						}
					});
					this.steps.push(step);
				}
				return step;
			}
		});
	};
	let findNextIndex = (steps, current, direction = 1) => {
		for (let index = current + direction; index >= 0 && index < steps.length; index += direction) {
			if (steps[index] && steps[index].is_applicable) {
				return index;
			}
		}
		return null;
	};

	/**
	 * Ajax
	 *
	 * @since 1.0
	 */
	const BYTES_IN_MB = 1048576;
	Alpine.magic( 'ajax', el => (route, data, callback) => {
		let onloadEvent,
			formData = new FormData(),
			xhr      = new XMLHttpRequest(),
			buttons  = el.querySelectorAll("[type='submit']");

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

		function convertTo(number) {
			return Math.round(number / BYTES_IN_MB * 100) / 100;
		}

		return new Promise(resolve => {
			switch (el.tagName) {
				case 'BUTTON':
					el.classList.add('btn--load');

					onloadEvent = () => el.classList.remove('btn--load');
					break;
				case 'FORM':
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

			xhr.open(el.getAttribute('method')?.toUpperCase() ?? 'POST', grafema.apiurl + route);

			xhr.withCredentials = true;
			xhr.responseType    = 'json';

			// regular ajax sending & request with file uploading
			xhr.onloadstart = xhr.upload.onprogress = event => callback?.(onProgress(event, xhr));
			xhr.onloadend   = event => callback?.(onProgress(event, xhr));
			xhr.onload      = event => {
				document.dispatchEvent(
					new CustomEvent(route, {
						detail: { data: xhr.response?.data, event, el, resolve },
						bubbles: true,
						// Allows events to pass the shadow DOM barrier.
						composed: true,
						cancelable: true
					})
				);
				onloadEvent && onloadEvent();
			};

			xhr.send(formData);
		});
	});

	/**
	 * Notifications system
	 *
	 * @since 1.0
	 */
	Alpine.magic( 'notice', ( el, { Alpine } ) => {
		return {
			items: [],
			notify( message ) {
				this.items.push( message )
			}
		}
	} )
	Alpine.store( 'notice', {
		items: {},
		duration: 4000,
		setDuration( duration ) {
			this.duration = parseInt(duration) || 4000;
		},
		info( message ) {
			this.notify( message, 'info' );
		},
		success( message ) {
			this.notify( message, 'success' );
		},
		warning( message ) {
			this.notify( message, 'warning' );
		},
		error( message ) {
			this.notify( message, 'error' );
		},
		loading( message ) {
			this.notify( message, 'loading' );
		},
		close( id ) {
			if ( typeof this.items[id] !== 'undefined' ) {
				this.items[id].selectors.push( 'hide' );
				//var height = ( e.target.offsetHeight + parseInt( window.getComputedStyle( e.target ).getPropertyValue( 'margin-top' ) ) ) * -1;

				//e.target.style.setProperty( '--top', height + 'px' );
				//e.target.style.setProperty( '--ms',  notice.time + 'px' );
				setTimeout( () => {
					delete this.items[id];
				}, 1000 )
			}
		},
		notify( message, type ) {
			if ( message ) {
				let animationName = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5),
					timestamp     = Date.now();
				this.items[timestamp] = {
					anim: `url("data:image/svg+xml;charset=UTF-8,%3csvg width='24' height='24' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cstyle%3ecircle %7b animation: ${this.duration}ms ${animationName} linear;%7d%40keyframes ${animationName} %7bfrom%7bstroke-dasharray:0 70%7dto%7bstroke-dasharray:70 0%7d%7d%3c/style%3e%3ccircle cx='12' cy='12' r='11' stroke='%23000' stroke-opacity='.2' stroke-width='2'/%3e%3c/svg%3e")`,
					message: message,
					closable: true,
					selectors: [ type || 'info' ],
					classes() {
						return this.selectors.map( x => 'notice__item--' + x ).join(' ')
					},
				}
				setTimeout( () => this.close(timestamp), this.duration );
			}
		},
	});

	/**
	 * Password
	 *
	 * @since 1.0
	 */
	Alpine.magic('password', () => {
		return {
			min: {
				lowercase: 2,
				uppercase: 2,
				special: 2,
				digit: 2,
				length: 12
			},
			valid: {
				lowercase: false,
				uppercase: false,
				special: false,
				digit: false,
				length: false
			},
			charsets: {
				lowercase: 'abcdefghijklmnopqrstuvwxyz',
				uppercase: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
				special: '!@#$%^&*(){|}~',
				digit: '0123456789'
			},
			switch(value) {
				return !(!!value);
			},
			check(value) {
				let matchCount = 0;
				let totalCount = 0;

				for (const charset in this.charsets) {
					let requiredCount = this.min[charset],
						charsetRegex  = new RegExp(`[${this.charsets[charset]}]`, 'g'),
						charsetCount  = (value.match(charsetRegex) || []).length;
					matchCount += Math.min(charsetCount, requiredCount);
					totalCount += requiredCount;

					this.valid[charset] = charsetCount >= requiredCount;
				}

				if (value.length >= this.min.length) {
					matchCount += 1;
					totalCount += 1;
					this.valid.length = value.length >= this.min.length;
				}

				return Object.assign(
					{
						progress: totalCount === 0 ? totalCount : (matchCount / totalCount) * 100,
					},
					this.valid
				)
			},
			generate() {
				let password = '';
				let types = Object.keys(this.charsets);

				types.forEach(type => {
					let count   = Math.max(this.min[type], 0),
						charset = this.charsets[type];

					for (let i = 0; i < count; i++) {
						let randomIndex = Math.floor(Math.random() * charset.length);
						password += charset[randomIndex];
					}
				});

				while (password.length < this.min.length) {
					let randomIndex = Math.floor(Math.random() * types.length),
						charType    = types[randomIndex],
						charset     = this.charsets[charType],
						randomCharIndex = Math.floor(Math.random() * charset.length);
					password += charset[randomCharIndex];
				}
				this.check(password);

				return this.shuffle(password);
			},
			shuffle(password) {
				let array = password.split('');
				let currentIndex = array.length;
				let temporaryValue, randomIndex;

				while (currentIndex !== 0) {
					randomIndex = Math.floor(Math.random() * currentIndex);
					currentIndex -= 1;

					temporaryValue = array[currentIndex];
					array[currentIndex] = array[randomIndex];
					array[randomIndex] = temporaryValue;
				}

				return array.join('');
			},
		}
	});

	/**
	 * Avatar
	 *
	 * @since 1.0
	 */
	Alpine.data('avatar', () => ({
		content: '',
		image: '',
		add(event, callback) {
			let file = event.target.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = e => {
					this.image = e.target.result;
				};
				reader.readAsDataURL(file);
			}

			if (callback) {
				callback();
			}
		},
		remove() {
			let root  = this.$el.closest('[x-data]'),
				input = root && root.querySelector('input[type="file"]');
			if (input) {
				input.value = '';
			}
			this.image = '';
		},
		getInitials( string, letters = 2 ) {
			const wordArray = string.split(' ').slice( 0, letters );
			if ( wordArray.length >= 2 ) {
				return wordArray.reduce( ( accumulator, currentValue ) => `${accumulator}${currentValue[0].charAt(0)}`.toUpperCase(), '' );
			}
			return wordArray[0].charAt(0).toUpperCase();
		},
	}));

	/**
	 * Datepicker.
	 *
	 * Based on https://wwilsman.github.io/Datepicker.js/#methods
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'datepicker', ( el, { value, expression, modifiers }, { evaluateLater, effect } ) => {
		el.setAttribute('readonly', true);

		let evaluate = evaluateLater(expression || '{}');
		effect(() => {
			evaluate( content => {
				let format = grafema?.dateFormat,
					lang   = (grafema?.lang || navigator.language || navigator.userLanguage || 'en-US');

				let translateWeekdays = length => {
					return Array.from({ length: 7 }, (_, i) => {
						return new Intl.DateTimeFormat(lang, { weekday: length }).format(new Date(2024, 0, i + 1));
					});
				}

				let translateMonths = length => {
					return Array.from({ length: 12 }, (_, i) => {
						let month = new Intl.DateTimeFormat(lang, { month: length }).format(new Date(2024, i, 1));
							month = month.endsWith('.') ? month.slice(0, -1) : month;

						return month.charAt(0).toUpperCase() + month.slice(1);
					});
				}

				let formatter = (date, format) => {
					let s           = date.toString(),
						f           = date.getTime(),
						fullYear    = date.getFullYear(),
						monthNumber = date.getMonth(),
						day         = date.getDate(),
						hours       = date.getHours(),
						minutes     = date.getMinutes(),
						seconds     = date.getSeconds();

					return format.replace( /a|A|d|D|F|g|G|h|H|i|I|j|l|L|m|M|n|s|S|t|T|U|w|y|Y|z|Z/g, format => {
						switch ( format ) {
							case 'a' : return hours > 11 ? 'pm' : 'am';
							case 'A' : return hours > 11 ? 'PM' : 'AM';
							case 'd' : return ( '0' + day ).slice(-2);
							case 'D' : return translateWeekdays('short')[ date.getDay() ];
							case 'F' : return translateMonths('long')[ monthNumber ];
							case 'g' : return ( s = ( hours || 12 ) ) > 12 ? s - 12 : s;
							case 'G' : return hours;
							case 'h' : return ( '0' + ( ( s = hours || 12 ) > 12 ? s - 12 : s ) ).slice(-2);
							case 'H' : return ( '0' + hours ).slice(-2);
							case 'i' : return ( '0' + minutes ).slice(-2);
							case 'I' : return (() => {
								let a = new Date(fullYear, 0),
									c = Date.UTC(fullYear, 0),
									b = new Date(fullYear, 6),
									d = Date.UTC(fullYear, 6);
								return ((a - c) !== (b - d)) ? 1 : 0;
							})();
							case 'j' : return day;
							case 'l' : return translateWeekdays('long')[ date.getDay() ];
							case 'L' : return ( s = fullYear ) % 4 === 0 && ( s % 100 !== 0 || s % 400 === 0 ) ? 1 : 0;
							case 'm' : return ( '0' + ( monthNumber + 1 ) ).slice(-2);
							case 'M' : return translateMonths('short')[ monthNumber ];
							case 'n' : return monthNumber + 1;
							case 's' : return ( '0' + seconds ).slice(-2);
							case 'S' : return [ 'th', 'st', 'nd', 'rd' ][ ( s = day ) < 4 ? s : 0 ];
							case 't' : return (new Date(fullYear, monthNumber, 0)).getDate();
							case 'T' : return 'UTC';
							case 'U' : return ( '' + f ).slice( 0, -3 );
							case 'w' : return date.getDay();
							case 'y' : return ( '' + fullYear ).slice(-2);
							case 'Y' : return fullYear;
							case 'z' : return Math.ceil((date - new Date(fullYear, 0, 1)) / 86400000);
							default : return -date.getTimezoneOffset() * 60;
						}
					});
				}

				try {
					new Datepicker( el, {
						...{
							inline: false,
							multiple: false,
							ranged: true,
							time: false,
							lang: lang.substr( 0, 2 ).toLowerCase(),
							months: 1,
							openOn: 'today',
							timeAmPm: false,
							within: false,
							without: false,
							yearRange: 5,
							weekStart: grafema?.weekStart,
							separator: ' — ',
							serialize: value => {
								let date = new Date(value);
								if (format) {
									return formatter(date, format);
								}
								return date.toLocaleDateString(lang);
							},
						},
						...content
					});
				} catch (e) {
					console.error( 'Errors: check the library connection, "Datepicker" is not defined. Details: https://github.com/wwilsman/Datepicker.js' );
				}
			});
		});
	});

	/**
	 * Validation by field `type`, `regexp` or `mask`.
	 * This function are use `vanillaTextMask` library.
	 *
	 * @since 1.0
	 */
	Alpine.data( 'mask', () => ( {
		run: mask => {
			let elem = this.$el;
			if( typeof mask === 'undefined' ) {
				let type = elem.getAttribute( 'type' );
				if( type ) {
					let exp = '';
					// validation based on the field type
					switch( type ) {
						case 'tel':
							exp = /[^ \-()+\d]/g;
							break;
						case 'number':
							exp = /[^.-\d]/g;
							break;
						case 'color':
							exp = /[^ a-zA-Z(),\d]/g;
							break;
						// TODO: validate domains and subdomains
						// @see https://stackoverflow.com/questions/26093545/how-to-validate-domain-name-using-regex
						case 'domain':
							break;
					}

					// removing forbidden characters
					if ( exp ) {
						elem.value = elem.value.replace( exp, '' );
					}
				}
			} else if( mask === Object( mask ) ) {
				elem.value = elem.value.replace( mask, '' );
			}
			/**
			 * Validation by mask.
			 *
			 * @see discussion //javascript.ru/forum/dom-window/82008-kak-preobrazovat-stroku-v-massiv.html
			 */
			else {
				try {
					function limit( position, symbol, max ) {
						let pos = position;

						max = max.toString();
						if( mask.charAt( --pos ) === symbol ) {
							if( elem.value.charAt( pos ) === max.charAt(0) ) {
								return new RegExp( '[0-' + max.charAt(1) + ']' );
							} else {
								return /\d/;
							}
						}
						return new RegExp( '[0-' + max.charAt(0) + ']' );
					}

					let maskArr  = mask.match( /(\{[^}]+?\})|(.)/g ),
						//var maskArr  = mask.match( /(\{[^\s]+\})|(\+)|([()])|(.)|(\s+)/g ),
						position = -1;
					maskArr = maskArr.map( function( symbol ) {
						++position;
						switch( symbol ) {
							case 'i':
								return limit( position, symbol, 59 );
							case 'H':
								return limit( position, symbol, 23 );
							case 'D':
								return limit( position, symbol, 31 );
							case 'M':
								return limit( position, symbol, 12 );
							case 'Y': case '0':
								return /\d/;
							default:
								if( /\{[^}]+?\}/.test( symbol ) ) {
									return new RegExp( symbol.slice( 2, -2 ) );
								}
								return symbol;
						}
					} );

					//console.log( maskArr );
					vanillaTextMask.maskInput( {
						inputElement: elem,
						guide: false,
						mask: maskArr,
					} );
				} catch( e ) {
					console.error( 'Errors: check the library connection, "vanillaTextMask" is not defined. Details: https:://github.com/text-mask/text-mask' );
				}
			}
		}
	} ) );

	/**
	 * An accessible dialog window: modal, alert, dialog, popup
	 *
	 * @since 1.0
	 */
	Alpine.magic( 'modal', el => {
		return {
			open: ( id, animation ) => {
				setTimeout( () => {
					let modal = document.getElementById( id );
					if( modal ) {
						modal.classList.add( 'is-active', animation || 'fade' );
					}
					document.body.style.overflow = 'hidden';
				}, 25 );
			},
			close: animation => {
				let modal = el.closest( '.modal' );
				if( modal !== null && modal.classList.contains( 'is-active' ) ) {
					modal.classList.remove( 'is-active', animation || 'fade' );
					document.body.style.overflow = '';
				}
			}
		}
	});

	/**
	 * Progress bar
	 *
	 * @since 1.0
	 */
	Alpine.directive( 'progress', (el, {modifiers}) => {
		new IntersectionObserver((entries, observer) => {
			entries.forEach(entry => {
				if(entry.isIntersecting) {
					let [value = 100, from = 0, to = 100, duration = '400ms'] = modifiers;

					let start = parseInt(from) / parseInt(value) * 100;
					let end   = parseInt(to) / parseInt(value) * 100;

					if (start > end) {
						[ end, start ] = [ start, end ];
					}

					el.style.setProperty('--grafema-progress', ( start < 0 ? 0 : start ) + '%');
					setTimeout(() => {
						el.style.setProperty('--grafema-transition', ' width ' + duration);
						el.style.setProperty('--grafema-progress', ( end > 100 ? 100 : end ) + '%');
					}, 500)

					// apply progress just once
					observer.unobserve(el);
				}
			});
		}).observe(el);
	});

	/**
	 * Advanced select dropdown based on SlimSelect library.
	 *
	 * @see   https://github.com/brianvoe/slim-select
	 * @since 1.0
	 */
	Alpine.directive('select', (el, { expression }) => {
		const settings = {
			showSearch: false,
			hideSelected: false,
			closeOnSelect: true,
		};

		if (el.hasAttribute('multiple')) {
			settings.hideSelected = true;
			settings.closeOnSelect = false;
		}

		const custom = JSON.parse(expression || '{}');
		if (typeof custom === 'object') {
			Object.assign(settings, custom);
		}

		try {
			new SlimSelect({
				settings,
				select: el,
				data: Array.from(el.options).reduce((acc, option) => {
					let image       = option.getAttribute('data-image'),
						icon        = option.getAttribute('data-icon'),
						description = option.getAttribute('data-description') || '';

					let images       = image ? `<img src="${image}" alt />` : '',
						icons        = icon ? `<i class="${icon}"></i>` : '',
						descriptions = description ? `<span class="ss-description">${description}</span>` : '',
						html         = `${images}${icons}<span class="ss-text">${option.text}${descriptions}</span>`;

					let optionData = {
						text: option.text,
						value: option.value,
						html: html,
						selected: option.selected,
						display: true,
						disabled: false,
						mandatory: false,
						placeholder: false,
						class: '',
						style: '',
						data: {}
					};

					if (option.parentElement.tagName === 'OPTGROUP') {
						const optgroupLabel = option.parentElement.getAttribute('label');
						const optgroup = acc.find(item => item.label === optgroupLabel);
						if (optgroup) {
							optgroup.options.push(optionData);
						} else {
							acc.push({
								label: optgroupLabel,
								options: [optionData]
							});
						}
					} else {
						acc.push(optionData);
					}
					return acc;
				}, []),
			});
		} catch {
			console.error('The SlimSelect library is not connected');
		}
	});

	/**
	 * Advanced drag & drop based on slimselect library.
	 *
	 * @see   https://github.com/bevacqua/dragula
	 * @since 1.0
	 */
	Alpine.directive(
		'media',
		(el, {expression}) => {
			console.log(el)
			//dragula([el]);
		}
	);

	/**
	 * Custom fields builder.
	 *
	 * @since 1.0
	 */
	Alpine.data(
		'builder',
		() => ({
			default: {
				location: 'post',
				operator: '===',
				value: 'editor',
			},
			groups: [
				{
					rules: [
						{
							location: 'post_status',
							operator: '!=',
							value: 'contributor',
						},
					]
				},
			],
			addGroup() {
				let pattern = JSON.parse(JSON.stringify(this.default));
				this.groups.push({
					rules: [ pattern ]
				});
			},
			removeGroup(index) {
				this.groups.splice(index, 1);
			},
			addRule(key) {
				let pattern = JSON.parse(JSON.stringify(this.default));
				this.groups[key].rules.push(pattern);
			},
			removeRule(key,index) {
				this.groups[key].rules.splice(index, 1);
			},
			submit() {
				let groups = JSON.parse(JSON.stringify(this.groups));
				console.log(groups);
			},
		})
	);

	/**
	 * Sortable elements
	 *
	 * @since 1.0
	 */
	Alpine.data( 'sortable', () => ( {
		init() {
			let nestedSortables = [].slice.call( document.querySelectorAll( '.sortable' ) );
			for( let i = 0; i < nestedSortables.length; i++ ) {
				new Sortable( nestedSortables[i], {
					multiDrag: true,
					selectedClass: 'is-active',
					fallbackTolerance: 3,
					group: 'nested',
					easing: 'cubic-bezier(1, 0, 0, 1)',
					animation: 100,
					fallbackOnBody: true,
					swapThreshold: 0.5,
					dataIdAttr: 'data-id',
				} );
			}
		}
	} ) );

	/**
	 * Tab
	 *
	 * @since 1.0
	 */
	Alpine.data( 'tab', (id) => ({
		tab: id,
		tabButton(id) {
			return {
				['@click']() {
					this.tab = id;

					let formData = new FormData();
					formData.append('tab', id);

					updateUrlParams(formData);
				},
				[':class']() {
					return this.tab === id ? 'active' : '';
				},
			};
		},
		tabContent(id) {
			return {
				['x-show']() {
					return this.tab === id;
				},
			};
		}
	}));

	/**
	 * Table checkbox
	 *
	 * @since 1.0
	 */
	Alpine.data( 'table', () => ( {
		init() {
			document.addEventListener( 'keydown', e => {
				let key = window.event ? event : e;
				if ( !!key.shiftKey ) {
					this.selection.shift = true;
				}
			});
			document.addEventListener( 'keyup', e => {
				let key = window.event ? event : e;
				if ( !key.shiftKey ) {
					this.selection.shift = false;
				}
			});
		},
		selection: {
			box: {},
			shift: false,
			addMore: true,
		},
		bulk: false,
		trigger: {
			['@change']( e ) {
				let checked = 0;
				let inputs  = document.querySelectorAll( 'input[name="item[]"]' );
				if (inputs.length) {
					inputs.forEach(input => (input.checked = e.target.checked, input.checked && checked++));
				}
				this.bulk = checked > 0;
			},
		},
		reset: {
			['@click']( e ) {
				let inputs = document.querySelectorAll( 'input[name="item[]"], input[x-bind="trigger"]' );
				if (inputs.length) {
					inputs.forEach(input => input.checked = false);
				}
				this.bulk = false;
			},
		},
		switcher: {
			['@click']( e ) {
				let checkboxes = document.querySelectorAll( 'input[name="item[]"]' );
				let nodeList   = Array.prototype.slice.call( document.getElementsByClassName( 'cb' ) );

				this.selection.addMore = !!e.target.checked;
				if ( this.selection.shift ) {
					this.selection.box[1] = nodeList.indexOf( e.target.parentNode );

					let i = this.selection.box[0],
						x = this.selection.box[1];

					if ( i > x ) {
						for ( ; x < i; x++ ) {
							checkboxes[x].checked = this.selection.addMore;
						}
					}
					if ( i < x ) {
						for ( ; i < x; i++ ) {
							checkboxes[i].checked = this.selection.addMore;
						}
					}
					this.selection.box[0] = undefined;
					this.selection.box[1] = undefined;
				} else {
					this.selection.box[0] = nodeList.indexOf( e.target.parentNode );
				}

				let checked = document.querySelectorAll('input[name="item[]"]:checked');
				this.bulk   = checked.length > 0;
			},
		}
	} ) )

	/**
	 * Password
	 *
	 * @since 1.0
	 */
	Alpine.magic('password', () => {
		return {
			min: {
				lowercase: 2,
				uppercase: 2,
				special: 2,
				digit: 2,
				length: 12
			},
			valid: {
				lowercase: false,
				uppercase: false,
				special: false,
				digit: false,
				length: false
			},
			charsets: {
				lowercase: 'abcdefghijklmnopqrstuvwxyz',
				uppercase: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
				special: '!@#$%^&*(){|}~',
				digit: '0123456789'
			},
			switch(value) {
				return !(!!value);
			},
			check(value) {
				let matchCount = 0;
				let totalCount = 0;

				for (const charset in this.charsets) {
					let requiredCount = this.min[charset],
						charsetRegex  = new RegExp(`[${this.charsets[charset]}]`, 'g'),
						charsetCount  = (value.match(charsetRegex) || []).length;
					matchCount += Math.min(charsetCount, requiredCount);
					totalCount += requiredCount;

					this.valid[charset] = charsetCount >= requiredCount;
				}

				if (value.length >= this.min.length) {
					matchCount += 1;
					totalCount += 1;
					this.valid.length = value.length >= this.min.length;
				}

				return Object.assign(
					{
						progress: totalCount === 0 ? totalCount : (matchCount / totalCount) * 100,
					},
					this.valid
				)
			},
			generate() {
				let password = '';
				let types = Object.keys(this.charsets);

				types.forEach(type => {
					let count   = Math.max(this.min[type], 0),
						charset = this.charsets[type];

					for (let i = 0; i < count; i++) {
						let randomIndex = Math.floor(Math.random() * charset.length);
						password += charset[randomIndex];
					}
				});

				while (password.length < this.min.length) {
					let randomIndex = Math.floor(Math.random() * types.length),
						charType    = types[randomIndex],
						charset     = this.charsets[charType],
						randomCharIndex = Math.floor(Math.random() * charset.length);
					password += charset[randomCharIndex];
				}
				this.check(password);

				return this.shuffle(password);
			},
			shuffle(password) {
				let array = password.split('');
				let currentIndex = array.length;
				let temporaryValue, randomIndex;

				while (currentIndex !== 0) {
					randomIndex = Math.floor(Math.random() * currentIndex);
					currentIndex -= 1;

					temporaryValue = array[currentIndex];
					array[currentIndex] = array[randomIndex];
					array[randomIndex] = temporaryValue;
				}

				return array.join('');
			},
		}
	});

	/**
	 * Counting time in four different units: seconds, minutes, hours and days.
	 *
	 * @since 1.0
	 */
	Alpine.data( 'timer', ( endDate, startDate ) => ( {
		end: endDate, // format: '2021-31-12T14:58:31+00:00'
		day:  '00',
		hour: '00',
		min:  '00',
		sec:  '00',
		init() {
			let start = startDate || new Date().valueOf(),
				end   = new Date( this.end ).valueOf();

			// if the start date is earlier than the end date
			if( start < end ) {
				// number of seconds between two dates
				let diff = Math.round( ( end - start ) / 1000 );

				let t = this;
				setInterval( function() {
					t.day  = ( '0' + parseInt( diff / ( 60 * 60 * 24 ), 10 ) ).slice(-2);
					t.hour = ( '0' + parseInt( ( diff / ( 60 * 60 ) ) % 24, 10 ) ).slice(-2);
					t.min  = ( '0' + parseInt( ( diff / 60 ) % 60, 10 ) ).slice(-2);
					t.sec  = ( '0' + parseInt( diff % 60, 10 ) ).slice(-2);

					if( --diff < 0 ) {
						t.days = t.hour = t.min = t.sec = '00';
					}
				}, 1000 );
			}
		},
	} ) );

} );
