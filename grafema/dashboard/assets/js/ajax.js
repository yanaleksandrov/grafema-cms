var __webpack_exports__ = {};

function parseResponse(method, fragment, selectors = 'body', delay) {
    [ ...document.querySelectorAll(selectors) ].forEach((target => {
        setTimeout((() => {
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
                target.value = fragment || '';
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
                if (!name) {
                    return null;
                }
                target.setAttribute(name, value || '');
                break;

              case 'notify':
                if (fragment) {
                    Alpine.store('notice').setDuration(custom.duration ?? 4e3);
                    Alpine.store('notice').notify(fragment, custom.type ?? 'info');
                }
                break;

              default:
                target[method](fragment || '');
            }
        }), delay || 0);
    }));
}

document.addEventListener('system/test', (({detail}) => {
    const {el, data, resolve} = detail;
    resolve(data);
    let delay = 250;
    Object.entries(data).forEach((([key, value]) => {
        delay = delay + 250;
        setTimeout((() => {}), delay);
    }));
}));

document.addEventListener('system/install', (({detail: {data, resolve}}) => resolve(data)));

document.addEventListener('user/sign-in', (({detail: {data}}) => {
    if (data.logged && data.redirect) {
        window.location.href = data.redirect;
    }
}));

document.addEventListener('user/reset-password', (({detail: {data}}) => {
    if (data.logged && data.redirect) {
        window.location.href = data.redirect;
    }
}));

document.addEventListener('files/upload', (({detail}) => {
    const {el, data, resolve} = detail;
    if (el) {
        el.value = '';
    }
    resolve(data);
}));

document.addEventListener('posts/import', (({detail}) => {
    const {data, resolve} = detail;
    resolve(data);
}));

document.addEventListener('media/get', (({detail}) => {
    const {data, resolve} = detail;
    resolve(data);
}));

document.addEventListener('extensions/get', (({detail}) => {
    const {data, resolve} = detail;
    resolve(data);
}));