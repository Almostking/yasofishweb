# Migración visual de la maqueta a Storefront — 26 de octubre de 2026

## Fuente y alcance

Se inspeccionaron `index.html`, `style.css`, `brand.css`, `legal.css`, `app.js`, `patagonia.svg` y logo del repositorio, la maqueta publicada en https://almostking.github.io/yasofishweb/ y todos los PHP/CSS/JS del tema hijo actual. La imagen adjunta se utilizó como referencia visual, no como instrucciones ni como confirmación de datos comerciales.

Las capturas comparativas se realizaron con Edge/Playwright, viewport de altura 1000 px y anchos 1440, 768 y 390 px, sin zoom. La maqueta publicada y WordPress se abrieron en contextos equivalentes. WordPress se examinó con una excepción temporal de loopback a la pantalla próximamente, sin modificar su opción guardada; se bloqueó correo y red externa durante las pruebas locales. No se desactivó la protección comercial.

## Capturas antes y después

| Ancho | Maqueta publicada | WordPress antes | WordPress después |
|---|---|---|---|
| 1440 px | [Referencia](visual/reference-1440.png) | [Antes](visual/before-1440.png) | [Después](visual/after-1440.png) |
| 768 px | [Referencia](visual/reference-768.png) | [Antes](visual/before-768.png) | [Después](visual/after-768.png) |
| 390 px | [Referencia](visual/reference-390.png) | [Antes](visual/before-390.png) | [Después](visual/after-390.png) |

Las capturas WordPress finales no contienen productos de prueba. Las de referencia conservan los productos y precios de demostración propios de la maqueta y no son datos importados a WooCommerce. Medidas completas: [antes](visual/metrics-before.json) y [después](visual/metrics-after.json).

## Problemas corregidos

- Ancho limitado y espacio vertical de Storefront: portada sin contenedor estrecho, margen de encabezado eliminado de forma delimitada y sidebar retirado en portada y páginas comerciales.
- Encabezado: logo original con marca, buscador real de productos, cuenta real, carrito y navegación equivalente. Se conservan menús asignados; navegación de respaldo comercial si no existen. El contador se actualiza con fragmentos AJAX WooCommerce.
- Banner: composición original de dos columnas, título blanco/amarillo, fondo de ondas, ilustraciones originales de carrete/señuelo, cinta y botones. En móvil cambia a composición vertical como la maqueta.
- Añadidos franja de beneficios, tarjetas de categorías, cuadrícula comercial, bloque editorial con paisaje original, pie de cuatro columnas y redes con los SVG originales. Se ajustaron contraste, etiquetas móviles y botón flotante.
- Tipografía: misma pila `DM Sans, Arial, sans-serif` y `Barlow Condensed, Arial Narrow, sans-serif` del original. La maqueta no suministra archivos de esas fuentes; se conserva su misma resolución por fuentes disponibles. Se retiró la petición de fuentes Storefront que cambiaba el aspecto y añadía una dependencia externa.

El CSS derivado del original vive en `assets/css/visual.css`, siempre bajo `.yf-design`; selectores generales de encabezado, navegación y pie se trasladaron a clases propias. La integración se hace con hooks de Storefront y `template-parts/visual.php`, sin copiar plantillas de WooCommerce ni cargar la aplicación JavaScript de demostración. Los SVG se incluyen con namespace válido y se comprueban en construcción para rechazar scripts/foreignObject/eventos.

## Mediciones y revisión visual

| Ancho | Encabezado referencia / después | Banner antes: x / ancho | Banner después: x / ancho | Banner referencia / después: y y altura |
|---|---|---|---|---|
| 1440 | 124 / 124 px | 43,19 / 1353,63 px | 0 / 1440 px | y 237 / 237; alto 658,64 / 658,64 px |
| 768 | 124 / 124 px | 23,03 / 721,94 px | 0 / 768 px | y 237 / 237; alto 674,56 / 672,56 px |
| 390 | 175 / 175 px | 16 / 358 px | 0 / 390 px | y 293 / 293; alto 748,09 / 748,09 px |

Las capturas se revisaron visualmente, incluidos encabezado, banner, categorías, tarjetas, editorial y pie. No hubo desbordamiento horizontal del documento en ninguno de los tres anchos ni excepciones JavaScript. La navegación horizontal desplazable en móvil sigue el patrón de la maqueta. No se declara equivalencia píxel a píxel.

## Diferencias restantes deliberadas o pendientes

- La franja «maqueta», ofertas demo y textos demo se sustituyeron por identidad real y mensajes de coordinación, sin anunciar ventas habilitadas. El banner no anuncia ofertas inexistentes.
- WooCommerce tiene cero productos publicados. El área comercial muestra cuatro tarjetas de categoría con aviso explícito de catálogo en preparación, sin precios ni botones ficticios de compra. Cuando existen destacados reales, se renderizan mediante el shortcode nativo de productos de WooCommerce y se oculta el estado vacío.
- Las categorías enlazan a términos reales cuando existen; si aún no existen, usan búsqueda real de producto por categoría, sin crear términos ni inventario en la base de datos.
- No se importaron dirección, horarios, teléfono alternativo o correo desde la demo a los ajustes comerciales. El pie usa solo valores guardados en WordPress; faltando ubicación ofrece coordinación. Sus enlaces legales aparecen solo si hay páginas reales asignadas. Por eso el pie es más corto: referencia / WordPress 638,92 / 515,55 px en escritorio; 874,69 / 649,22 px en tablet; 1133,86 / 814,05 px en móvil.
- Las redes tienen los destinos públicos ya presentes en el repositorio original y siguen siendo editables en el Personalizador. El WhatsApp usa el número comercial previamente confirmado, sin fijarlo en el código.
- Diferencia de 2 px de altura del banner a 768 px; textos reales distintos también cambian los saltos de línea en zonas comerciales. Las etiquetas de categoría móviles se ajustaron para evitar recorte.
- El diseño con catálogo, fotografías y textos comerciales definitivos requiere revisión adicional al cargarlos; no se certifican de antemano todos esos contenidos ni todos los navegadores.

## Pruebas funcionales después de la migración

**Aprobadas:** diez pruebas de navegador de añadir dos productos locales temporales, actualizar cantidad a 3, persistencia, total CLP 6.250, carrito clásico y formulario, redirección de checkout a cotización, validación vacía, dirección requerida, retiro y consulta WhatsApp interceptada sin envío. Cero excepciones JavaScript.

**Aprobadas además:** dos destacados temporales renderizados como tarjetas WooCommerce nativas; desaparición del estado vacío; actualización AJAX del contador a 1 tras agregar un producto; búsqueda de productos real devuelve ambos productos. Se eliminaron después los cuatro productos temporales de las dos rondas y sus dos sesiones de carrito. No se crearon pedidos en esta ejecución.

**Aprobadas en servidor:** nueve comprobaciones de versión, sanitización, guardas de checkout clásico/Store API, catálogo permitido, BACS como único gateway admitido, desactivación BACS preservada y Storefront como padre. Construcción valida cuatro PHP del tema, tres JavaScript, SVG y ZIP/checksum; CI repite construcción y lint de scripts de mantenimiento.

**Estado final verificado:** carrito clásico permanente en página 9 y URL /cart/, WhatsApp comercial configurado, cero productos y pedidos, BACS apagado, próximamente activo y guard temporal retirado. No se cambiaron ajustes comerciales para la migración; solo se prepararon/restauraron fixtures locales para probar.

**No repetidas en esta revisión visual:** transacción completa order-pay/BACS y generación de correo (aprobadas en la ronda funcional anterior); entrega de correo real, WhatsApp real, verificación bancaria, impuestos/boleta, logística, WPCS, pruebas de carga y hosting definitivo. No se enviaron mensajes ni pagos reales.

## Instalación y rollback

Versión conservada en 1.2.1 porque la revisión continúa en el mismo PR sin publicar. El ZIP incluye el nuevo diseño y `screenshot.png`. Para LocalWP se utiliza el script de sincronización con SHA256 y respaldo. La copia del tema anterior a la migración se conserva en `outputs/visual-1.2.1/theme-before/` del espacio original; las sincronizaciones también generan ZIP de respaldo. Revertir el código con ese respaldo no requiere revertir la base de datos ni el carrito clásico.

No se modifica la maqueta publicada, no se fusiona el PR, no se publica Release y no se despliega producción.
