# Ejecución real — 26 de octubre de 2026

**Actualización posterior:** se completaron pruebas locales de carrito clásico, cantidades, cotización interceptada, order-pay/BACS, estados y generación de correos con restauración y limpieza. Véase [resultados funcionales y límites](PRUEBAS-FUNCIONALES-1.2.1.md). La lista original de pendientes de este documento describe la primera ejecución y queda actualizada por ese informe.

Entrega local versionada en rama `codex/yasofish-1.2.1`; commit de implementación `b1771ce`. Se configuró origin para el repositorio identificado sin hacer push. ZIP final: `outputs/yasofish-storefront-child-1.2.1.zip`, SHA256 `8ad085e90553087ad5cb89573d1731028f77e53f030f6e0f951b896cad0cdf29`. Dos construcciones consecutivas produjeron el mismo hash. Los cambios previos de la demostración estática e IDENTIDAD-Y-ENTREGA.md siguen sin confirmar.

Durante la revisión hubo código anterior servido desde OPcache; se invalidaron los dos PHP del tema para verificar el código actual. La documentación explica cómo reiniciar el sitio LocalWP si vuelve a ocurrir. El servidor de vista temporal se cerró al terminar.

## Alcance ejecutado

Se recuperó Texto pegado.txt desde la conversación referenciada. Se inspeccionaron todos los PHP, CSS y JS del tema, documentación, árbol Git local y árbol main del repositorio público Almostking/yasofishweb mediante conector GitHub. El remoto incluye demostración en raíz, ZIP y tema; no coincide con la estructura local (dist/). No había remoto configurado en el checkout. Se preservaron los cambios anteriores en outputs/audit-before/preexisting.patch y copia del tema. No se modificó el repositorio remoto ni producción.

Modificados por esta revisión: functions.php, front-page.php, style.css, assets/js/delivery-quote.js, screenshot.png, readme.txt e INSTALLACION.md del tema; .gitignore, README.md y workflow de Pages. Añadidos CHANGELOG.md, docs/, scripts/ y workflow theme.yml. Los cambios anteriores de dist/, IDENTIDAD-Y-ENTREGA.md y el procedimiento comercial se conservaron; no se atribuyen a esta auditoría.

Correcciones: contenedor de portada ancho; móvil sin desbordamiento; pie adaptable sin capas contradictorias; contraste del botón y enlaces; navegación de respaldo comercial sin Sample Page; categoría predeterminada excluida; destacados sin productos con estado vacío. Se conserva banner y logo autorizado. Contactos sin valores predeterminados no confirmados, redes y despacho editables. No hay productos ni precios inventados.

Se añadieron guardas para checkout clásico y Store API, conservando enlace order-pay de WooCommerce para pedidos confirmados. Solo BACS puede aparecer como gateway online. No se activa BACS, no se cobra ni se marca un pago automáticamente. El formulario no guarda datos ni envía WhatsApp automáticamente; su envío abre la consulta para decisión del cliente. No se añadió un nonce porque no existe acción de escritura al servidor del tema. WordPress/WooCommerce siguen validando pagos y formularios persistentes.

## Pruebas ejecutadas

| Prueba | Resultado y límite |
|---|---|
| PHP tema | Dos archivos aprobados con PHP 8.3.36 de LocalWP |
| JavaScript | Tres archivos aprobados por node --check |
| PowerShell | Dos scripts aprobados por parser nativo |
| ZIP | Integridad CRC, carpeta raíz, versión, allowlist, SHA256 y construcción determinista comprobadas |
| Instalación/actualización | ZIP aplicado al tema activo LocalWP con respaldo previo; versión leída desde WordPress 1.2.1 |
| Rollback | Restaurado respaldo 1.2.0, versión comprobada desde WordPress, reaplicado ZIP 1.2.1 |
| Integración | Nueve aserciones aprobadas: versión, dos sanitizadores, guardas clásico/API, API de catálogo permitida, solo BACS, BACS desactivado preservado y Storefront padre |
| Portada responsive | HTTP 200, banner presente y sin desbordamiento horizontal a 1440/768/390 px; capturas revisadas |
| JavaScript navegador | Cero excepciones pageerror durante recorrido; no equivale a auditoría exhaustiva de consola |
| Navegación | /shop/, /cart/, /checkout/, /my-account/ responden 200; checkout vacío llega al carrito |
| Configuración | WP 7.1.3, WooCommerce 11.2.1, Storefront 4.6.2; CLP, CL:CL-TA, stock sí, BACS no, impuestos no, registro no, productos publicados 0 |
| Seguridad básica | Escape y sanitización revisados, archivos de tema sin firmas de tokens GitHub/claves privadas en escaneo local. No equivale a escaneo completo del historial ni secretos remotos |

La portada anónima habitual está cubierta por WooCommerce «próximamente». La revisión visual usó un servidor temporal 127.0.0.1 con filtro solo en memoria; no se desactivó esa protección en la base de datos. Las capturas están en outputs/home-*.png, resultados en outputs/browser-results.json. Las rutas españolas probadas inicialmente devolvieron 404; las rutas configuradas reales son las inglesas anteriores.

El registro PHP ya tenía un error histórico de tabla WooCommerce durante activación. Las consultas actuales de catálogo funcionaron. PHP CLI con configuración de Local emite aviso por php_imagick.dll ausente; no es un error del tema y no se reparó la instalación de PHP. No se certificó procesamiento WebP/Imagick.

## Pendientes reales

- Autorizar operación externa para integrar archivos en una rama del remoto, abrir PR, ejecutar CI y publicar una Release aprobada. No se realizó push, PR, Release ni gasto. CI y descarga desde Release están preparados, aún no ejecutados contra GitHub.
- Verificar carrito clásico; el formulario por hook no se integra con Cart Blocks. El bloqueo de checkout sí cubre Store API. No se cambió contenido de páginas existente.
- No ejecutados: pedidos simulados, pago BACS por order-pay, estados posteriores al pago, correo recibido, carrito con productos, formulario WhatsApp completo, validación exhaustiva de enlaces, pruebas de carga o benchmark, ni WordPress Coding Standards (no disponible). No están aprobados.
- Confirmar datos comerciales, catálogo real, impuestos/boleta, políticas, banco y correos. No se cambiaron ajustes del negocio ni datos de clientes.
- Git Updater no instalado por licencia de autenticación y estructura monorepo. Alternativa Release + scripts lista para LocalWP, sin actualización automática en el escritorio WordPress. cPanel depende de hosting/acceso futuro.

No se tocó yasofish.cl, no se contrataron servicios, no se enviaron mensajes, no se expusieron secretos y no se eliminaron datos.

## Preparación del PR autorizado

Rama reconstruida desde main remoto 3a81ef4 para incluir únicamente YASOFISH 1.2.1. No incluye cambios de la demostración estática, archivos privados, outputs, respaldos, bases de datos ni el workflow local de Pages. El ZIP se construye mediante CI; el ZIP antiguo de la raíz se conserva sin modificar. Los commits b1771ce y 528453f son referencias de la entrega local previa y no se suben como historial del PR.
