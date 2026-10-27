# Auditoría final e integración comercial local — 27 de octubre de 2026

## Resultado de esta etapa

Se continuó en `codex/yasofish-1.2.1`, PR #1, sobre WordPress LocalWP existente. La portada y sus recursos aprobados no se reconstruyeron. Se completaron catálogo nativo, filtros, idioma oficial, páginas informativas de revisión y administración de pendientes. No es una publicación comercial: faltan datos reales, aprobación legal/comercial, hosting y verificaciones externas.

La base técnica observada es WordPress 7.1.3, WooCommerce 11.2.1, Storefront 4.6.2 y PHP 8.3.36. Las cuatro categorías son términos nativos. La página Carrito sigue siendo ID 9, mismo slug/URL/asignación y contenido `[woocommerce_cart]`. BACS no se habilitó; próximamente se conservó.

## Matriz de comparación completa

| Área de la maqueta | Diferencia encontrada | Resolución WordPress y límite |
|---|---|---|
| Portada y banner | Migración ya aprobada | Conservada; comprobada de nuevo a 1440/768/390 px. Sin productos reales usa estado vacío, no inventario demo |
| Catálogo | Sin panel nativo completo; estilos de la demo reducían las tarjetas WC | Panel lateral, cuadrícula nativa de tres columnas en escritorio y dos en móvil/tablet, con CSS delimitado |
| Categorías | Accesos usaban búsquedas si no existían términos | Cañas, Carretes, Señuelos y Accesorios creados; archivos `/product-category/.../` verificados con y sin productos |
| Búsqueda | WooCommerce enviaba una coincidencia directamente a su ficha | Se conserva una página de resultados real incluso para una sola coincidencia |
| Filtros | Datos estáticos de la demo / ausencia de controles integrados | Categoría, precio mínimo/máximo y Solo ofertas consultan WooCommerce. Los precios vacíos no se convierten en máximo cero |
| Ordenamiento | Opciones de demo sin catálogo real | Selector nativo WooCommerce; orden descendente probado |
| Tarjetas y descuentos | Márgenes/anchos heredados y colisiones de `.products` | Anchos completos de columna; precios normal/rebajado, botón y disponibilidad nativos. No se asignaron fotos inventadas |
| Producto | Ficha y recomendaciones afectadas por cuadrícula genérica | Ficha nativa localizada y responsive; cuadrícula estática limitada a tarjetas informativas. Stock y agotado probados |
| Variantes y ficha jig de la demo | Pesos, colores y consejos de productos de demostración | No importados. WooCommerce permite atributos/variaciones administrables, pendientes del catálogo real y de pruebas específicas de variantes |
| Carrito | Flujo clásico aprobado; necesitaba comprobar traducción/diseño | Conservado y probado: cantidades, persistencia y total CLP 5.750 con descuento ficticio de QA |
| Cotización | Debía mantenerse separada del pago | Validaciones de despacho/retiro y mensaje WhatsApp interceptado aprobados. No crea ni paga pedidos automáticamente |
| Checkout | No debe aceptar una compra antes de coordinar | Redirige al carrito; guarda de Store API y validación clásica verificadas |
| Enlaces de pago | Pedido pendiente requiere clave y, según la protección WC, verificar correo | Se verificó correo ficticio y apareció el formulario nativo. BACS siguió ausente por estar apagado |
| Mi cuenta | Interfaz en inglés y falta de prueba autenticada | Idioma oficial; acceso con cliente temporal, pedidos, direcciones, datos y recuperación de contraseña comprobados; cliente eliminado |
| Información y políticas | Enlaces faltantes o sin contenido apropiado | Siete páginas locales con contenido y aviso de borrador; enlaces comprobados, no aprobados como contrato definitivo |
| Encabezado/pie | Faltaban enlaces, contacto y distinción de pagos | Cuatro columnas conservadas; enlaces reales, correo del proyecto y Mercado Pago solo presencial. No se copiaron dirección/horarios |
| Administración | Pendientes dispersos | Apariencia → Preparación YASOFISH y campos del Personalizador; productos, pedidos, banco y transporte siguen en interfaces nativas |
| Idioma | Sin paquetes oficiales instalados | `es_CL` y paquetes oficiales exactos de núcleo/WooCommerce/Storefront; títulos cambiados sin cambiar URLs. No se modificó el núcleo |
| Móvil/tablet | Tarjetas y recomendaciones estrechas | Corregidas colisiones y ancho; rutas principales HTTP 200 y documento sin desbordamiento a los tres anchos |

No se declara igualdad píxel a píxel. El rango numérico de precios sustituye al slider de techo ficticio de la maqueta. Los títulos, formularios, pagos, errores y cuenta son nativos y pueden diferir visualmente de sus simulaciones. Las fotos y datos definitivos necesitan revisión posterior al cargarse.

## Capturas verificables

La [comparación de portada anterior](MIGRACION-VISUAL-1.2.1.md) se conserva. Evidencia de esta etapa en `docs/auditoria-final/`:

| Vista | Maqueta | WordPress |
|---|---|---|
| Catálogo escritorio | [Referencia](auditoria-final/reference-catalog-1440.png) | [QA con productos temporales](auditoria-final/catalog-1440.png) |
| Catálogo móvil | [Referencia](auditoria-final/reference-catalog-390.png) | [Estado final vacío](auditoria-final/catalog-empty-390.png) |
| Producto escritorio | [Referencia](auditoria-final/reference-product-1440.png) | [Producto ficticio de QA, ya eliminado](auditoria-final/product-1440.png) |
| Producto móvil | [Referencia](auditoria-final/reference-product-390.png) | [QA, ya eliminado](auditoria-final/product-390.png) |
| Mi cuenta | [Referencia](auditoria-final/reference-account-1440.png) | [WordPress](auditoria-final/account-1440.png) |
| Información | [Referencia](auditoria-final/reference-information-1440.png) | [Borrador local](auditoria-final/information-1440.png) |
| Portada final | Referencia ya documentada | [1440](auditoria-final/home-empty-1440.png), [390](auditoria-final/home-empty-390.png) |
| Catálogo sin inventario | La demo contiene productos ficticios | [1440](auditoria-final/catalog-empty-1440.png), [768](auditoria-final/catalog-empty-768.png) |

Las capturas de QA con precios corresponden exclusivamente a datos ficticios identificados y eliminados. Las referencias GitHub conservan datos demo que no se importaron. El conjunto completo de capturas a 1440/768/390 px y resultados privados permanece en `outputs/final-audit-1.2.1/` del espacio original, fuera de Git.

## Pruebas ejecutadas

| Grupo | Resultado real |
|---|---|
| Navegador principal | 53 de 54 aserciones aprobadas; 21 recorridos responsive de siete páginas incluidos. La aserción inicial de order-pay esperaba el formulario antes de verificar correo; se corrigió el procedimiento, no se eliminó la protección |
| Order-pay con verificación | Tres aserciones adicionales aprobadas: protección presente, formulario tras correo correcto, BACS desactivado. Cierra la comprobación anterior |
| Envío real de filtros | Categoría con precios vacíos, precio rebajado y recuperación de contraseña: tres aprobadas |
| Solo ofertas | Dos aprobadas con dos productos adicionales de QA; oferta calculada por WooCommerce, no un arreglo estático |
| Mi cuenta autenticada | Cuatro aprobadas: login, pedidos, direcciones y datos de cuenta |
| Administración | Diez aprobadas: capacidades de productos/pedidos/páginas/datos, pantalla de pendientes, controles reales del Personalizador, idioma y soporte WebP |
| Aprobación de políticas | Cinco aprobadas sobre una página temporal propia: anónimo rechazado, nonce inválido rechazado, aprobación válida, revocación y eliminación. Ninguna página comercial real fue aprobada |
| Integración de tema | Nueve aprobadas: versión, sanitización, guardas clásico/API, catálogo permitido, BACS solo cuando disponible, desactivación preservada y herencia Storefront |
| Vacíos tras limpieza | Cuatro categorías, catálogo y portada a tres anchos, y carrito sin formulario cuando vacío, comprobados sin inventario ficticio |
| BACS simulado en servidor | Método nativo invocado solo para pedido ficticio de total positivo, sin activar pasarela: `on-hold`, sin pago ni fecha de pago. Segundo pedido `pending`, tampoco pagado |
| Correos | Cinco generados e interceptados antes del transporte: cuatro mensajes de pedidos y uno de nueva cuenta. HTML no vacío, textos en español. Ninguno entregado a clientes ni administrador reales |
| Rollback de preparación | Revertidos categorías/páginas/opciones registradas desde checksum y reaplicados con un nuevo respaldo. Se conservó el carrito y sus asignaciones |
| Sintaxis y paquete | PHP 8.3, JavaScript y SVG validados; ZIP con carpeta raíz, allowlist y SHA256. CI ejecuta la construcción y lint de scripts |

Evidencia sanitizada de resultados se adjunta en `docs/auditoria-final/`. No se reescribió una aserción fallida como aprobada: la verificación de correo de order-pay se registra por separado. No se prueba un pago real ni el envío bancario con BACS apagado; la simulación directa del gateway no equivale a probar un cobro por formulario en producción.

## Incidencias y seguridad

- Se corrigió el caso real de filtros HTML con precios vacíos: WooCommerce interpretaba el límite vacío como cero. Se omiten solo esos valores vacíos antes de la consulta; cero explícito sigue siendo un límite válido.
- Los errores de arranque del arnés CLI de QA (orden de registro del Personalizador y cantidad de argumentos de save_post) se corrigieron. No fueron errores de páginas de visitantes; no se borraron registros para ocultarlos.
- Sigue el aviso preexistente de `php_imagick.dll` ausente en PHP de Local. Se detectó soporte WebP mediante el editor disponible; no se certifica conversión masiva ni rendimiento con imágenes reales.
- El navegador no registró excepciones JavaScript en el recorrido. Se conservaron mensajes importantes de WooCommerce, incluida verificación de correo y ausencia de pasarelas. No se ocultaron errores mediante CSS.
- Consultas y filtros usan APIs/lookup de WooCommerce; entradas se delimitan y escapan. El control de aprobación exige edición y nonce. Los borradores no aprobados tienen noindex y enlaces ocultos para visitantes fuera de LocalWP.
- No se agregaron plugins, servicios de pago ni requisitos de VPS. Se mantiene caché nativa, paginación nativa, imágenes responsive y carga diferida donde corresponde. No se ejecutó una prueba de volumen con cientos de productos o miles de imágenes.
- No se instalaron WordPress Coding Standards ni se declara su aprobación; no estaban disponibles. El escaneo de patrones de secretos y archivos privados no sustituye una auditoría exhaustiva del historial remoto.

## Estado final y administración

LocalWP debe cerrar esta ejecución en **es_CL, CLP, CL:CL-TA, carrito clásico ID 9, BACS no, próximamente sí, cero productos, pedidos, clientes o páginas ficticias de QA**. Permanecen cuatro categorías nativas vacías y siete páginas de revisión no aprobadas. Se retira el guard temporal al acabar las capturas; el estado verificado queda registrado en `estado-final.json`.

Los borradores tienen estado técnico publish solo en el sitio local protegido para poder revisar enlaces; no son documentos definitivos ni se asignaron como términos contractuales del checkout. El checkbox administrativo permite al titular revisar/aprobar posteriormente sin editar código. Más detalles: [administración y reproducción](ADMINISTRACION-YASOFISH.md), [datos y pendientes](DATOS-COMERCIALES.md), [fuentes y borradores](legal/README.md).

## Archivos modificados y alcance GitHub

- `template-parts/commerce.php`: filtros, vínculos, panel administrativo, campos, revisión de políticas y seguridad.
- `template-parts/visual.php`: enlaces nativos, información/contacto y pago presencial distinguido.
- `assets/css/commerce.css`, ajuste delimitado de `assets/css/visual.css`: páginas internas y corrección de colisiones nativas.
- `functions.php`: carga del módulo y aviso comercial, sin modificar el flujo aprobado de cotización.
- `scripts/prepare-local-commerce.php`: preparación local con respaldo y rollback; solo yasofish.local.
- Documentación, siete borradores HTML, matriz, capturas, resultados sanitizados y registro de cambios. Los paquetes oficiales de idioma se instalaron en wp-content/languages, fuera del repositorio y del ZIP del tema.

Continúa el PR #1 como borrador. El ZIP del tema no incorpora páginas, categorías, idioma, bancos ni base de datos: esos cambios de configuración se documentan por separado. No se fusiona main, no se publica Release y no se despliega producción.

## Pendientes indispensables antes de producción

1. Confirmar catálogo/fotos/precios/stock, identidad legal, dirección, atención, retiro/delivery y condiciones de despacho.
2. Aprobar y completar los siete textos y revisar el marco legal/fiscal vigente para la fecha de lanzamiento; configurar documento tributario con asesor.
3. Contratar hosting compatible, configurar dominio/HTTPS, respaldos y restauración, controles de acceso y transporte de correo, todo con autorización.
4. Configurar banco exclusivamente en WooCommerce después de verificar titularidad y probar el flujo real bajo control; no habilitar Mercado Pago online.
5. Probar recepción de correos, WhatsApp, abonos, logística y contenido final en staging; revisar variantes si se venden productos variables, concurrencia, rendimiento y actualizaciones.
6. Recién con aprobación explícita, publicar políticas definitivas, configurar aceptación/privacidad y autorizar lanzamiento. Nada de esta auditoría constituye esa aprobación.
