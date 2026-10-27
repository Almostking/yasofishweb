# YASOFISH Chile

Código de la tienda para WordPress, WooCommerce y el tema Storefront.

## Paquete para la tienda

El tema instalable está en `yasofish-storefront-child/` y se entrega como [yasofish-storefront-child-source.zip](yasofish-storefront-child-source.zip). La portada y catálogo leen productos reales de WooCommerce; el carro, cuentas, pedidos, checkout y pagos dependen de WooCommerce. El despacho inicial se cotiza por WhatsApp antes de crear una orden pagable.

Antes de aceptar ventas se deben instalar WordPress, Storefront y WooCommerce en el hosting, cargar el catálogo y configurar los datos comerciales, boleta electrónica, transferencia BACS, correos y operación de despacho. La cuenta bancaria solo se configura en el escritorio protegido de WooCommerce. Sigue [la guía de instalación](yasofish-storefront-child/INSTALLACION.md) y [el flujo de venta y despacho](yasofish-storefront-child/PROCEDIMIENTO-VENTA-Y-DESPACHO.md).

## Vista local

`dist/` contiene una versión estática para revisar la apariencia y navegación. No está conectada a WordPress ni procesa pedidos, cuentas o pagos. La vista publicada en GitHub Pages también es estática; no es la tienda comercial.

## Requisitos de publicación

Se necesita un hosting WordPress con HTTPS, Storefront y WooCommerce. El sitio productivo no se publica desde GitHub Pages. El tema no incluye credenciales, base de datos ni una pasarela de pago; WooCommerce administra esos componentes en el servidor.
