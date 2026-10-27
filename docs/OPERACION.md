# Desarrollo, actualización y reversión

**Carrito definitivo 1.2.1:** en LocalWP se dejó `[woocommerce_cart]` de forma permanente en la página existente. Consulta [copia verificable, pruebas y procedimiento reproducible para hosting](CARRITO-CLASICO-1.2.1.md); los cambios de esta página son configuración de base de datos y no viajan en el ZIP del tema.

Si tras actualizar PHP sirve código anterior desde OPcache, detener e iniciar el sitio en LocalWP. La vista temporal incluida invalida los dos PHP del tema antes de revisarlos; esta incidencia de caché se observó durante las pruebas y se resolvió para la captura final.

Fuente prevista: https://github.com/Almostking/yasofishweb (público). La copia local y main remoto tienen estructuras diferentes. No hacer push forzado. El remoto contiene la demostración estática en raíz y el tema en subdirectorio; mantener esos archivos al integrar esta propuesta en una rama codex/yasofish-1.2.1 desde main remoto. Publicar un PR y revisar el diff antes de fusionar. Los cambios locales anteriores al trabajo están respaldados en outputs/audit-before.

## Validación y paquete

Requiere PHP 8.3, Node y Python 3.11. Ejecutar `python scripts/build.py`; en Windows se puede definir YASOFISH_PHP con la ruta de PHP de LocalWP. Genera outputs/yasofish-storefront-child-1.2.1.zip y su SHA256. No incluye WordPress, uploads, configuración privada ni base de datos. CI ejecuta las mismas verificaciones y guarda un artefacto. La sintaxis no sustituye pruebas funcionales ni WordPress Coding Standards.

El workflow theme.yml valida PR y main; su ejecución manual desde main crea un borrador de Release solo después de validar. Revisar el artefacto en LocalWP y publicar el borrador únicamente con aprobación. No instala nada en producción. Este PR solo añade validaciones del tema; no añade ni ejecuta workflows de despliegue de Pages.

## LocalWP

`scripts/sync-local.ps1 -ExpectedSha256 <hash verificado>` instala el paquete local en el sitio yasofish. Comprueba destino, checksum y crea ZIP de respaldo antes de copiar. No cambia productos, ajustes ni base de datos. Es una copia superpuesta: archivos locales ajenos no se borran. Si falla la copia, restaurar el respaldo antes de continuar. La primera instalación del tema ya existe; Storefront debe permanecer instalado.

Una vez publicada y aprobada una Release, `scripts/update-from-github.ps1 -Version 1.2.1` descarga ZIP y checksum mediante GitHub CLI y ejecuta la sincronización. Repetir con una versión aprobada futura. Las credenciales de gh permanecen fuera del tema. El checksum comprueba integridad, no es una firma de autor. Revisar siempre repositorio, versión y validaciones.

Git Updater fue evaluado: https://github.com/afragen/git-updater requiere licencia para peticiones autenticadas tras la prueba. Además, el tema vive en subdirectorio y main no es un paquete de tema. No se instala ni se añade un encabezado engañoso. Alternativa preparada sin dependencia de pago: Release validada + actualización explícita con gh/PowerShell para LocalWP. Para cPanel futuro: actualizar desde el ZIP aprobado en Apariencia o habilitar SSH/WP-CLI con respaldo y aprobación. No hay actualizador automático dentro de WordPress; esa integración futura queda pendiente de elegir hosting y acceso.

## Rollback

1. Detener cambios y conservar copia del tema actual y respaldo completo de archivos/base de datos antes de una futura operación comercial.
2. En LocalWP, seleccionar el ZIP anterior de outputs/local-backups (estructura yasofish-storefront-child/) y calcular SHA256 con Get-FileHash.
3. Ejecutar sync-local.ps1 con -Package apuntando al respaldo y -ExpectedSha256 al hash obtenido. Se crea otro respaldo antes de restaurar.
4. Comprobar portada, carrito, cuentas y enlace de pago; limpiar caché si corresponde. No restaurar una base de datos antigua sobre pedidos recientes para revertir solo código.

No borrar archivos adicionales automáticamente. Una reversión entre versiones que agreguen archivos requiere revisar cuáles retirar. El paso 1.2.0 ↔ 1.2.1 conserva funciones nuevas en archivos adicionales inertes; no requiere migrar datos.

## Configuración comercial pendiente

En LocalWP se observó CLP, Chile/Tarapacá, stock activo, impuestos desactivados, BACS desactivado, cuentas con registro desactivado y cero productos. No se modificaron opciones. Confirmar WhatsApp, contacto, horarios, retiro, despacho, redes y avisos desde Personalizar. No se inventaron datos para llenar campos vacíos.

Mantener modo próximamente. Confirmar impuestos y boleta con titular/asesor; configurar banco exclusivamente en WooCommerce, correos, políticas y cuentas. BACS deja pedidos en espera; un comprobante no acredita abono. El responsable verifica banco y actualiza el pedido. Mercado Pago solo presencial. El tema filtra gateways online a BACS, sin activar ninguno.

Usar carrito clásico de WooCommerce para el formulario de cotización: los bloques no ejecutan woocommerce_proceed_to_checkout. El bloqueo de pago sí cubre Store API. Cambiar la página del carrito a shortcode [woocommerce_cart] debe hacerse tras revisar su contenido; no se cambió en esta ejecución.

## Catálogo y medios

Crear categorías reales en Productos > Categorías; portada lista hasta cuatro categorías raíz incluso vacías. No se crearon productos ni precios ficticios. WooCommerce mantiene stock y tamaños de imagen; subir WebP autorizado, usar tamaños responsive nativos y evitar originales gigantes. La imagen de marca del banner es prioritaria; productos usan renderizado nativo. Dimensionar compresión, copias y almacenamiento cuando se conozcan hosting y catálogo. No se compraron plugins ni servicios.
