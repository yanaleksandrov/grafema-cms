document.addEventListener( 'alpine:init', () => {

	/**
	 * An accessible dialog window: modal, alert, confirm & prompt.
	 *
	 * @since 1.0
	 */
	const url    = new URL(window.location.href);
	const params = new URLSearchParams(url.search);
	Alpine.magic( 'dialog', el => {
		const dialogHandler = (templateID, data = {}, dialogID = 'grafema-dialog', isModal) => {
			setTimeout( () => {
				let template = document.querySelector(`#${templateID}`),
					dialog   = document.querySelector(`#${dialogID}`);
				if (dialog && template) {
					data.content = template.innerHTML;

					Alpine.store('dialog', data);
					if (isModal) {
						dialog.showModal();
					} else {
						dialog.show();
					}

					document.body.style.overflow = 'hidden';

					// update current URL
					params.set('dialog', templateID);
					url.search = params.toString();
					window.history.pushState({}, '', url.toString());

					// delete param for onClose
					dialog.addEventListener('close', ()=> {
						params.delete('dialog');
						url.search = params.toString();
						window.history.replaceState({}, '', url.toString());
					});
				}
			}, 25 );
		}

		return {
			init: async callback => {
				let templateID = params.get('dialog');
				if (el?.id === templateID && templateID && callback) {
					let data = await callback();
					if (data) {
						dialogHandler(templateID, data, 'grafema-dialog', true);
					}
				}
			},
			open: (templateID, data = {}, dialogID) => dialogHandler(templateID, data, dialogID, true),
			show: (templateID, data = {}, dialogID) => dialogHandler(templateID, data, dialogID, false),
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