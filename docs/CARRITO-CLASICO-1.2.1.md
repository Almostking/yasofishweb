# Carrito clásico permanente y procedimiento de hosting

## Configuración LocalWP ejecutada — 26 de octubre de 2026

La página Carrito existente, ID **9**, mantiene su URL `http://yasofish.local/cart/`, slug y asignación en WooCommerce. Su contenido persistente ahora es `[woocommerce_cart]`, sin `woocommerce/cart`. No se creó una página nueva. Esta configuración queda guardada en la base de datos, independientemente de la sesión de prueba o de GitHub.

Antes de modificar se guardó el registro completo de la página (incluido HTML original), URL, asignación, estado BACS, modo próximamente y versión del tema en un JSON fuera del directorio público. Se verificó su lectura completa y SHA256: `7beb0cdfd1954bad66e9f342ba51c563efddc8f0262b82c596cc82948d4da685`. La copia y su archivo `.sha256` permanecen en `outputs/classic-permanent-1.2.1/backups/` del espacio de trabajo original; no se suben a GitHub.

El WhatsApp comercial confirmado por el titular se guardó en el Personalizador, separado del código del tema. Se verificó la URL construida sin solicitarla a WhatsApp. Se retiró el valor ficticio de las pruebas. Con carrito vacío no se muestra formulario de cotización; al agregar productos y existir un WhatsApp válido aparece el formulario clásico.

## Pruebas ejecutadas y aprobadas

Diez comprobaciones de navegador, todas aprobadas y sin excepciones JavaScript:

1. Agregar dos productos ficticios locales: dos filas en el carrito.
2. Mostrar simultáneamente el carrito clásico y el formulario de cotización.
3. Cambiar la cantidad del primer producto a 3 y comprobar persistencia tras actualizar/recargar.
4. Total correcto: 3 × CLP 1.250 + 1 × CLP 2.500 = CLP 6.250.
5. Acceder a `/checkout/` con productos redirige a `/cart/` para cotizar antes de pagar.
6. Formulario vacío rechazado por campos obligatorios.
7. Despacho exige calle y número.
8. Formulario completo válido.
9. Retiro oculta/deshabilita la calle y conserva validez.
10. Mensaje WhatsApp contiene cantidades, producto, dirección ficticia y solicitud de total. Navegación externa interceptada y abortada: no se envió mensaje.

BACS permaneció desactivado durante toda esta ejecución. No se crearon pedidos. Se usaron dos productos temporales ocultos del catálogo, identificados como ficticios, que después se eliminaron junto con su sesión de carrito. Se retiró el guard temporal de correo/red externa y se restauraron las opciones temporales antes de guardar el WhatsApp comercial confirmado.

Estado final verificado mediante WordPress/WooCommerce: **carrito clásico permanente; mismo ID/URL/asignación; cero productos; cero pedidos; BACS desactivado; próximamente activo; WhatsApp comercial configurado; sin guard temporal**. Se comprobaron también idempotencia del script (segunda aplicación sin cambios), restauración exacta desde el respaldo original y reaplicación del clásico. El estado final vuelve a ser el clásico autorizado.

Evidencia privada local: `outputs/classic-permanent-1.2.1/browser-results.json`, `cleanup-results.json`, `final-state.json` y captura `classic-cart.png`. La prueba del mensaje usó un número ficticio interceptado; después se verificó la construcción del enlace con el número confirmado, sin una petición externa.

## Repetición en el hosting definitivo

Este procedimiento es una operación de configuración de base de datos, no un efecto de actualizar el ZIP. No se ejecutó en producción. Antes de hacerlo en hosting se necesita aprobación, copia completa de archivos/base de datos y acceso al sitio en mantenimiento/próximamente. Conservar Storefront, WooCommerce y el tema hijo.

### Desde CLI (PHP y acceso al WordPress del hosting)

1. Obtener los scripts de la revisión aprobada. Definir `YASOFISH_WP_ROOT` con la ruta del WordPress y `YASOFISH_BACKUP_DIR` con una carpeta privada fuera de la raíz pública. No ubicar respaldos en uploads ni public_html.
2. Inspeccionar sin modificar: `php scripts/configure-classic-cart.php --allow-nonlocal`. Sin `--apply`, solo informa la página asignada y su tipo.
3. Tras aprobación para ese hosting, ejecutar `php scripts/configure-classic-cart.php --apply --allow-nonlocal`. Genera y verifica JSON + SHA256 antes de cambiar el contenido; usa el ID actualmente asignado, no el ID 9 de LocalWP. Mantiene URL/slug/asignación, apaga BACS y mantiene próximamente. No guarda bancos, contraseñas ni WhatsApp en el script.
4. Revisar `verified: true`, shortcode, URL y asignación. Configurar WhatsApp comercial en Apariencia → Personalizar → YASOFISH Chile. Hacer pruebas controladas de carrito/cotización y limpiar únicamente datos temporales. Mantener BACS apagado y próximamente activo hasta autorización comercial independiente.
5. Para revertir el contenido con su copia verificable: `php scripts/configure-classic-cart.php --apply --allow-nonlocal --restore=/ruta/privada/cart-RESPALDO.json`. El script exige checksum correcto y mismo ID/URL/asignación, respalda el estado actual y restaura exactamente el contenido anterior. Conserva BACS apagado y próximamente activo, incluso al revertir.

En LocalWP se omite `--allow-nonlocal` y se utiliza el PHP/configuración de Local. El script rechaza otros dominios por defecto. Si el hosting no permite CLI: respaldar contenido y asignación desde WordPress y copia completa del proveedor; editar la misma página en el editor de código, reemplazar Cart Blocks por un bloque Shortcode con `[woocommerce_cart]`, guardar y verificar ID/URL y Ajustes → WooCommerce → Avanzado. No importar una base de datos local sobre pedidos del hosting.

## Límites

Se resolvió la incompatibilidad seleccionando permanentemente el carrito clásico; no se desarrolló integración con Cart Blocks. Reinsertar ese bloque volvería a omitir el formulario del tema. Continúan pendientes las validaciones comerciales (banco/abonos, correo real, impuestos/boleta y logística) y las pruebas en el hosting futuro. No hubo fusión, Release, despliegue ni activación de ventas.
