# Procedimiento de cotización, transferencia y despacho YASOFISH

## Estado actual

Este documento deja definido el flujo inicial para configurar cuando exista WordPress + WooCommerce en el hosting definitivo. La maqueta local no crea pedidos, no envía mensajes y no recibe pagos. La transferencia tampoco está activada en un WooCommerce real.

## Decisión para la primera etapa

Como los costos y plazos cambian según destino y vía de despacho, primero se cotiza por WhatsApp y se informa el total final. El cliente acepta ese total antes de que se genere una orden pagable. Después se crea el pedido en WooCommerce con el costo de despacho acordado y se envía su enlace de pago.

No se debe crear una orden con despacho en $0, cobrar un monto parcial ni mostrar datos bancarios antes de informar y aceptar el costo total.

## Recorrido del cliente

1. Agrega productos y cantidades al carro.
2. En el formulario de cotización indica cómo recibirás el pedido, nombre de quien lo recibe, región, comuna y ciudad/localidad. Para delivery o envío a regiones, informa calle y número; departamento/casa y referencias son opcionales. Al continuar se abre WhatsApp con el resumen para revisar y enviar.
3. YASOFISH confirma stock, modalidad disponible y el costo y plazo concretos según destino. Se informa la preparación de 2 a 4 días y el rango de transporte estimado para esa vía; el cliente recibe y acepta el total final antes de pagar.
4. El cliente acepta la cotización.
5. YASOFISH registra el pedido en WooCommerce con productos, cantidades, dirección/retiro, método y cargo de despacho acordados; luego envía el enlace de pago de ese pedido.
6. El cliente paga por transferencia directa y WooCommerce muestra las instrucciones y datos bancarios configurados para esa orden.
7. El pedido queda en espera de confirmación. El vendedor comprueba que el dinero esté efectivamente abonado en la cuenta BCI; una captura o comprobante enviado por el cliente no acredita por sí solo el pago.
8. Tras verificar el abono, el vendedor cambia el estado a “Procesando” y prepara el pedido. Cuando se entrega o despacha, registra la gestión y lo marca “Completado”.

## Preparación en WooCommerce

### Despacho

- Definir zonas, comunas cubiertas, transportistas o vías locales, costos, horarios/días de operación y plazos que se ofrecerán.
- Mantener la información entregada por el titular: delivery en Iquique y Alto Hospicio desde las 18:00 h, con disponibilidad y costo por confirmar; envíos regionales con costo y plazo por destino; preparación de 2 a 4 días. No sustituir estos datos por una tarifa ficticia.
- Retiros: Hernán Fuenzalida 2276, Iquique, lunes a jueves 10:00–16:00 h y viernes a domingo 10:00–16:30 h; Calle China 4439, sector La Pampa, Alto Hospicio, previa coordinación entre 19:00 y 22:30 h, días por confirmar.
- Solo activar tarifas automáticas cuando sean estables, verificables y suficientes para mostrar el total antes del pago.
- Para los destinos que requieren cálculo individual, mantener la cotización previa por WhatsApp. Crear la orden pagable después de que el cliente acepte el total.
- La solicitud de cotización no es una compra ni una aceptación de pago. No exigir aceptación de términos comerciales en esta etapa; los términos y el total final deben mostrarse y aceptarse antes de cobrar.
- No se ofrece factura en la etapa inicial. No pedir RUT o razón social para la cotización. Acordar y configurar con SII/asesoría contable la emisión de la boleta electrónica antes de abrir ventas.
- Incluir el importe acordado como línea de envío del pedido y dejar la vía, destino y plazo en las notas del pedido.
- Antes del lanzamiento, completar: cobertura por comuna; precio por vía/destino; plazo de transporte; hora de corte; días del delivery local; disponibilidad desde las 18:00; días del punto de retiro de Calle China 4439; y vigencia de cada cotización.

### Transferencia directa

WooCommerce incluye el método BACS, que opera de forma manual y no confirma depósitos automáticamente. En el escritorio protegido: **WooCommerce → Ajustes → Pagos → Pagos sin conexión → Transferencia bancaria directa**.

Configurar allí el título, descripción, instrucciones y los datos vigentes de la cuenta. Los datos bancarios se ingresan solo en ese escritorio y se muestran al cliente en la página de pedido y en los correos de WooCommerce. No incorporarlos al tema, la maqueta, archivos públicos ni repositorios.

Usar instrucciones claras: pagar el total exacto de la orden, identificar el número de pedido en el comentario de transferencia y consultar por WhatsApp si hay dudas. El vendedor revisa el saldo recibido en BCI; no confiar únicamente en capturas o comprobantes.

Estados previstos:

- **Pendiente de pago:** pedido manual creado y cotización aceptada, antes de que el cliente lo pague.
- **En espera:** WooCommerce recibió la selección de transferencia; falta verificar el abono.
- **Procesando:** fondos verificados; empieza la preparación de productos físicos.
- **Completado:** pedido entregado o despachado y gestión terminada.

No marcar pagado ni comenzar el despacho antes de verificar el abono efectivo. Definir y publicar cuánto tiempo se mantendrá una orden sin pago antes de cancelarla.

## Prueba de aceptación en un sitio de pruebas

Antes de abrir ventas, realizar una orden de prueba por cada modalidad de entrega, incluyendo un despacho regional, un despacho local y cada tipo de retiro habilitado. Comprobar que:

- no se permite pagar antes de informar y aceptar el cargo de despacho;
- el total de productos y envío coincide con la cotización aceptada;
- el enlace de pago y los correos identifican la orden correcta;
- WooCommerce deja la transferencia en espera y muestra instrucciones de prueba;
- sin abono, el pedido no avanza a preparación;
- con abono de prueba verificado, se puede cambiar a “Procesando” y luego a “Completado” al finalizar la entrega;
- cancelación, devolución, inventario y comunicaciones dejan un registro entendible.

No hacer depósitos reales para probar. Usar pedidos de prueba y confirmar la secuencia primero en un entorno de staging. Al pasar a producción, revisar destinatarios de correo, monto, cuenta y textos una vez más.

## Referencias de WooCommerce

- [Configurar transferencia directa (BACS)](https://woocommerce.com/document/bacs/)
- [Crear y gestionar pedidos, pagos y pruebas](https://woocommerce.com/document/managing-orders/)
- [Estados de pedido y errores de pago](https://woocommerce.com/document/troubleshooting-payment-errors/)

## Información de YASOFISH ya recibida y datos aún por cerrar

- Preparación informada: 2 a 4 días.
- Delivery Iquique–Alto Hospicio desde las 18:00 h; días, zonas, disponibilidad, tarifa y plazo por confirmar.
- Retiro en tienda de Hernán Fuenzalida 2276, Iquique, en el horario publicado.
- Retiro coordinado en Calle China 4439, sector La Pampa, Alto Hospicio, entre 19:00 y 22:30 h; días por confirmar.
- Envíos regionales: vías, comunas, costos y plazos se confirman por destino.
- Pago online inicial: transferencia bancaria directa; Mercado Pago con tarjeta solo en el terminal presencial de la tienda.
