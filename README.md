# 🍳 COOKLY
### Aplicación web de catálogo y gestión de recetas de cocina

![Logo de Cookly](public/IMG/logo.png)

**Proyecto de formación – Análisis y Desarrollo de Software (ADSO)**

**Institución:** Servicio Nacional de Aprendizaje (SENA)  
**Centro:** Centro de la Industria, la Empresa y los Servicios  
**Ficha:** 3407799  
**Estado:** En desarrollo y proceso de aseguramiento de calidad

---

## 📖 Descripción del proyecto

**Cookly** es una aplicación web diseñada para facilitar la organización, creación, búsqueda y administración de recetas de cocina mediante una interfaz moderna, intuitiva y accesible.

El proyecto nace de la necesidad de centralizar las recetas culinarias en un espacio digital donde los usuarios puedan consultar ingredientes, cantidades, tiempos de preparación e instrucciones detalladas.

Cookly permite que cada usuario gestione sus propias recetas y explore las preparaciones compartidas por otros miembros de la comunidad.

Además, cuenta con un sistema de administración encargado de supervisar las recetas antes de su publicación, contribuyendo a mantener la organización y calidad del contenido.

La aplicación está desarrollada utilizando **PHP, MySQL, HTML, CSS y JavaScript**, con una arquitectura cliente-servidor y una base de datos relacional.

---

## 🎯 Objetivo general

Desarrollar una aplicación web que permita registrar, organizar, consultar y administrar recetas de cocina, facilitando el acceso a la información culinaria mediante una plataforma funcional, segura y fácil de utilizar.

## 🎯 Objetivos específicos

- Implementar un sistema de registro, autenticación y gestión de usuarios.
- Permitir la creación, consulta, edición y eliminación de recetas.
- Organizar las recetas mediante categorías e ingredientes.
- Facilitar la búsqueda de recetas por título, categoría o autor.
- Implementar un sistema de aprobación de recetas mediante un administrador.
- Ofrecer estadísticas y reportes sobre la información registrada.
- Incorporar un manual de usuario y documentación del aplicativo.
- Aplicar procedimientos de aseguramiento de calidad para verificar el cumplimiento de los requisitos establecidos.

---

## 🚀 Funcionalidades principales

### 👤 1. Gestión de usuarios

Cookly dispone de un sistema de autenticación y administración de cuentas.

**Funcionalidades:**
- Registro de nuevos usuarios.
- Inicio y cierre de sesión.
- Validación de credenciales.
- Consulta y actualización del perfil.
- Cambio de nombre, correo electrónico y biografía.
- Actualización de la imagen de perfil.
- Cambio de contraseña.
- Eliminación de cuenta.
- Control de acceso mediante roles.

Las contraseñas se almacenan utilizando funciones de hash de PHP.

### 🍲 2. Gestión de recetas

Los usuarios autenticados pueden crear y administrar sus propias recetas.

**Funcionalidades:**
- Crear recetas.
- Consultar recetas propias.
- Editar recetas existentes.
- Eliminar recetas propias.
- Agregar títulos y descripciones.
- Escribir instrucciones de preparación.
- Seleccionar categorías.
- Establecer dificultad, tiempo de preparación y porciones.
- Agregar ingredientes con cantidades y unidades de medida.
- Asociar imágenes mediante enlaces o archivos.

Cuando un usuario crea o modifica una receta, esta pasa al proceso de revisión administrativa antes de publicarse en el catálogo general.

### 🔎 3. Exploración y búsqueda

La aplicación cuenta con una sección denominada **Explorar**, donde se muestran las recetas aprobadas.

Incluye:
- Visualización mediante tarjetas.
- Búsqueda dinámica por título, categoría o autor.
- Resultados mediante JavaScript y peticiones asíncronas.
- Paginación de seis recetas por página.
- Consulta detallada de cada preparación.
- Visualización de imágenes, ingredientes, cantidades e instrucciones.

### 🛡️ 4. Panel de administración

Cookly incorpora un panel exclusivo para usuarios con rol de administrador.

Permite:
- Consultar recetas de distintos usuarios.
- Buscar y filtrar recetas.
- Filtrar por estado, categoría y autor.
- Ordenar resultados.
- Revisar recetas pendientes.
- Aprobar o rechazar publicaciones.
- Modificar el estado de una receta.
- Editar recetas de otros usuarios.
- Eliminar recetas mediante confirmación.
- Detectar determinados cambios simultáneos antes de guardar modificaciones.
- Consultar información estadística de la plataforma.

Las operaciones administrativas de edición y eliminación incorporan controles adicionales de validación.

### 📊 5. Estadísticas y reportes

Cookly proporciona información general sobre los registros del sistema.

Entre sus estadísticas se encuentran:
- Cantidad de usuarios registrados.
- Cantidad de recetas.
- Número de categorías.
- Distribución de recetas por categorías.
- Clasificación por dificultad.
- Usuarios con mayor cantidad de recetas.
- Distribución de recetas según su estado.

El sistema incorpora exportación de reportes en formato compatible con Excel (`.xls`) y una opción para guardar información en PDF mediante la impresión del navegador.

### 📋 6. Registro de eventos

La aplicación incorpora un sistema de registro de eventos y errores.

Los registros se generan en el archivo `logs/sistema.log` y pueden consultarse desde `logs.php` por usuarios administradores.

Se contemplan niveles de registro como:
- INFO.
- WARNING.
- ERROR.
- CRITICAL.

Este mecanismo permite consultar acontecimientos registrados durante la utilización del sistema y facilita el diagnóstico de problemas.

### 📚 7. Manual y documentación

Cookly cuenta con una sección de ayuda y documentación.

Incluye:
- Manual de usuario integrado en `manual.php`.
- Guías sobre registro y acceso.
- Explicación de la gestión de recetas.
- Indicaciones sobre búsqueda, edición y eliminación.
- Preguntas frecuentes.
- Sección de anexos con documentos PDF.
- Página destinada al video de presentación.

**Nota:** La página de video está creada, pero el archivo multimedia referenciado no está incluido en la versión examinada del repositorio.

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Aplicación |
|---|---|
| PHP | Desarrollo de la lógica del servidor |
| MySQL | Almacenamiento de información |
| HTML5 | Estructura de las páginas |
| CSS3 | Diseño y presentación visual |
| JavaScript | Interactividad y funciones dinámicas |
| AJAX / Fetch | Búsqueda dinámica de recetas |
| PDO | Conexión y consultas a la base de datos |
| Google Charts | Visualización de estadísticas |
| Git | Control de versiones |
| GitHub | Almacenamiento y colaboración del código |

El proyecto utiliza PHP sin un framework backend y JavaScript sin frameworks frontend.

---

## 🗄️ Base de datos

Cookly utiliza una base de datos relacional MySQL denominada `cookly`.

El modelo está organizado en cinco tablas principales:

| Tabla | Descripción |
|---|---|
| Usuarios | Información de cuentas, contraseñas y roles |
| Categorias | Categorías disponibles para clasificar recetas |
| Recetas | Información general de cada preparación |
| Ingredientes | Catálogo de ingredientes registrados |
| Receta_Ingredientes | Relación entre recetas e ingredientes, con cantidades y unidades |

### Relaciones principales

- Un usuario puede crear varias recetas.
- Una categoría puede estar asociada a diferentes recetas.
- Una receta puede contener múltiples ingredientes.
- Un ingrediente puede utilizarse en diferentes recetas.
- La tabla `Receta_Ingredientes` permite establecer la relación de muchos a muchos entre recetas e ingredientes.

---

## 📂 Estructura del proyecto

```text
cookly/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── auth.php
│   ├── admin_gestion.php
│   ├── funciones_admin.php
│   ├── funciones_recetas.php
│   ├── funciones_usuarios.php
│   ├── header.php
│   └── footer.php
│
├── public/
│   ├── css/
│   ├── JS/
│   ├── IMG/
│   │   ├── recetas/
│   │   └── avatares/
│   └── docs/
│
├── index.php
├── Login.php
├── Logout.php
├── registro.php
├── perfil.php
│
├── recetas.php
├── receta_crear.php
├── receta_detalle.php
├── receta_editar.php
├── receta_eliminar.php
├── ajax_buscar.php
│
├── admin.php
├── admin_receta_editar.php
├── reportes.php
├── exportar_excel.php
├── logs.php
│
├── manual.php
├── anexos.php
├── video.php
├── 404.php
│
└── README.md
```

### Descripción de los directorios

**`config/`**  
Contiene la configuración de conexión a MySQL.

**`includes/`**  
Agrupa funciones reutilizables para autenticación, usuarios, recetas, administración y elementos comunes de interfaz.

**`public/css/`**  
Contiene las hojas de estilo utilizadas en las distintas páginas.

**`public/JS/`**  
Incluye scripts para interactividad, búsqueda y navegación.

**`public/IMG/`**  
Almacena recursos gráficos, imágenes de recetas y avatares.

**`public/docs/`**  
Contiene documentos relacionados con el proyecto.

**`logs/`**  
Directorio generado por el sistema para almacenar los eventos registrados. No se incluye como directorio inicial en el ZIP.

---

## 💻 Requisitos para ejecutar el proyecto

Para utilizar Cookly en un entorno local se necesita:

- PHP 8.0 o superior.
- MySQL.
- Servidor web compatible con PHP, como Apache.
- Extensión PDO MySQL.
- Navegador web moderno.
- Un entorno local como Laragon o XAMPP.

Se recomienda utilizar Visual Studio Code para revisar y modificar el código y MySQL Workbench para administrar la base de datos.

## ⚙️ Instalación y configuración

### Paso 1. Obtener el proyecto

Descargar el código desde GitHub o clonar el repositorio mediante Git.

Después, ubicar la carpeta `cookly` dentro del directorio del servidor local.

Con Laragon, por ejemplo:

```text
C:\laragon\www\cookly
```

### Paso 2. Preparar la base de datos

Iniciar MySQL y abrir MySQL Workbench.

Crear la base de datos:

```sql
CREATE DATABASE IF NOT EXISTS cookly
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Posteriormente, importar el esquema SQL del proyecto y sus datos iniciales, incluidas las categorías.

**Importante:** El ZIP analizado no incluye el archivo SQL necesario para crear las tablas. Para realizar una instalación desde cero, debe obtenerse ese script del equipo o prepararse a partir del modelo de datos validado.

### Paso 3. Configurar la conexión

Abrir el archivo:

```text
config/database.php
```

Verificar que la conexión corresponda al servidor MySQL que se utilizará.

Los parámetros son:
- Dirección del servidor.
- Nombre de la base de datos.
- Usuario de MySQL.
- Contraseña.
- Juego de caracteres.

Se recomienda utilizar credenciales propias del entorno y no publicar contraseñas reales en GitHub.

### Paso 4. Iniciar el servidor

Ejecutar Apache y MySQL desde Laragon o XAMPP.

Si se utiliza Laragon con la carpeta indicada anteriormente, acceder a:

```text
http://localhost/cookly/Login.php
```

La dirección puede variar según la configuración del servidor.

### Paso 5. Crear una cuenta

Desde la pantalla de registro:

1. Ingresar el nombre.
2. Registrar un correo electrónico válido.
3. Crear una contraseña.
4. Confirmar la contraseña.
5. Acceder mediante la pantalla de inicio de sesión.

Las cuentas nuevas utilizan el rol de usuario. La asignación del rol de administrador se gestiona directamente en la base de datos.

---

## 👥 Roles del sistema

### Usuario

Puede administrar su perfil, crear y gestionar sus recetas, explorar publicaciones aprobadas y consultar el contenido disponible.

### Administrador

Además de utilizar las funciones de usuario, puede revisar y gestionar recetas de la comunidad, modificar sus estados, consultar información estadística y acceder a los registros del sistema.

---

## 🔄 Flujo de publicación de recetas

Cookly utiliza tres estados:

| Estado | Significado |
|---|---|
| Pendiente | Receta que necesita revisión |
| Aceptado | Receta aprobada para aparecer en Explorar |
| Rechazado | Receta no aprobada para su publicación |

El proceso comienza cuando un usuario crea una receta. Esta queda pendiente de revisión y un administrador decide si la aprueba o la rechaza.

Las recetas aceptadas pueden visualizarse en el catálogo general.

Cuando un usuario edita una receta propia, esta vuelve al estado pendiente.

---

## 🔐 Seguridad

El proyecto incorpora diferentes mecanismos de seguridad en su código fuente:

- Contraseñas protegidas mediante `password_hash()` y verificadas con `password_verify()`.
- Consultas preparadas mediante PDO.
- Sesiones de PHP para controlar el acceso.
- Verificación de roles en funciones administrativas.
- Verificación de propiedad para modificar recetas propias.
- Validaciones de formularios.
- Transacciones para determinadas operaciones sobre la base de datos.
- Protección mediante tokens CSRF en las operaciones del módulo administrativo avanzado.
- Registro de determinados errores y eventos.

Estas medidas forman parte de la implementación actual, pero **no significan que la seguridad completa del aplicativo haya sido validada**.

El PAC contempla pruebas adicionales de autenticación, sesiones, cargas de archivos, permisos, protección contra ataques y validación de entradas.

---

## ✅ Aseguramiento de la calidad

Cookly dispone de una Especificación de Requisitos de Software (SRS) y un Plan de Aseguramiento de la Calidad (PAC).

Estos documentos orientan la revisión y verificación del aplicativo.

El proceso de aseguramiento contempla:
- Revisión de requisitos.
- Análisis del código fuente.
- Pruebas funcionales.
- Pruebas de integración.
- Evaluación de seguridad.
- Pruebas de usabilidad.
- Pruebas de rendimiento.
- Verificación de compatibilidad.
- Registro y seguimiento de incidencias.
- Gestión de riesgos y mejora continua.

También se contempla la aplicación de herramientas como diagramas de Ishikawa y Pareto, listas de verificación, matrices de evaluación, hojas de registro, histogramas, diagramas de dispersión, gráficos de control y análisis de riesgos.

Las métricas y los resultados de estas actividades deberán respaldarse con las evidencias correspondientes.

---

## 📚 Documentación del proyecto

El repositorio contiene los siguientes documentos:

| Documento | Ubicación |
|---|---|
| Especificación de Requisitos (SRS) | `public/docs/SRS.pdf` |
| Plan de Aseguramiento de Calidad (PAC) | `public/docs/PAC.pdf` |
| Manual de Usuario | `public/docs/Usuario.pdf` |
| Manual Técnico | `public/docs/tecnico.pdf` |
| Errores y Soluciones | `public/docs/errores.pdf` |

Los documentos también pueden consultarse desde la sección **Anexos** de la aplicación.

La sección de anexos contiene además un enlace previsto para `Herramientas_QA.pdf`, archivo que todavía debe incorporarse al directorio correspondiente.

---

## 🚧 Estado del desarrollo y aspectos pendientes

Cookly dispone de los principales módulos de autenticación, recetas, exploración, administración, estadísticas y documentación implementados en el código.

Sin embargo, todavía se deben revisar o completar los siguientes aspectos:

- Incorporar el script SQL de instalación de la base de datos.
- Completar y documentar las pruebas funcionales y no funcionales.
- Reforzar las validaciones del servidor en las operaciones de usuarios y recetas.
- Revisar la protección CSRF de las operaciones que todavía no la utilizan.
- Revisar los nombres de archivos y rutas para asegurar compatibilidad con servidores Linux.
- Trasladar las credenciales de conexión a una configuración externa segura.
- Documentar y probar copias de seguridad y restauración.
- Incorporar el archivo multimedia del video de presentación.
- Completar los recursos documentales pendientes.
- Validar la instalación del sistema desde cero.

Estas actividades forman parte del proceso de mejora y aseguramiento de calidad.

---

## 🤝 Equipo de desarrollo

Proyecto desarrollado por aprendices del programa **Análisis y Desarrollo de Software (ADSO)** del Servicio Nacional de Aprendizaje (SENA).

| Integrante | Rol |
|---|---|
| Santiago Polania Giraldo | Líder de proyecto y analista |
| Alison Nicol Cabrera Castillo | Diseñadora UX/UI |
| Andrés Felipe Pabón Ardila | Desarrollador backend |
| Santiago Castro Cerón | Desarrollador frontend |
| Kevin Alexis Figueroa Piernagorda | Responsable de pruebas y calidad |

**Instructor:** José de Jesús Motta Vargas.

---

## 📌 Información académica

**Institución:** Servicio Nacional de Aprendizaje – SENA  
**Programa:** Tecnólogo en Análisis y Desarrollo de Software  
**Centro de formación:** Centro de la Industria, la Empresa y los Servicios  
**Ficha:** 3407799  
**Proyecto:** Cookly – Aplicación web de catálogo y gestor de recetas  
**Año:** 2026

---

### 🍽️ Cookly — Organiza, comparte y descubre nuevas recetas.

*Proyecto académico desarrollado como parte del proceso de formación en Análisis y Desarrollo de Software del SENA.*
