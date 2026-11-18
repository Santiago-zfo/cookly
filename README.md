# 🍳 Cookly
### Catálogo y gestión de recetas de cocina

![Logo de Cookly](public/IMG/logo.png)

Proyecto de formación · ADSO · SENA · Ficha 3407799 · En desarrollo

---

## ¿Qué es?

**Cookly** es una aplicación web para crear, organizar, buscar y administrar recetas de cocina. Cada usuario gestiona sus propias recetas y explora las preparaciones compartidas por la comunidad; un administrador revisa y aprueba las publicaciones antes de que aparezcan en el catálogo.

## Funcionalidades

- Registro, inicio/cierre de sesión y perfil (datos, avatar y contraseña).
- Crear, consultar, editar y eliminar recetas con categorías, ingredientes e imágenes.
- Exploración con búsqueda dinámica (AJAX) y paginación.
- Panel de administración: aprobación, filtros, edición, eliminación con confirmación y control de cambios simultáneos.
- Estadísticas, exportación a Excel y reporte imprimible en PDF.
- Manual de usuario, anexos documentales y video de presentación.
- Registro de eventos en `logs/sistema.log`.

## Tecnologías

PHP 8 · MySQL · PDO · HTML5 · CSS3 · JavaScript (AJAX) · Git  
Arquitectura MVC propia, sin frameworks backend ni frontend.

## Requisitos

- PHP 7.4 o superior (probado con 8.3).
- MySQL o MariaDB con extensión `pdo_mysql`.
- Apache, Laragon o XAMPP.

## Instalación

1. Coloca la carpeta del proyecto en `C:\laragon\www\cookly`.

2. Importa `Script BD (1).txt`: crea la base de datos, las tablas, datos de prueba y procedimientos almacenados.

   > **Antes de importar:** el script está envuelto en `/* ... */`. Retira esas dos marcas o no se ejecutará nada. Las sentencias `ALTER TABLE` asumen una base de datos vacía.

3. Ajusta la conexión en `config/database.php`. Por defecto usa `root` sin contraseña; no subas credenciales reales al repositorio.

4. Arranca Apache y MySQL, y abre:

   ```text
   http://localhost/cookly/public/pages/Login.php
   ```

   También funciona `http://localhost/cookly/`, que redirige a la página de inicio.

5. Las cuentas nuevas tienen rol `usuario`. Para dar permisos de administrador, cambia el campo `rol` a `admin` en la tabla `Usuarios`.

## Estructura

```text
cookly/
├── app/
│   ├── Core/          rutas, sesión, Controlador y View
│   ├── Models/        lógica de negocio y acceso a datos (PDO)
│   ├── Controllers/   acciones por módulo
│   └── Views/         plantillas HTML y layout común
├── config/database.php
├── public/
│   ├── pages/         puntos de entrada públicos (URLs estables)
│   ├── css/ JS/ IMG/ docs/
│   └── video.mp4
├── index.php          redirige a public/pages
├── logs/sistema.log
└── Script BD (1).txt
```

Los archivos de `public/pages/*.php` son controladores delgados: cargan `app/bootstrap.php` y delegan en un controlador. Las URLs no han cambiado.

## Documentación

| Documento | Archivo |
|---|---|
| Especificación de Requisitos | `public/docs/SRS.pdf` |
| Plan de Aseguramiento de Calidad | `public/docs/PAC.pdf` |
| Manual de Usuario | `public/docs/Usuario.pdf` |
| Manual Técnico | `public/docs/tecnico.pdf` |
| Errores y Soluciones | `public/docs/errores.pdf` |

Los PDF también se consultan desde **Anexos** en la aplicación. Falta incorporar `public/docs/Herramientas_QA.pdf`. Para detalles de arquitectura, mapeo de rutas y pruebas, consulta el manual técnico y el PAC.

## Equipo de desarrollo

- Santiago Polania Giraldo
- Alison Nicol Cabrera Castillo
- Andrés Felipe Pabón Ardila
- Santiago Castro Cerón
- Kevin Alexis Figueroa Piernagorda

**Instructor:** José de Jesús Motta Vargas.

---

🍽️ **Cookly** — Organiza, comparte y descubre nuevas recetas.  
*Proyecto académico del SENA – Centro de la Industria, la Empresa y los Servicios.*
