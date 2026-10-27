# Historial

## 1.2.1 — 26 de octubre de 2026

- Migración visual de la maqueta original: encabezado, banner con SVG, beneficios, categorías, tarjetas, editorial, footer e iconos; CSS delimitado y datos WooCommerce reales. Capturas equivalentes, pruebas AJAX/búsqueda y validación SVG incluidas.

- Portada más ancha conservando banner, colores y logo; categorías sin productos y estado vacío de destacados.
- Eliminadas reglas contradictorias del pie, mensajes técnicos al visitante y valores comerciales predeterminados sin confirmar. Se mantienen los valores ya guardados en WordPress.
- Redes sociales y condiciones de despacho editables; retiro y envío sin direcciones ni plazos fijados en el formulario.
- Cotización previa protegida en checkout clásico y Store API. Pago de pedidos confirmados mediante enlace nativo y BACS; no se habilita BACS ni se marca ningún pago automáticamente.
- ZIP reproducible con SHA256, validación PHP/JS, flujo de CI y borrador de Release manual.
- Sincronización LocalWP con respaldo, actualización desde Release y procedimiento de rollback.

Cambios correctivos compatibles: no migraciones de base de datos ni sustitución del tema padre.
