document.addEventListener('system/test', ({detail}) => {
	const {el, event, data} = detail;
	console.log(el);
	console.log(event);
	console.log(data);
});