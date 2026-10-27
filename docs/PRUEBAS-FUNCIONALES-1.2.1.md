# Pruebas funcionales LocalWP — 26 de octubre de 2026

Ejecutadas sobre el tema activo YASOFISH 1.2.1, WooCommerce 11.2.1, WordPress 7.1.3 y PHP 8.3.36. Evidencia local privada en `outputs/functional-1.2.1/` del espacio de trabajo original; no se incluye en Git por contener copias de configuración y enlaces temporales de pedidos.

## Preparación y alcance

El carrito configurado (página 9) contenía el bloque `woocommerce/cart`, sin shortcode clásico. Se guardó una copia completa de su contenido, revisiones y fechas, además de BACS, cuentas bancarias y opciones del tema. Durante la prueba se sustituyó el contenido por `[woocommerce_cart]`.

Se crearon únicamente dos productos simples locales, ocultos del catálogo y rotulados `YF TEST FICTICIO`, con precios ficticios CLP 1.250 y 2.500, y existencias ficticias de 10 unidades cada uno. Se crearon dos pedidos de prueba mediante la API nativa WooCommerce, con destinatarios del dominio reservado `.invalid`. Los productos y pedidos se eliminaron al finalizar.

BACS se habilitó temporalmente con texto explícito de prueba y sin cuentas bancarias. El WhatsApp temporal fue un valor no enrutable. Un guard temporal interceptó `pre_wp_mail` antes del transporte y bloqueó solicitudes HTTP externas del servidor; el navegador bloqueó toda navegación externa, incluido WhatsApp. Solo las peticiones de prueba desde loopback pudieron omitir la pantalla próximamente; no se cambió su ajuste guardado. No se enviaron mensajes, transferencias ni correos reales.

## Resultados aprobados

| Prueba | Resultado observado |
|---|---|
| Identificar carrito | Cart Blocks confirmado; copia original guardada antes de modificar |
| Carrito clásico | Formulario clásico y `.yf-delivery-quote` presentes |
| Añadir productos | Dos productos agregados desde el navegador; dos filas en carrito |
| Modificar cantidades | Primer producto actualizado de 1 a 3; cantidad persistente tras recargar |
| Actualización y cálculo | 3 × 1.250 + 1 × 2.500 = CLP 6.250 en el total del carrito |
| Checkout inicial | Con productos, `/checkout/` redirige a `/cart/` y muestra cotización previa |
| Formulario vacío | Inválido por campos obligatorios |
| Despacho | Exige calle y número; formulario válido al completarlos |
| Retiro | Oculta y deshabilita dirección; permite cotizar con los demás campos completos |
| WhatsApp | Generó mensaje con 3 unidades, nombre del producto, dirección ficticia y solicitud de total final. Petición a wa.me interceptada y abortada, sin envío |
| Creación de pedidos | Dos pedidos creados mediante API WooCommerce, importe CLP 2.500 cada uno y método BACS |
| Enlace order-pay | Formulario de pago del pedido accesible en navegador, con BACS disponible |
| Procesamiento BACS | Submit real del formulario local llega a order-received; pedido queda `on-hold`, `is_paid=false`, sin fecha de pago |
| Pedido pendiente | Segundo pedido conserva `pending`, `is_paid=false`, sin fecha de pago |
| Generación de correos | Cuatro mensajes interceptados: nuevo pedido al administrador, recepción/en espera al destinatario ficticio, instrucciones/factura del pedido pendiente y repetición explícita del correo en espera. HTML no vacío (13.116–13.762 bytes), con identificación del pedido ficticio |
| JavaScript | Cero excepciones pageerror durante el recorrido de navegador |
| Restauración | Contenido del carrito, BACS, cuentas y opciones del tema comparados exactamente con la copia original |
| Limpieza | Ambos productos y ambos pedidos dejan de existir; cero productos publicados, como al inicio. No quedan carritos con los productos temporales. Revisiones creadas por la prueba retiradas; revisiones anteriores conservadas. Guard temporal eliminado |

El navegador registró 13 comprobaciones aprobadas y ninguna fallida. La creación y los estados se verificaron además en servidor, independientemente del texto mostrado en pantalla. El correo al administrador fue generado hacia la configuración existente, pero el guard devolvió éxito antes de enviar; no se contactó a ese destinatario.

## Problemas y límites observados

- **Cart Blocks:** el hook del formulario del tema solo opera en carrito clásico. No se cambió código del tema para integrarlo con bloques. Conforme a la instrucción de restaurar los ajustes originales, al terminar se repuso Cart Blocks; para uso habitual hay que conservar explícitamente el shortcode clásico o implementar y probar una integración con bloques.
- **Restauración del contenido:** el primer intento con `wp_update_post` no preservó exactamente el HTML de bloques por sus filtros de guardado. La verificación detectó la diferencia; se restauraron los bytes y fechas desde el respaldo de esa página y se confirmó igualdad exacta. No quedó la alteración.
- **Entorno PHP:** persistió el aviso de extensión `php_imagick.dll` ausente ya documentado. No impidió estas pruebas y no se reparó PHP.
- El precio CLP 6.250 corresponde al carrito de cuatro unidades; los pedidos de CLP 2.500 se crearon por separado con dos unidades del primer producto para representar un total confirmado. No se probó una conversión automática de cotización a pedido: el flujo previsto requiere creación del pedido por el vendedor.

## Pendientes y no certificados

No se probaron envío real de WhatsApp, entrega real de correo/SMTP, verificación bancaria, abonos, boleta/impuestos, procesamiento logístico, carga masiva, WordPress Coding Standards ni producción. Tampoco se certifican todos los casos de pago (enlace vencido, importe cero, pedido de cliente autenticado o clave inválida). BACS y correos quedaron probados con datos ficticios y transporte interceptado; no equivale a recepción real ni a pago confirmado.

No hubo cambios de código del tema, fusión del PR, Release ni despliegue. Esta documentación actualiza las pruebas antes pendientes del informe inicial; se vuelve a ejecutar CI sobre el PR #1.
