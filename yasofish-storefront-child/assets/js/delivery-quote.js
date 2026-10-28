(function () {
	'use strict';

	function initQuote(form) {
	if (!form || form.dataset.yfQuoteBound) return;
	form.dataset.yfQuoteBound = '1';

	const method = form.querySelector('[name="method"]');
	const address = form.querySelector('.yf-delivery-address');
	const street = form.querySelector('[name="street"]');
	const region = form.querySelector('[name="region"]');
	const commune = form.querySelector('[name="commune"]');
	const city = form.querySelector('[name="city"]');
	const pickupCopy = { 'pickup-iqq': 'Retiro coordinado: confirmar punto y horario con YASOFISH' };

	function syncDeliveryFields() {
		const isPickup = method.value.indexOf('pickup-') === 0;
		address.hidden = isPickup;
		street.required = !isPickup;
		street.disabled = isPickup;
		form.querySelector('[name="unit"]').disabled = isPickup;

	}

	method.addEventListener('change', syncDeliveryFields);
	syncDeliveryFields();

	form.addEventListener('submit', function (event) {
		event.preventDefault();
		if (!form.reportValidity()) return;

		const data = new FormData(form);
		const deliveryMethod = data.get('method');
		const lines = JSON.parse(form.dataset.items || '[]');
		const message = [
			'Hola YASOFISH, quisiera cotizar este carro antes de pagar:',
			...lines.map(function (line) { return '• ' + line; }),
			'',
			'Nombre de quien recibe: ' + data.get('name'),
			'Entrega: ' + (pickupCopy[deliveryMethod] || (deliveryMethod === 'delivery-local' ? 'Delivery Iquique / Alto Hospicio' : 'Envío a otra región')),
			'Región: ' + data.get('region'),
			'Comuna: ' + data.get('commune'),
			'Ciudad/localidad: ' + data.get('city'),
			...(pickupCopy[deliveryMethod] ? [] : [
				'Dirección: ' + data.get('street'),
				data.get('unit') ? 'Casa/departamento: ' + data.get('unit') : '',
				data.get('notes') ? 'Referencias de entrega: ' + data.get('notes') : ''
			].filter(Boolean)),
			'',
			'Por favor confirma stock, disponibilidad de la vía, costo del despacho, plazo y total final.'
		].filter(Boolean).join('\n');
		const url = 'https://wa.me/' + form.dataset.phone + '?text=' + encodeURIComponent(message);
		window.location.assign(url);
	});
	}
	function initAll() { document.querySelectorAll('.yf-delivery-quote').forEach(initQuote); }
	initAll();
	if (window.jQuery) { window.jQuery(document.body).on('updated_wc_div updated_cart_totals', initAll); }
}());
