function parseResponse(method, fragment, selectors = 'body', delay) {
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
				case 'notify':
					if (fragment) {
						Alpine.store('notice').setDuration(custom.duration ?? 4000);
						Alpine.store('notice').notify(fragment, custom.type ?? 'info');
					}
					break;
				default:
					// use 'prepend, append, replaceWith, removeAttribute or scrollIntoView' methods
					target[method](fragment || '');
			}
		}, delay || 0);
	});
}

// TODO: each element should be updated separately in 200ms increments so that there is visual progress
document.addEventListener('system/test', ({detail}) => {
	const {el, data, resolve} = detail;
	resolve(data);
	// console.log(datas);
	// console.log(Alpine);
	// let datas = Alpine.closestDataStack(el);
	// let reactiveData = Alpine.reactive(datas);
	// console.log(datas.get(datas, 'db'))
	// Alpine.addScopeToNode(el, {...reactiveData, ...{approved: data}});
	// console.log({...reactiveData, ...{approved: data}})
	//Alpine.bind(root, {approved: data});

	let delay = 250;
	Object.entries(data).forEach(([key, value]) => {
		delay = delay + 250;
		setTimeout(() => {
			//Alpine.addScopeToNode(el, {...datas, ...{approved: {[key]: value}}});
			//resolve({[key]: value});
			//console.log({[key]: value})
		}, delay);
	});
});

document.addEventListener('system/install', ({detail: { data, resolve }}) => resolve(data));

document.addEventListener('user/sign-in', ({ detail: { data } }) => {
	if (data.logged && data.redirect) {
		window.location.href = data.redirect;
	}
});