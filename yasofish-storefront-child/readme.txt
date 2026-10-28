YASOFISH Chile — tema hijo de Storefront
========================================

Requisitos
----------
* WordPress 6.9 o posterior (referencia para versiones actuales de WooCommerce).
* Storefront instalado (se conserva como tema padre).
* WooCommerce para catálogo, productos, carrito, impuestos, despacho, pedidos y checkout.
* PHP 8.3 o posterior recomendado; MySQL 8.0 o MariaDB 10.6 o posterior; HTTPS; al menos 256 MB de memoria para WordPress.

Instalación
-----------
1. Instala y activa Storefront desde Apariencia > Temas.
2. En Apariencia > Temas > Añadir tema > Subir tema, carga yasofish-storefront-child.zip.
3. Activa YASOFISH Chile.
4. Instala/configura WooCommerce y asigna sus páginas de tienda, carrito y finalizar compra.
5. Selecciona una portada estática en Ajustes > Lectura para activar la portada personalizada.
6. Sube la versión de mayor resolución del logo desde Apariencia > Personalizar > Identidad del sitio.
7. Edita el aviso, portada y número con código de país en Apariencia > Personalizar > YASOFISH Chile.
8. Configura CLP, impuestos, productos, inventario y zonas de despacho en WooCommerce.
9. Para ventas online iniciales, activa la transferencia bancaria directa integrada de WooCommerce y configura la cuenta desde el escritorio de WordPress. Mercado Pago con tarjeta está disponible solo en el terminal presencial de la tienda; no actives un gateway online de tarjeta hasta que el titular decida incorporarlo.
10. Sigue la sección Cuentas, cartera de clientes y avisos de INSTALLACION.md para activar Mi cuenta, limitar roles y configurar consentimientos de marketing/stock.

Compatibilidad
--------------
No incluye plantillas duplicadas de WooCommerce. Usa los flujos y extensiones nativos para mantener compatibilidad con sus actualizaciones. La portada consulta categorías y productos destacados reales desde WooCommerce. Los botones de WhatsApp se mantienen apagados hasta guardar un número válido.

Seguridad y alcance
-------------------
El tema no solicita, almacena ni transmite datos de pago y no tiene credenciales. WooCommerce gestiona las transferencias y sus instrucciones desde el escritorio de la tienda; no incluyas datos bancarios en este tema. WordPress/WooCommerce validan carrito, pedido y cantidades en servidor. Mantén WordPress, Storefront, WooCommerce y extensiones actualizados; utiliza HTTPS, MFA para administradores y copias de respaldo.

Versión 1.2.1: validada sintácticamente con PHP 8.3.36 y JavaScript con Node. Instalada en LocalWP y revisada a 1440, 768 y 390 px mediante vista temporal de loopback, conservando el modo próximamente. Carrito vacío y cuentas cargan; pedidos, pagos y carrito con productos pendientes de prueba. Informe completo y rollback en docs/ del repositorio.

Marca
-----
La imagen de marca proporcionada pertenece a YASOFISH Chile. Usa únicamente versiones autorizadas y de alta resolución del logo para la tienda.
