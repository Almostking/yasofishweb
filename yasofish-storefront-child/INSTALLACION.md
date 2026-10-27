# Instalación y preparación comercial

El paquete extiende Storefront; no copia el tema padre ni modifica archivos de WooCommerce. Instala Storefront y WooCommerce por separado en un sitio de pruebas antes de activar este tema.

## Opción económica viable seleccionada

Producción utilizará WordPress + WooCommerce + Storefront + este tema hijo en un hosting compartido administrado con MySQL o MariaDB integrado al plan. No requiere una aplicación React, Supabase Pro ni una base de datos contratada por separado. La demostración estática que acompaña al tema solo sirve para mostrar el diseño; no procesa ventas.

Para empezar, mantén WooCommerce y Storefront sin costo, usa funciones nativas y evita extensiones pagadas hasta que exista una necesidad comprobada. Prioriza un plan con HTTPS, copias de seguridad que se puedan restaurar, soporte WordPress y precio de renovación transparente. El hosting, dominio, correo, extensiones opcionales, mantenimiento y comisión de Mercado Pago no están cubiertos por el costo del tema/plataforma.

Los $31.410 CLP por año de la comparación recibida se consideran una referencia preliminar, no un costo total confirmado. Antes de contratar, comprobar renovación, IVA, dominio, SSL, correo, respaldo y recursos incluidos; las comisiones de pago dependen de cada transacción. WooCommerce publica el núcleo como gratuito y sin comisión de plataforma, pero cada proveedor puede cobrar su servicio.

Requisitos recomendados actuales de referencia: WordPress 6.9+, PHP 8.3+, MySQL 8.0 o MariaDB 10.6+, HTTPS y al menos 256 MB de memoria para WordPress. Verifica los requisitos vigentes de WooCommerce y del hosting antes de instalar.
## Contenido listo en el tema

- Identidad azul YASOFISH con logo adaptable desde Identidad del sitio.
- Portada responsive con categorías reales y productos destacados de WooCommerce.
- Apariencia coordinada para catálogo, ficha, carro, checkout y avisos de pedido.
- Recomendaciones al pie de la ficha: hasta cuatro productos que comparten categoría con el producto actual, para mantenerlas dentro del equipo de pesca.
- Enlace de WhatsApp por producto y asesoría con texto prellenado, habilitado solo al configurar un teléfono válido.
- Cotización del carro con modalidad de entrega, nombre de quien recibe, región, comuna, ciudad/localidad y dirección; casa/departamento y referencias son opcionales. La dirección se solicita solo para delivery o envío a regiones. Al enviarla, se abre WhatsApp con el mensaje preparado y el cliente decide si lo envía.
- El formulario repite las condiciones informadas: preparación de 2 a 4 días; delivery local desde las 18:00 h con disponibilidad y costo por confirmar; envío regional cotizado por destino; retiros con dirección, horario y coordinación publicados. La solicitud no cobra: después de aceptar stock, total y plazo, YASOFISH crea el pedido, el cliente paga por transferencia y el abono se verifica antes de preparar y despachar.
- En esta etapa no se ofrece factura ni se solicita RUT o razón social en el formulario. Configurar y probar la emisión de boleta electrónica antes de habilitar ventas online.
- Aviso superior, textos de portada, teléfono y WhatsApp separados y editables desde el Personalizador; WhatsApp queda apagado hasta confirmar su número.
- Pie horizontal con categorías de pesca, Instagram, Facebook, TikTok y WhatsApp, dirección editable y solo los métodos habilitados en WooCommerce; añade enlaces a las páginas de privacidad y términos una vez publicadas.
- Carrito, pagos, stock, impuestos, pedidos y despachos delegados a WooCommerce y sus extensiones.
- No hay credenciales de proveedor, bases de datos ni llamadas de pago personalizadas. El formulario de cotización no envía datos al servidor; al elegir abrir WhatsApp, incluye en el mensaje el destino y el carro. El usuario revisa y envía el mensaje desde WhatsApp.

## Cuentas, cartera de clientes y avisos

- **Cuentas cliente:** WooCommerce administra registro, acceso, direcciones e historial de pedidos en “Mi cuenta”. El tema incluye un botón visible “Iniciar sesión / Crear cuenta” que conduce a esa página. En WooCommerce → Ajustes → Cuentas y privacidad, habilitar registro en Mi cuenta y enlaces para crear contraseña; publicar la política de privacidad y su aviso en registro y checkout. Los compradores invitados pueden tener órdenes sin que se cree una cuenta.
- **Cartera de clientes:** usar WooCommerce → Clientes para revisar compradores, datos asociados a sus pedidos y compras. Los registros de compradores pertenecen a WooCommerce; el tema no crea una base de clientes paralela.
- **Acceso del equipo:** crear cuentas separadas. Reservar Administrador al propietario y asignar Gestor de tienda a quien gestione catálogo, pedidos y clientes. Nunca usar una cuenta cliente compartida como acceso de administración; exigir MFA al personal administrativo.
- **Ofertas por correo:** recomendación inicial: MailPoet para WooCommerce como opción futura, no necesaria para abrir la tienda. Si se activa, configurar la casilla de suscripción en checkout, dejarla desmarcada por defecto, habilitar confirmación de suscripción y enlace para darse de baja. Comprar no significa suscribirse a promociones. Sincronizar en marketing solo a quienes tengan el consentimiento correspondiente; excluir al resto.
- **Aviso de reposición:** opción futura: extensión de avisos de reposición (Back In Stock Notifications for WooCommerce). No es requisito para la primera etapa; evalúa su plan y privacidad antes de activarla. Permite apuntarse a un producto agotado, avisar cuando vuelve, gestionar bajas y mostrar demanda; se integra con Mi cuenta y también admite invitados. Mantener visibles los agotados para que puedan apuntarse. El aviso de ese producto debe seguir separado de la suscripción general de ofertas.
- **Datos y privacidad:** definir finalidad y plazo de conservación, limitar permisos del equipo, configurar privacidad y retención en WooCommerce, y documentar cómo atender solicitudes de acceso o eliminación. La Ley chilena 21.719 tiene vigencia prevista para el 1 de diciembre de 2026; dejar revisados el aviso y los procesos con asesoría local antes del lanzamiento.

MailPoet y Back In Stock Notifications son extensiones; hay que revisar sus planes, términos, compatibilidad y destino de datos al contratar/configurar. No se han instalado ni conectado servicios externos en este paquete.

## Completar cuando estén los datos

- [ ] Verificar en Apariencia → Personalizar → YASOFISH Chile la dirección, teléfono, correo, horarios y redes; confirmar los días de operación del retiro en Alto Hospicio.
- [ ] Hosting compartido administrado y dominio definitivo; confirma MySQL/MariaDB, HTTPS, recursos, soporte WordPress, copias restaurables y precio de renovación antes de contratar.
- [ ] WordPress, Storefront y WooCommerce instalados y con actualizaciones automáticas o un plan de mantenimiento.
- [ ] Logo vectorial o PNG de alta resolución con autorización de marca.
- [ ] Productos: nombres, SKUs, categorías, precios CLP, fotos autorizadas, dimensiones y peso, stock y variaciones.
- [ ] Mantener categorías de WooCommerce dentro del rubro pesca (cañas, carretes, señuelos y accesorios); clasificar cada producto para que las recomendaciones relacionadas muestren equipo de pesca.
- [ ] Confirmar con SII/asesoría contable el régimen aplicable y habilitar la emisión de boleta electrónica antes de iniciar ventas; por ahora no se ofrecen facturas.
- [ ] Flujo preparado en el tema: el carro solicita modalidad, nombre de quien recibe y destino; para delivery/envío regional también pide calle y número. Casa/departamento y referencias son opcionales. El checkout normal se detiene para evitar cobrar sin costo de envío confirmado. Tras aceptar costo y plazo, crear el pedido WooCommerce con el total final y enviar el enlace de pago. Configurar el WhatsApp comercial en el Personalizador.
- [ ] Transferencia online: en WooCommerce → Ajustes → Pagos → Pagos sin conexión, activar “Transferencia bancaria directa” (BACS), ingresar la cuenta solo desde el escritorio protegido y revisar los textos en la página de pedido y correo. WooCommerce no confirma depósitos: comprobar el abono real en BCI antes de pasar de “En espera” a “Procesando”. Marcar “Completado” tras despachar o entregar.
- [ ] Mercado Pago: por ahora se usa exclusivamente en el terminal de la tienda física. No instalar ni activar un gateway online de tarjeta hasta que el titular decida sumar ese canal; si se incorpora después, probarlo en staging con credenciales del titular.
- [ ] Despacho: confirmar cobertura por comuna, tarifas/vías y plazos de transporte; hoy el plazo y costo se cotizan según destino. Confirmar hora de corte, días del delivery desde las 18:00 h, días del retiro coordinado en Alto Hospicio y vigencia de cada cotización. Mantener cotización manual antes del pago donde no exista tarifa automática confiable.
- [ ] WhatsApp: confirmar si el teléfono informado también recibirá mensajes, luego configurar el número y revisar el texto prellenado.
- [ ] Cuentas cliente: habilitar registro de WooCommerce, recuperación mediante enlace de contraseña y avisos de privacidad; probar registro, restablecimiento y acceso a pedidos.
- [ ] Equipo: definir personas, cuentas individuales, rol mínimo necesario y MFA para cuentas administrativas.
- [ ] Ofertas: instalar/conectar la solución de correo elegida, configurar suscripción opcional, confirmación, bajas y política de privacidad. No importar clientes como suscritos si no hay prueba de consentimiento.
- [ ] Reposición: elegir extensión de avisos, definir cuánto tiempo conservar solicitudes y probar alta, aviso al reponer y baja.
- [ ] Políticas de privacidad, cambios, devoluciones, garantías, términos de venta e información tributaria revisadas para Chile.
- [ ] Correo transaccional desde dominio propio, SPF/DKIM/DMARC y prueba de pedidos/cancelaciones.
- [ ] Usuarios con mínimos privilegios y MFA para administradores; eliminar cuentas y plugins sin uso.
- [ ] Copias de seguridad fuera del hosting y restauración comprobada.
- [ ] Prueba completa en staging: producto agotado, cupones, móvil, checkout, webhooks, correos, impuestos, despacho, error/abandono y devolución.
- [ ] Revisión final de headers, TLS, permisos del hosting, registros y recuperación del sitio.

No activar la transferencia real hasta completar las pruebas de pedidos, instrucciones, correos y conciliación en el hosting definitivo. Los datos bancarios se configuran solo dentro del escritorio protegido de WooCommerce, no en el tema, los archivos de demostración, GitHub ni capturas públicas. Las medidas de hosting, WordPress y las cuentas de pago se configuran en esas plataformas, no en un tema visual.

El flujo operativo acordado para preparar la configuración está descrito en [PROCEDIMIENTO-VENTA-Y-DESPACHO.md](PROCEDIMIENTO-VENTA-Y-DESPACHO.md). Completar esa prueba en staging y cerrar los datos de despacho antes de habilitar el checkout con pagos.

## Privacidad y obligaciones antes de vender

El tema evita formularios propios, no envía datos a terceros y no guarda credenciales de Mercado Pago. Usa los flujos de WooCommerce para cuentas, pedidos y pagos. Esto no sustituye la seguridad del hosting: usa HTTPS, actualiza WordPress/Storefront/WooCommerce/extensiones, limita roles, activa MFA administrativo, protege copias y valida los webhooks del proveedor de pagos.

La tienda real deberá informar claramente identidad del proveedor, características esenciales, precio total, stock, despacho, condiciones y canales de contacto antes de confirmar una compra, y enviar confirmación escrita del contrato. Revisar las reglas de garantía legal y retracto aplicables, publicar las políticas correspondientes y completar revisión legal chilena. La Ley 21.719 entra en vigencia el 1 de diciembre de 2026; preparar inventario de datos, finalidades, permisos, retención, ejercicio de derechos y contratos con proveedores antes de esa fecha. La ruta informativa de la maqueta no reemplaza estas políticas.
