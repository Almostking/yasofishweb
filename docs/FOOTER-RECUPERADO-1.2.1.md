# Recuperación fiel del footer — 27 de octubre de 2026

> Revisión vigente: [Migración fiel GitHub → WordPress](MIGRACION-FIEL-1.2.1.md). La referencia completa se muestra solo en LocalWP, con ejemplos en memoria y sin inventario ficticio. Esta instrucción sustituye el ocultamiento de referencias local y la franja adicional de enlaces del footer de informes anteriores. Hosting requiere datos aprobados.

Se trabajó exclusivamente sobre el footer y su administración, en la rama `codex/yasofish-1.2.1`, PR #1. No se modificaron encabezado, banner, catálogo, inventario, carrito ni checkout. Los cambios anteriores siguen documentados en la auditoría general; este informe actualiza únicamente el footer.

## Fuente comprobada

Se descargó mediante la API GitHub el [index.html original de main](https://github.com/Almostking/yasofishweb/blob/main/index.html), blob `66f7f54db135e987ade35a4c677efa3b7f52d735`. Coincide con el HTML de la maqueta en el checkout. Los valores se extrajeron de sus nodos, no de prompts anteriores. Se capturó también la [referencia publicada](https://almostking.github.io/yasofishweb/). No se modificó la maqueta.

## Información conservada y estado comercial

| Campo del Personalizador | Referencia original recuperada | Estado final |
|---|---|---|
| Dirección de atención o retiro | Hernán Fuenzalida 2276, Iquique, Chile | Pendiente de aprobación |
| Horario de la tienda | Lunes a jueves, 10:00–16:00 h; viernes a domingo, 10:00–16:30 h | Pendiente de aprobación |
| Punto de retiro coordinado | Calle China 4439, sector La Pampa, Alto Hospicio, de 19:00–22:30 h. Coordinar antes; días por confirmar | Pendiente de aprobación |
| Delivery local | Iquique y Alto Hospicio desde las 18:00 h; disponibilidad y costo por confirmar | Pendiente de aprobación |
| Preparación de pedidos | Pedidos online: preparación de 2 a 4 días | Pendiente de aprobación |
| Despacho del footer | Despacho local y a regiones; plazo y costo según destino, a confirmar antes del pago | Pendiente de aprobación |
| Teléfono de atención | +56 9 6650 9563, tomado del `tel:` original | Guardado; enlace `tel:+56966509563` |
| Texto de novedades | Pronto podrás recibir ofertas y avisos de reposición con tu autorización | Guardado; no existe formulario ni servicio de suscripción |

Correo `yasofishchile@gmail.com`, WhatsApp e Instagram/Facebook/TikTok ya estaban configurados y se conservaron; se verificaron sus destinos contra el HTML original. No se envió correo, llamada ni mensaje, ni se certificó titularidad o disponibilidad de las cuentas sociales.

No se fijan direcciones, horarios ni plazos comerciales en PHP. Se reutilizan los cuatro campos de ubicación y el teléfono existentes. El texto de despacho del footer tiene un campo separado porque el campo anterior de condiciones también pertenece al formulario de carrito: ese texto del carrito conserva exactamente su valor previo. Las páginas informativas existentes mantienen IDs y contenidos.

## Administración y aprobación explícita

En **Apariencia → Personalizar → YASOFISH Chile** están todos los campos. Un administrador puede ver la referencia en la previsualización y en el sitio autenticado. Los visitantes sin permiso no ven ubicación, horario, retiro, delivery, preparación ni despacho de referencia, incluso en LocalWP. Las capturas de administrador llevan un aviso de referencia pendiente; las capturas de visitante muestran el ocultamiento.

La casilla **«Apruebo dirección, horario, retiro, delivery, preparación y despacho para publicación comercial»** guarda una huella de los seis valores exactos. Solo usuarios con `edit_theme_options` pueden aprobar mediante el Personalizador nativo, con sus controles de autorización y nonce. Se probaron aprobación de valores actuales y valores recién editados. Cambiar cualquiera invalida la aprobación; para aprobar de nuevo, revisar todos, desmarcar y volver a marcar la casilla, y publicar los cambios del Personalizador. Desmarcar revoca la aprobación. Vaciar la caché del hosting si existe tras aprobar o revocar.

**La ejecución termina con la casilla sin aprobación.** La prueba técnica de aprobación se revirtió. Este permiso es independiente del de políticas legales y no habilita BACS, ventas ni el sitio público. Un usuario sin permisos no puede aprobar.

## Matriz visual y funcional

| Área | Antes | Resultado y diferencia restante |
|---|---|---|
| Marca, fondo, separadores y redes superiores | Ya coincidían | Conservados; mismos SVG originales |
| Pesca | Cuatro enlaces nativos | Conservados; no se incorpora «Ofertas de demostración» |
| Atención al cliente | Lista legal larga; teléfono ausente, correo en otra columna | Orden principal recuperado: WhatsApp, cuenta/crear cuenta, carrito, compra/privacidad, términos borrador, teléfono, correo y preparación/despacho |
| Ubicación | Sustituida por texto genérico | Referencias completas, icono y ciudad destacada; visibles al administrador y pendientes al público |
| Novedades | Texto distinto al original | Texto original recuperado; mismos enlaces y SVG, sin suscripciones |
| Páginas adicionales | Dentro de la columna principal | Cinco enlaces en franja secundaria: despachos, devoluciones, garantía, privacidad y contacto; sin borrar ni duplicar páginas |
| Responsive | Dos columnas en tablet/móvil | Conservado a 768/390 px y cuatro columnas a 1440; sin reducir texto para igualar altura |
| Pagos y copyright | Ya adaptados a WooCommerce | Conservados: transferencia previa coordinación y Mercado Pago solo presencial; sin etiquetas de cobros demo ni copyright de maqueta |
| Botón flotante | Enlace configurado | Conservado, sin enviar mensajes |

No se declara igualdad píxel a píxel: el aviso privado de referencia y la franja secundaria añaden altura. El visitante ve una columna Ubicación sin los datos pendientes. Los enlaces nativos y etiquetas comerciales seguras sustituyen las rutas y cobros de demostración.

## Capturas equivalentes

| Ancho | Original | Antes | Después: administrador | Después: visitante sin aprobación |
|---|---|---|---|---|
| 1440 | [Original](footer-recovery/reference-1440.png) | [Antes](footer-recovery/before-1440.png) | [Referencia recuperada](footer-recovery/after-owner-1440.png) | [Ocultamiento](footer-recovery/after-public-1440.png) |
| 768 | [Original](footer-recovery/reference-768.png) | [Antes](footer-recovery/before-768.png) | [Referencia recuperada](footer-recovery/after-owner-768.png) | [Ocultamiento](footer-recovery/after-public-768.png) |
| 390 | [Original](footer-recovery/reference-390.png) | [Antes](footer-recovery/before-390.png) | [Referencia recuperada](footer-recovery/after-owner-390.png) | [Ocultamiento](footer-recovery/after-public-390.png) |

Las capturas son del footer, con ancho real indicado. Para inspeccionar el tema sin modificar el modo próximamente se utilizó un guard temporal limitado al equipo local; la vista de administrador se simuló por solicitud sin crear cuentas ni sesiones persistentes. El guard se retira al terminar. No se contactaron destinos externos desde las pruebas de enlaces.

## Pruebas y límites

Resultados sanitizados: [navegador previo](footer-recovery/before-checks.json), [navegador final](footer-recovery/after-checks.json), [persistencia y permisos](footer-recovery/checks.json).

Corrida final: **60/60 comprobaciones de navegador y 28/28 de persistencia/permisos aprobadas**; corrida previa 7/7. Son comprobaciones del footer, no una nueva certificación completa del flujo de compra.

- Referencia y estado anterior: sin desbordamiento a los tres anchos.
- Administrador y visitante: contenido recuperado, ocultamiento, cuatro columnas, enlaces secundarios, categorías nativas, `tel:`, `mailto:`, número WhatsApp y rutas sociales originales comprobados.
- Enlaces internos probados con acceso de QA al tema real, no solamente a la pantalla próximamente; respuestas HTTP 200 y comprobación de errores PHP renderizados.
- SVG comparados por sus trazados con los de la maqueta; sin duplicación de dirección, preparación, teléfono ni correo en el footer.
- Persistencia en opciones reales, controles reales del Personalizador, aprobación, invalidación por edición y rechazo anónimo comprobados. La configuración original protegida se comparó exactamente con el respaldo.
- No se crearon productos, pedidos ni cuentas. No se repitieron compras ni se alteraron funciones comerciales fuera del alcance.

Incidencias resueltas: la primera recarga local conservó `functions.php` antiguo en OPcache mientras cargaba el nuevo footer y mostró un error PHP de función no definida. Se invalidó la caché del tema completo y se repitió la corrida; se conserva el intento fallido en la evidencia privada. Se corrigieron también la inicialización de la clase del Personalizador en el arnés CLI y el cálculo de aprobación sin valores recién enviados. La corrida final se informa por separado; no se convierte el intento fallido en aprobado. Persiste el aviso previo de `php_imagick.dll` ausente al iniciar PHP de Local; no corresponde a este cambio de footer ni se declara resuelto.

## Respaldo, reproducción y reversión

Antes de intervenir se guardaron el tema real de LocalWP y el JSON privado de opciones/invariantes, con SHA256, en `outputs/footer-recovery-1.2.1/` del workspace original. La sincronización creó además un ZIP previo bajo `outputs/pr-yasofish-1.2.1/outputs/local-backups/`. No se suben opciones, bancos, respaldos ni datos privados a GitHub.

Para reproducir en otro entorno: respaldar tema y opciones; instalar ZIP comprobando SHA256; completar en el Personalizador los campos de la tabla desde el HTML original verificado, manteniendo aprobación desmarcada; revisar autenticado y anónimo; validar con el titular antes de aprobar. El ZIP del tema no contiene la base de datos ni importa estos valores automáticamente.

Para revertir inmediatamente en esta instalación: comprobar el SHA256 de `before.json`, restaurar el tema desde `local-theme-before` o el ZIP previo y restituir únicamente las claves del footer modificadas a sus valores del snapshot (retirar solo las claves nuevas ausentes en él). No restaurar indiscriminadamente todas las opciones después de cambios del propietario; no tocar banco, pedidos, carrito ni páginas. Invalidar OPcache del tema y revisar de nuevo. Los respaldos se conservaron, no se ejecutó una reversión final que descartase el footer solicitado.

Archivos de código: `template-parts/visual.php` (solo función footer), nuevo `template-parts/footer-settings.php`, carga del módulo en `functions.php` y reglas exclusivamente del footer en `assets/css/visual.css`. CI vuelve a validar PHP/JS/SVG y ZIP en el mismo PR borrador. Sin merge, Release ni despliegue.
