# Administración de YASOFISH sin editar código

## Punto de partida

LocalWP conserva el carrito clásico en su página existente, BACS apagado y modo próximamente. Las categorías Cañas, Carretes, Señuelos y Accesorios ya existen. No hay inventario ficticio permanente. Los textos legales son borradores visibles en el entorno local, no contratos aprobados.

En **Apariencia → Preparación YASOFISH** se muestra qué información comercial está guardada y qué falta, con accesos a la administración nativa. Solo usuarios con permisos de administración del tema pueden acceder.

## Trabajo cotidiano

| Tarea | Dónde se administra |
|---|---|
| Productos y fotografías autorizadas | Productos → Todos los productos / Añadir producto; Biblioteca de medios |
| Precio normal y rebajado | Ficha de producto → Datos del producto → General |
| Stock y SKU | Datos del producto → Inventario; no desactivar inventario por editar el tema |
| Categorías | Productos → Categorías; conservar los slugs canas, carretes, senuelos y accesorios para los accesos principales |
| Variaciones reales | Datos del producto → Producto variable → Atributos y Variaciones; no importar pesos o colores de la demo como datos reales |
| Pedidos y estados | WooCommerce → Pedidos |
| WhatsApp, correo, contacto, días, horarios, retiro, zonas y textos de despacho | Apariencia → Personalizar → YASOFISH Chile |
| Redes y avisos | La misma sección del Personalizador |
| Información y políticas | Páginas → seleccionar la página existente y editar con WordPress |
| Zonas y tarifas operativas | WooCommerce → Ajustes → Envío; el texto del Personalizador no calcula tarifas por sí mismo |
| País, CLP e inventario general | WooCommerce → Ajustes |
| Banco | WooCommerce → Ajustes → Pagos → Transferencia bancaria; datos solo en la administración protegida, nunca en GitHub |

No agregar productos sin precios/fotos/stock confirmados. Priorizar imágenes WebP autorizadas de tamaño adecuado; WooCommerce genera tamaños responsive. Se detectó soporte WebP con el editor disponible, pero la carga masiva y fotografías finales aún requieren pruebas.

## Pedidos con coordinación previa

El cliente agrega productos y solicita cotización por WhatsApp. El responsable confirma stock, costo/plazo de entrega y total, y obtiene aceptación. Entonces crea el pedido con todos los importes acordados y envía el enlace nativo de pago. Una solicitud de cotización no equivale a un pedido pagado.

Con BACS habilitado en el futuro y debidamente configurado, el pedido permanece en espera hasta verificar el abono. No confirmar un pago solo por una captura. Marcar Procesando únicamente tras comprobar el ingreso; preparar y registrar entrega/despacho antes de completar el pedido. Mercado Pago corresponde al terminal presencial, no a una pasarela de este sitio.

## Revisión de borradores

Las siete páginas locales llevan un aviso de borrador. Tienen estado técnico `publish` exclusivamente para poder revisar los enlaces en LocalWP protegido; llevan metadatos de revisión y no se consideran aprobadas. El tema oculta sus enlaces fuera de yasofish.local para visitantes y bloquea la visualización pública de esos borradores. No copiar una base de datos local directamente sobre la tienda comercial.

Cada página de revisión tiene una caja **Revisión comercial YASOFISH** en el editor. Tras validar identidad, datos, condiciones y asesoría legal cuando corresponda, el titular puede marcarla como revisada/aprobada y editar el aviso de borrador. La casilla verifica permisos y nonce, no activa ventas ni BACS. No se marcó ninguna página real como aprobada en esta ejecución.

Antes de lanzar: revisar las siete páginas, asignar privacidad/términos definitivos en WordPress/WooCommerce y probar su aceptación y correos. No se asignaron automáticamente borradores como contrato definitivo del checkout.

## Repetir la preparación en una copia local

1. Instalar tema, padre Storefront y WooCommerce. Mantener una copia de archivos y base de datos.
2. Instalar idioma **Español de Chile** y traducciones oficiales desde WordPress. En esta auditoría se usaron paquetes oficiales para WordPress 7.1.3, WooCommerce 11.2.1 y Storefront 4.6.2.
3. Definir `YASOFISH_WP_ROOT` con la ruta local y `YASOFISH_COMMERCE_BACKUP` con un archivo nuevo fuera de la carpeta pública. Ejecutar `php scripts/prepare-local-commerce.php` usando PHP/configuración de LocalWP. Requiere el repositorio completo con `docs/legal/`; el ZIP del tema no importa contenido ni cambia la base de datos.
4. El script registra opciones/títulos originales, crea únicamente categorías faltantes y páginas de revisión inexistentes, conserva IDs/URLs de tienda/carrito/checkout/cuenta, cambia sus títulos a español y configura los contactos identificados. No habilita pagos, envío ni impuestos.
5. Para revertir inmediatamente la preparación, antes de editar esas páginas o asignarles productos, ejecutar el mismo script con el mismo archivo de respaldo y `--rollback`. Verifica SHA256, restaura opciones y títulos y elimina solo las páginas de revisión registradas y categorías nuevas vacías. Revisar el registro antes de una reversión posterior; preservar cambios de contenido hechos por el propietario.

El script rechaza dominios distintos de yasofish.local. Para hosting definitivo se necesita una migración aprobada: crear categorías desde WooCommerce, instalar idioma oficial, importar los contenidos como **borradores**, revisarlos y aprobarlos desde el editor. No aplicar el respaldo local sobre pedidos reales. Seguir además [carrito clásico y rollback](CARRITO-CLASICO-1.2.1.md) y [operación](OPERACION.md).
