# Manual de Usuario de Cookly

Entrega del 9 de noviembre de 2026. Edición 2.0 del manual; la aplicación no declara una versión propia.

## Abrir el manual

1. Utiliza la carpeta `cookly` actualizada en tu instalación PHP/MySQL, por ejemplo en Laragon.
2. Conserva tu configuración de conexión actual en `config/database.php`.
3. Inicia sesión y selecciona **Manual de Usuario** en el menú.
4. También puedes abrir `manual.php` directamente sin iniciar sesión.
5. Selecciona **Descargar PDF completo** para descargar el documento con todas las capturas. El PDF ya viene incluido; no necesitas instalar generadores en el servidor.

El manual no crea tablas ni requiere importar un SQL o modificar la base de datos.

## Archivos nuevos

| Archivo o carpeta | Función |
| --- | --- |
| `includes/manual_contenido.json` | Fuente común del texto del manual web y del PDF, con descripciones de las imágenes. |
| `public/css/manual.css` | Diseño adaptable, impresión, índice y visor de imágenes. |
| `public/JS/manual.js` | Buscador sin distinción de tildes, índice móvil, navegación y ampliación accesible de capturas. |
| `public/IMG/manual/` | 28 capturas reales, recortadas y nombradas por función. |
| `public/IMG/manual/capturas.json` | Procedencia, dimensiones y huellas de los archivos de captura. |
| `public/docs/Manual_de_Usuario_Cookly.pdf` | Documento completo con índice enlazado, marcadores y capturas. |
| `manual_descargar.php` | Descarga directa y completa del PDF; no consulta MySQL. |
| `public/fonts/` | Nunito local y su licencia OFL, para conservar la tipografía sin depender de Google Fonts. |
| `tools/manual/generar_pdf.py` | Generador opcional del PDF desde el mismo contenido del manual. |
| `docs/verificacion_manual.json` | Resultados de las comprobaciones realizadas. |

## Archivos existentes ajustados

- `manual.php`: reemplaza el manual básico por la guía completa, usando el encabezado y pie existentes.
- `includes/header.php`: nombre completo del enlace, carga opcional de estilos, clase de página y navegación de ayuda sin sesión.
- `includes/footer.php`: carga opcional del JavaScript del manual.
- `public/css/main.css`: fuente local y ajuste del encabezado para acomodar el enlace en pantallas pequeñas.
- `public/css/auth.css`: fuente local.
- `Login.php` y `registro.php`: rutas a la carpeta real `public/css`, con minúsculas.
- `includes/auth.php`, `registro.php`, `perfil.php` y `Logout.php`: referencias a `Login.php` y `Logout.php` con las mayúsculas reales de los archivos.

Se conservaron las consultas y los procesos de registro, verificación de credenciales, gestión de recetas, moderación y perfil. Las correcciones de mayúsculas son necesarias para que las rutas funcionen también en servidores Linux. No se renombraron archivos de acceso, evitando colisiones en Windows.

## Funcionalidades comprobadas y alcance

| Función encontrada | Tratamiento en el manual |
| --- | --- |
| Registro, inicio y cierre de sesión | Pasos, capturas y pruebas reales. |
| Mis recetas y estados | Diferencia entre Pendiente, Aceptado y Rechazado. |
| Creación, consulta, edición y borrado | Pasos y capturas; se verificaron cancelar y aceptar el borrado. |
| Explorar y buscador AJAX | Búsqueda por título, categoría o autor; paginación de 6 recetas y máximo de 20 resultados de búsqueda. |
| Categorías | Selección del catálogo existente. No se inventa una pantalla de administración. |
| Ingredientes | Gestión desde las filas de cada receta. No se inventa una pantalla de catálogo. |
| Perfil | Edición de datos, foto, biografía, cambio de contraseña y eliminación de cuenta. |
| Admin | Consulta y aprobación o rechazo de recetas pendientes. |
| Reportes y Excel | Estadísticas y exportación existente; el PDF del panel usa impresión del navegador. |
| Logs | Acceso de administrador a los eventos de la instalación. |
| Recuperación de contraseña | No existe. Se explica el cambio de contraseña con sesión iniciada y la necesidad de contactar al responsable si se olvidó. |
| Video | La página existe, pero no se incluyó `public/video_presentacion.mp4`; no se afirma que pueda reproducirse. |
| Favoritos | No aparecen en la interfaz revisada; no se documentan como función disponible. |

### Entorno de prueba

Se revisaron los archivos del ZIP, excluyendo metadatos internos de Git del análisis funcional. Como no se adjuntó un script SQL, se ejecutó el PHP real sobre una base **local y aislada** con esquema derivado de las consultas. Se utilizó PHP 8.3 y MariaDB 10.11 compatible con las consultas MySQL del proyecto, además de Playwright/Chromium para la navegación. Esto verifica los flujos en esa instalación; no sustituye una comprobación contra el esquema real de tu servidor MySQL.

No se utilizaron credenciales de producción ni se modificó una base de datos real. Todos los nombres, correos y recetas de las capturas son de demostración. Las fotografías utilizadas en esas recetas ya venían en los recursos del proyecto. No se añadieron cuentas de prueba ni datos de prueba a la base original.

La configuración entregada en `config/database.php` conserva exactamente los bytes del archivo adjunto. También se conservaron, sin cambios, los archivos de funciones de recetas y usuarios, las páginas de recetas, el panel de administración, los reportes y el JavaScript general.

### Limitaciones observadas

- El cuadro nativo de confirmación del navegador no puede incluirse en las capturas de página del navegador disponible. Su mensaje, la cancelación y la aceptación sí se comprobaron. El manual lo explica y muestra el botón y el resultado reales, sin recrear la confirmación.
- Si se guarda una imagen como ruta relativa `public/...`, los campos existentes de tipo URL de perfil y edición pueden pedir una dirección completa al guardar otra vez. Se documenta cómo copiar la dirección completa de la imagen. No se modificó esa lógica de la aplicación.
- El comportamiento de borrado en cascada de la base real depende de sus claves foráneas. El SQL original no estaba en el ZIP y no fue posible verificarlo. La guía conserva la advertencia que muestra la interfaz al eliminar una cuenta.
- Las gráficas existentes usan Google Charts. Se cargaron los recursos reales del servicio durante las capturas; requieren conectividad en la instalación del usuario.

## Mantener el documento

1. Edita `includes/manual_contenido.json` para cambiar los textos. No contiene HTML ejecutable: la página escapa sus valores antes de mostrarlos.
2. Para actualizar una captura, toma una imagen real en una instalación con datos de prueba, guárdala en `public/IMG/manual/` y actualiza su nombre, descripción y dimensiones en el JSON.
3. Regenera el PDF cuando cambie el contenido o una captura:

```bash
python -m pip install reportlab pillow
python tools/manual/generar_pdf.py
```

El generador usa las fuentes incluidas, añade un índice enlazado y marcadores y escribe `public/docs/Manual_de_Usuario_Cookly.pdf`. Es una herramienta de desarrollo: no debe ejecutarse para cada descarga.

El manual web y su PDF no necesitan conectarse a MySQL. El resto de Cookly conserva sus requisitos originales.
