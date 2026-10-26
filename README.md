# YASOFISH Chile

Demo navegable de una tienda especializada en pesca y código fuente de un tema hijo para WooCommerce + Storefront.

## Demo

https://almostking.github.io/yasofishweb/

La demo es estática: catálogo, precios, inventario, carrito, formularios, pagos y cuentas son de ejemplo. No crea pedidos, no envía consultas y no recibe pagos.

## Tema WooCommerce

El archivo `yasofish-storefront-child-source.zip` contiene el tema hijo preparado para una instalación WordPress con WooCommerce y Storefront. Revisa la [guía de instalación](yasofish-storefront-child/INSTALLACION.md) y el [procedimiento de cotización, transferencia y despacho](yasofish-storefront-child/PROCEDIMIENTO-VENTA-Y-DESPACHO.md).

La primera etapa prevista cotiza disponibilidad y despacho por WhatsApp antes del pago; la transferencia BACS se configura manualmente en WooCommerce y el vendedor debe verificar el abono real. Mercado Pago con tarjeta se usa actualmente solo en el terminal presencial. No hay conexión con una tienda real ni credenciales en este repositorio.

## Publicación

La demo publicada usa los archivos HTML, CSS, JavaScript e imágenes de la raíz. El código fuente de la maqueta está en `dist/` en la carpeta de trabajo local. WooCommerce requiere contratar y configurar un hosting WordPress por separado; GitHub Pages solo sirve la demo estática.
