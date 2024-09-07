document.addEventListener( 'alpine:init', () => {

	const searchParamsHandler = (param, value, isRemove) => {
		const url    = new URL(window.location.href);
		const params = new URLSearchParams(url.search);
		if (isRemove) {
			params.delete(param);
		} else {
			params.set(param, value);
		}
		url.search = params.toString();
		window.history.replaceState({}, '', url.toString());
	}

	const dialogHandler = (templateID, data = {}, dialogID = 'grafema-dialog', isModal) => {
		setTimeout( () => {
			let template = document.querySelector(`#${templateID}`),
				dialog   = document.querySelector(`#${dialogID}`);
			if (dialog && template) {
				let content = dialog.querySelector('[data-content]');
				if (content) {
					content.innerHTML = template.innerHTML;
				}

				Alpine.store('dialog', data);
				if (isModal) {
					dialog.showModal();
				} else {
					dialog.show();
				}
				document.body.style.overflow = 'hidden';

				// update current URL
				searchParamsHandler('dialog', templateID, false);

				// Delete parameter for onClose
				const closeHandler = () => {
					searchParamsHandler('dialog', null, true);
					dialog.removeEventListener('close', closeHandler);
				};

				dialog.addEventListener('close', closeHandler);
			}
		}, 25 );
	}

	/**
	 * An accessible dialog window: modal, alert, confirm & prompt.
	 *
	 * @since 1.0
	 */
	Alpine.magic( 'dialog', el => {
		return {
			init: async callback => {
				const url    = new URL(window.location.href);
				const params = new URLSearchParams(url.search);

				const templateID = params.get('dialog');
				if (el?.id === templateID && templateID && callback) {
					let data = await callback();

					if (data) {
						dialogHandler(templateID, data, 'grafema-dialog', true);
					}
				}
			},
			open: (templateID, data = {}, dialogID) => {
				dialogHandler(templateID, data, dialogID, true);
			},
			show: (templateID, data = {}, dialogID) => {
				dialogHandler(templateID, data, dialogID, false);
			},
			close: (dialogID = 'grafema-dialog') => {
				let dialog = document.querySelector(`#${dialogID}`) || el.closest('dialog');
				if (dialog) {
					dialog.close();

					document.body.style.overflow = '';
				}
			}
		}
	});
});