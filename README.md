🍳 Cookly

Cookly es una aplicación web desarrollada en PHP, MySQL, HTML, CSS y JavaScript, diseñada para administrar recetas de cocina de forma sencilla e intuitiva. La plataforma permite a los usuarios registrarse, iniciar sesión y gestionar sus recetas, categorías e ingredientes.

El proyecto está organizado mediante archivos separados por funcionalidades, facilitando el mantenimiento del código y la reutilización de funciones comunes.

🚀 Funcionalidades

Registro de usuarios.

Inicio y cierre de sesión.

Gestión de perfiles de usuario.

Creación de recetas.

Edición de recetas.

Eliminación de recetas.

Visualización del detalle de las recetas.

Administración de categorías.

Administración de ingredientes.

Validación de usuarios mediante sesiones.

🛠️ Tecnologías utilizadas

PHP

MySQL

HTML5

CSS3

JavaScript

Docker

PDO

📂 Estructura del proyecto

Cookly/
│
├── config/
│   └── database.php
│
├── includes/
│   ├── auth.php
│   ├── funciones_recetas.php
│   ├── funciones_usuarios.php
│   ├── header.php
│   └── footer.php
│
├── public/
│   ├── css/
│   ├── js/
│   └── images/
│
├── index.php
├── Login.php
├── Logout.php
├── registro.php
├── perfil.php
└── ...

⚙️ Instalación

Clonar el repositorio.

Crear la base de datos cookly.

Importar el archivo SQL del proyecto.

Configurar la conexión en config/database.php.

Iniciar el servidor web o los contenedores Docker.

Acceder a la aplicación desde el navegador.

🔒 Seguridad

El proyecto utiliza PDO y consultas preparadas para acceder a la base de datos, ayudando a prevenir ataques de SQL Injection. Además, implementa sesiones para controlar el acceso de los usuarios autenticados.

🎯 Objetivo

Cookly busca ofrecer una plataforma donde los usuarios puedan administrar recetas de cocina de forma práctica, aplicando buenas prácticas de desarrollo web y organización del código mediante funciones reutilizables y una estructura modular.
