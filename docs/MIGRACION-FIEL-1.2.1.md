# Migración fiel GitHub → WordPress — 27 de octubre de 2026

Rama `codex/yasofish-1.2.1`, [PR #1 en borrador](https://github.com/Almostking/yasofishweb/pull/1). Esta revisión sustituye las instrucciones anteriores que ocultaban información de referencia en LocalWP. No autoriza publicar datos de demostración en una tienda comercial.

## Fuente y alcance ejecutado

Se inspeccionaron HTML, CSS, JavaScript, SVG, logo y recursos de la maqueta original en `main`, contrastándolos con los archivos servidos por [GitHub Pages](https://almostking.github.io/yasofishweb/). `index.html`, `style.css`, `brand.css`, `legal.css` y `app.js` publicados coinciden con `main`; véase [proveniencia verificable](migracion-fiel/published-source-verification.json). Una copia local ignorada de `legal.css` era diferente: se usó el archivo publicado de `main`, sin sobrescribir los cambios previos del usuario en la raíz.

Se continúa dentro del tema hijo existente. Los hooks de Storefront conservan la estructura WordPress y sustituyen sus componentes visuales por los originales. El CSS original se delimita al tema y neutraliza anchos, márgenes y estilos heredados; no se añadió otro sitio, constructor, plugin o arquitectura de comercio.

Se recuperaron composición, banner, SVG, logo, tipografía declarada, colores, categorías, tarjetas, encabezado, navegación, bloques editoriales, footer completo, redes y botón flotante. Las rutas se adaptan a páginas y categorías nativas. Los enlaces legales adicionales introducidos en la revisión anterior se retiraron del footer; sus páginas existentes permanecen intactas.

El footer de referencia conserva literalmente los datos y leyendas de GitHub: dirección, horarios, retiro, delivery, preparación, contacto, redes y métodos previstos. Su presencia local **no acredita que el titular los haya aprobado** para producción. No se completaron datos tributarios ni bancarios con ejemplos.

## Dos contextos de datos dentro del mismo tema

| Contexto | Comportamiento |
|---|---|
| LocalWP de revisión | El ajuste `yasofish_reference_mode` está activado. Requiere además entorno WordPress `local` y URL persistente `yasofish.local`. Muestra la maqueta completa con su aviso original de demostración y título local. |
| Ejemplos visuales | Nueve registros originales en arrays y carrito en `sessionStorage`; nunca `WC_Product`, pedidos, existencias comerciales ni pagos. Solo aparecen si no hay productos publicados y el carrito WooCommerce está vacío. Cotización y cuenta de referencia abren los diálogos demostrativos originales, sin enviar datos. |
| WooCommerce nativo | Con catálogo real, el tema usa productos, precios, descuentos, stock, imágenes, búsqueda, categorías, variantes y formularios de WooCommerce. `?yf_live=1` permite revisar esta ruta local; también se puede desmarcar el ajuste en el Personalizador. |
| Hosting comercial | El modo de referencia queda inhabilitado por entorno/dominio aunque se copie el ajuste. Cargar únicamente productos y datos aprobados. No activar ventas durante esta entrega. |

La página Carrito sigue siendo la existente, ID 9, URL `/cart/`, asignada a WooCommerce y contenido exacto `[woocommerce_cart]`. No se sustituyó su contenido ni se reintrodujeron Cart Blocks. El carrito comercial usa una plantilla clásica con cantidades, nonce, cupones, actualización, eliminación y hooks WooCommerce. La cotización vuelve a enlazarse al formulario después de una actualización AJAX del carrito.

La cuenta comercial conserva inicio de sesión real, recuperación, endpoints y registro solo si WooCommerce ya lo permite. Los productos variables utilizan sus formularios nativos. Los productos protegidos conservan su contraseña y no revelan la descripción. El checkout continúa bloqueado antes de confirmar el despacho; order-pay conserva la verificación nativa del correo del invitado y las pasarelas habilitadas.

## Evidencia visual

Se capturaron portada, catálogo, producto, carrito vacío, información y cuenta, a **1440, 768 y 390 px**, tanto antes como después y en GitHub Pages. Navegador Edge/Chromium, escala 1, viewport de 1000 px de alto, página completa. Se congeló la rotación de avisos para comparar el mismo estado; no se retocaron imágenes. Las capturas locales de revisión se hicieron con acceso temporal restringido al equipo; la opción persistente «próximamente» permaneció activa.

Las 18 capturas finales tienen las mismas dimensiones que sus equivalentes originales y no presentan desbordamiento horizontal de página. Se compararon cajas, posiciones, fuentes y colores calculados, además de píxeles. **Esto no demuestra equivalencia visual total.**

| Pantalla | 1440 px: píxeles distintos >5/255 | 768 px | 390 px |
|---|---:|---:|---:|
| Portada | 0,166% | 3,058% | 4,878% |
| Catálogo | 0,361% | 2,507% | 4,186% |
| Producto | 0,666% | 3,565% | 5,276% |
| Carrito vacío | 0,522% | 3,854% | 5,949% |
| Información | 0,185% | 5,970% | 8,096% |
| Cuenta | 0,136% | 3,686% | 5,582% |
| Footer aislado | 0,000% | 4,121% | 5,745% |

[Medición completa](migracion-fiel/pixel-comparison.json), [cajas finales WordPress](migracion-fiel/after-metrics.json) y [cajas de la referencia](migracion-fiel/reference-metrics.json). Los porcentajes cuentan cualquier píxel cuyo canal RGB difiera más de cinco niveles; no son un porcentaje de «diseño completado».

Se corrigieron restricciones de ancho, separación heredada, fuentes/márgenes del contenido informativo, geometría de tarjetas, iconos y enlaces del footer, estilo del enlace Ofertas al adaptar su URL, y una fila indeseada de 18 px causada por los enlaces de accesibilidad. Estos enlaces siguen disponibles al enfocar con teclado.

**Diferencias restantes:** el raster de texto presenta diferencias visibles sobre todo en tablet/móvil aun con dimensiones y colores calculados iguales en los controles inspeccionados. No se atribuyen exclusivamente a antialiasing sin una investigación adicional. El footer de 1440 px sí tiene diferencia cero en la captura comparada. Falta aceptación visual del titular y contraste en otros navegadores/dispositivos físicos. No se certifica paridad de todas las combinaciones posibles de filtros, textos o productos.

Las pantallas comerciales con datos reales requieren adaptaciones técnicas: formularios y errores nativos, actualización/cupón del carrito, cotización protegida, contraseña, verificación de correo de order-pay y registro condicionado a WooCommerce. No se ocultan para imitar botones ficticios. El catálogo real vacío no se rellena con inventario demostrativo. Los textos e información comercial de producción dependen de su aprobación; su validación final y las fotografías reales están pendientes.

### Capturas antes / referencia / después

Todos los archivos están en [la carpeta de evidencia](migracion-fiel/). Ejemplos directos:

| Ancho | Antes | Original | Después |
|---|---|---|---|
| 1440 | [Portada](migracion-fiel/before-home-1440.png) | [Portada](migracion-fiel/reference-home-1440.png) | [Portada](migracion-fiel/after-home-1440.png) |
| 768 | [Portada](migracion-fiel/before-home-768.png) | [Portada](migracion-fiel/reference-home-768.png) | [Portada](migracion-fiel/after-home-768.png) |
| 390 | [Portada](migracion-fiel/before-home-390.png) | [Portada](migracion-fiel/reference-home-390.png) | [Portada](migracion-fiel/after-home-390.png) |

La carpeta incluye los mismos tres estados para catálogo, producto, carrito, información, cuenta y footer, más navegación móvil. Los archivos siguen el patrón `estado-pantalla-ancho.png`. La comparación de carrito con contenido se verificó funcionalmente, además de la captura del carrito visual en memoria. No se presenta una captura vieja del carrito comercial como equivalencia final.

## Pruebas realizadas

El comercio se probó en **una base local separada**, copiada para esta ejecución, con el mismo tema instalado y un servidor local en loopback. No se insertaron fixtures en la base principal de LocalWP. Se bloquearon correo y solicitudes salientes durante las pruebas. No se enviaron WhatsApp, correos a clientes ni pagos.

| Prueba | Resultado y evidencia |
|---|---|
| Regresión nativa | 41/42 controles del primer conjunto final pasaron; el control de order-pay esperaba el formulario antes de verificar el correo del invitado. Se corrigió el criterio del ensayo y se cerró con **3/3** verificaciones específicas, conservando el resultado original. [Conjunto nativo](migracion-fiel/native-browser-results.json), [order-pay](migracion-fiel/order-pay-results.json). |
| Catálogo y producto | Búsqueda, categoría, precio, ofertas, orden, producto simple, añadir y contador AJAX aprobados. |
| Carrito y cotización | Cantidad, subtotal, actualización, formulario reenlazado tras AJAX, validaciones, envío/retiro y URL WhatsApp interceptada aprobados. Checkout redirigido al carrito y BACS no disponible. |
| Variantes y protección | **6/6**, atributos en carrito y descripción protegida no expuesta. [Resultados](migracion-fiel/variation-results.json). |
| Cuenta real | **4/4**, inicio de sesión y endpoints de pedidos, direcciones y datos. [Resultados](migracion-fiel/account-results.json). Cuenta temporal eliminada. |
| Maqueta en memoria | **20/20**, filtros, cantidades, totales, variantes de jig, diálogos, cuenta demostrativa, carrito WC vacío, cero solicitudes de alta comercial y cero mensajes externos. [Resultados](migracion-fiel/reference-results.json). |
| Pedidos y transferencia | Solo en QA: pedidos ficticios de $2.500 quedaron `on-hold` y `pending`, ambos sin pago ni fecha de pago. Se invocó BACS directamente en QA; la pasarela global permaneció desactivada. Ningún banco real. [Resultados](migracion-fiel/order-email-results.json). |
| Correos WooCommerce | Cuatro HTML no vacíos generados e interceptados antes del transporte; cero entregas. No demuestra recepción, SMTP, reputación ni entregabilidad. |
| Integración y controles | **9/9** en `scripts/test-local.php`: versión, sanitización, protección de checkout clásico/Store API, catálogo permitido, BACS y herencia. [Resultados](migracion-fiel/integration-results.json). |
| Separación local/comercial | **10/10** en `scripts/test-reference-local.php`. [Resultados](migracion-fiel/mode-results.json). |
| Rollback de base | Restauración real del SQL en el esquema QA, **52 tablas y 9/9 controles**. No se restauró ni sustituyó la base principal. [Resultados](migracion-fiel/rollback-results.json). |
| Limpieza final | Cuenta, fixtures, esquema QA, conexión privada y guard temporal retirados; productos y pedidos WC principales: cero. BACS apagado y carrito/otras configuraciones iguales al respaldo. [Estado final](migracion-fiel/final-state-results.json). |

Durante la implementación se corrigió un error JavaScript al redibujar el carrito demostrativo durante el evento de pérdida de foco. Las repeticiones posteriores no registraron errores JavaScript. Los fallos iniciales de los ensayos de popup WhatsApp y order-pay fueron expectativas del ensayo: el flujo real navega mediante URL y WooCommerce exige primero comprobar el correo del invitado.

**Advertencia existente del entorno:** PHP 8.3 de LocalWP informa que falta `php_imagick.dll`. No se instaló software ni se alteró su configuración para esta entrega. Las pruebas indicadas pasaron, pero no se certifica la extensión Imagick ni el procesamiento de todas las imágenes futuras.

## Respaldo y reversión

El paquete `yasofish-storefront-child-1.2.1.zip` contiene 31 archivos del tema, sin base de datos ni configuraciones privadas. SHA256: `14eed0690059b195c1be81734cf1a75ac0fe41ec113fe07707dbf3363925735a`. La compilación local aprobó PHP 8.3, sintaxis JavaScript, recursos SVG y controles del paquete. GitHub Actions ejecuta también el lint de los scripts PHP; el estado del último commit debe comprobarse en el PR.

Antes de intervenir se guardaron el tema instalado y SQL completo de la base local (1.711.832 bytes), configuración de carrito, ajustes del tema, BACS, próximamente y páginas. El SQL tiene SHA256 `8410d0526072e73bd3535f03f9aa71495608501e4efc3c0807847819d3de7cd0`; la importación aislada verificó ese hash y las configuraciones restauradas. Los respaldos contienen datos privados y permanecen **fuera de Git y del ZIP del tema** en la carpeta local `outputs/faithful-migration-1.2.1/`. No subirlos a GitHub.

Para revertir esta revisión local: detener pruebas, guardar un nuevo respaldo, restaurar los archivos de `theme-before/` en el mismo tema y retirar únicamente el ajuste `yasofish_reference_mode` añadido por esta ejecución (o restaurar los ajustes guardados). El carrito y BACS no requieren reversión: no cambiaron. El SQL se restauraría solo si fuera necesario recuperar la base completa, en mantenimiento y después de verificar destino y hash; no debe usarse para sobrescribir pedidos posteriores. La restauración ensayada fue exclusivamente sobre QA, que después se eliminó. `scripts/sync-local.ps1` también conserva ZIP previos del tema en `outputs/local-backups/`.

## Repetición en hosting definitivo — pendiente de autorización y entorno

1. Preparar staging del hosting con respaldo verificable de archivos/base; comprobar PHP, WordPress, WooCommerce y Storefront. No copiar SQL ni credenciales locales a GitHub.
2. Instalar el ZIP validado mediante el procedimiento de [operación](OPERACION.md), comparar SHA256 y conservar la versión anterior para rollback.
3. Declarar correctamente el entorno del hosting; comprobar que el modo de referencia está desactivado. Revisar logo, datos comerciales, redes y políticas con el titular. No aprobar automáticamente información tomada de la maqueta.
4. Conservar la página Carrito existente: respaldar contenido y asignación; solo si contiene Cart Blocks sustituir su contenido por `[woocommerce_cart]`, sin cambiar ID, URL ni asignación. Esta conversión **ya estaba hecha** en el LocalWP actual y no se repitió.
5. Cargar catálogo y fotos reales; repetir matriz responsive y pruebas de búsqueda, variantes, stock, carrito, cotización, checkout protegido, cuentas y order-pay en staging.
6. Configurar transferencia y correo únicamente con datos reales autorizados. Mantener BACS desactivado y próximamente activo hasta aprobación comercial. Probar correo con destinatarios controlados, nunca clientes reales.
7. Si falla: detener la actualización, restaurar archivos de la versión anterior y ajustes específicos; restaurar base completa solo con evaluación de datos posteriores. Repetir controles y conservar registro.

**Pendiente:** conexión GitHub→hosting WordPress, staging real, aceptación visual tablet/móvil, catálogo/fotos definitivos, aprobación de direcciones/horarios/políticas e identidad tributaria, banco real, SMTP/entregabilidad y pruebas de pagos autorizadas. No se fusionó el PR, no se publicó Release, no se modificó producción ni se activaron ventas.
