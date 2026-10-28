# Historial

## 1.2.1 — 26 de octubre de 2026

### Migración fiel de la fuente publicada — 27 de octubre

- HTML, CSS, SVG, recursos y contenido contrastados con main y GitHub Pages; estilos delimitados y plantillas nativas. Footer completo en referencia local, sin enlaces adicionales.
- Modo local con ejemplos en memoria, protegido por entorno y dominio; no importa inventario ni pedidos. Sustituye la instrucción anterior de ocultar referencias en LocalWP.
- Cotización reenlazada tras AJAX; se conservan carrito clásico, checkout protegido, variantes, contraseñas y verificación de order-pay.
- Capturas de seis pantallas a tres anchos y diferencias restantes documentadas. Pruebas nativas aisladas, correos interceptados, limpieza y restauración de 52 tablas verificadas. Véase `docs/MIGRACION-FIEL-1.2.1.md`.

### Recuperación del footer original — 27 de octubre

- Información recuperada desde el HTML original y guardada como referencia editable; aprobación explícita vinculada a los valores, con ocultamiento a visitantes hasta validar.
- Orden de Atención al cliente y Novedades restaurado; enlaces legales adicionales conservados en franja secundaria. Capturas antes/original/después a tres anchos y pruebas de permisos documentadas.

### Integración comercial local — 27 de octubre

- Categorías WooCommerce nativas; filtros de búsqueda, categoría, rango de precios y ofertas; orden y descuentos reales. Corregidas colisiones de tarjetas y recomendaciones.
- Idioma oficial es_CL instalado en LocalWP, siete borradores de revisión, enlaces completos y panel administrativo con campos pendientes y aprobación protegida.
- Preparación local con respaldo/rollback y pruebas de catálogo, cuenta, cotización, pedido pendiente, correo interceptado y limpieza documentadas. Pagos y próximamente conservan su estado protegido.

- Migración visual de la maqueta original: encabezado, banner con SVG, beneficios, categorías, tarjetas, editorial, footer e iconos; CSS delimitado y datos WooCommerce reales. Capturas equivalentes, pruebas AJAX/búsqueda y validación SVG incluidas.

- Portada más ancha conservando banner, colores y logo; categorías sin productos y estado vacío de destacados.
- Eliminadas reglas contradictorias del pie, mensajes técnicos al visitante y valores comerciales predeterminados sin confirmar. Se mantienen los valores ya guardados en WordPress.
- Redes sociales y condiciones de despacho editables; retiro y envío sin direcciones ni plazos fijados en el formulario.
- Cotización previa protegida en checkout clásico y Store API. Pago de pedidos confirmados mediante enlace nativo y BACS; no se habilita BACS ni se marca ningún pago automáticamente.
- ZIP reproducible con SHA256, validación PHP/JS, flujo de CI y borrador de Release manual.
- Sincronización LocalWP con respaldo, actualización desde Release y procedimiento de rollback.

Cambios correctivos compatibles: no migraciones de base de datos ni sustitución del tema padre.
