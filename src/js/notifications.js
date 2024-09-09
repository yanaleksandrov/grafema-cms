document.addEventListener( 'alpine:init', () => {

	/**
	 * Notifications system
	 *
	 * @since 1.0
	 */
	Alpine.store( 'notifications', []);
	Alpine.magic( 'notification', () => {
		return {
			info: function(message) {
				this.add(message, 'info');
			},
			error: function(message) {
				this.add(message, 'error');
			},
			success: function(message) {
				this.add(message, 'success');
			},
			warning: function(message) {
				this.add(message, 'warning');
			},
			loading: function(message) {
				this.add(message, 'loading');
			},
			close: id => {
				let items = Alpine.store( 'notifications'),
					index = items.findIndex(item => item.id === id);

				if (index !== -1) {
					items[index]['class'] += ' removed';
					Alpine.store('notifications', items);

					setTimeout(() => {
						items = Alpine.store( 'notifications');
						index = items.findIndex(item => item.id === id);

						if (index !== -1) {
							items.splice(index, 1);
							Alpine.store('notifications', items);
						}
					}, 75000);
				}
			},
			add: function(message, type = 'info', duration = 'auto') {
				if (message) {
					let animation = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5),
						timestamp = Date.now();

					let items = Alpine.store('notifications');

					if (duration === 'auto') {
						duration = Math.max(message.length * 70, 1000);
					}

					duration = 400000;
					items.push({
						id: timestamp,
						animation: `url("data:image/svg+xml;charset=UTF-8,%3csvg width='24' height='24' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3cstyle%3ecircle %7b animation: ${duration}ms ${animation} linear;%7d%40keyframes ${animation} %7bfrom%7bstroke-dasharray:0 70%7dto%7bstroke-dasharray:70 0%7d%7d%3c/style%3e%3ccircle cx='12' cy='12' r='11' stroke='%23000' stroke-opacity='.5' stroke-width='1'/%3e%3c/svg%3e")`,
						message: message + '',
						duration: +duration,
						class: type + '',
						type: type + '',
					});

					Alpine.store('notifications', items);

					if (duration) {
						setTimeout(() => this.close(timestamp), duration);
					}
				}
			},
		}
	});
});