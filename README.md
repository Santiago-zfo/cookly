# 🍳 Cookly

Cookly es una aplicación web desarrollada en **PHP** y **MySQL** que permite a los usuarios gestionar recetas de cocina de una manera sencilla, organizada y segura. La plataforma ofrece funciones para registrar usuarios, iniciar sesión y administrar recetas con sus respectivas categorías e ingredientes.

El proyecto fue desarrollado aplicando el patrón de arquitectura **MVC (Modelo - Vista - Controlador)** y utilizando **Programación Orientada a Objetos (POO)** para mantener un código organizado, reutilizable y fácil de mantener.

## 🚀 Funcionalidades

- Registro de nuevos usuarios.
- Inicio y cierre de sesión.
- Creación de recetas.
- Edición de recetas existentes.
- Eliminación de recetas.
- Visualización del detalle de cada receta.
- Gestión de categorías.
- Gestión de ingredientes.
- Almacenamiento seguro de la información mediante MySQL y PDO.

## 🛠️ Tecnologías utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Docker
- PDO
- Programación Orientada a Objetos (POO)
- Arquitectura MVC

## 📂 Estructura del proyecto

```text
Cookly/
│
├── config/
├── controllers/
├── models/
├── includes/
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── views/
└── index.php
```

## ⚙️ Instalación

1. Clonar el repositorio.
2. Crear la base de datos `cookly`.
3. Importar el archivo SQL del proyecto.
4. Configurar la conexión a la base de datos en `config/database.php`.
5. Ejecutar el servidor web o los contenedores Docker.
6. Acceder al proyecto desde el navegador.

## 🔒 Seguridad

Cookly utiliza **PDO** con consultas preparadas para reducir el riesgo de ataques de SQL Injection y mejorar la seguridad en el acceso a la base de datos. Además, implementa sesiones para la autenticación y gestión de usuarios.

## 🎯 Objetivo del proyecto

El propósito de Cookly es ofrecer una plataforma donde los usuarios puedan compartir y administrar recetas de cocina de forma práctica, mientras se aplican buenas prácticas de desarrollo web como el uso de MVC, Programación Orientada a Objetos, separación de responsabilidades y acceso seguro a bases de datos.

## 👨‍💻 Autor

**Andrés Felipe Pabón Ardila**

Proyecto desarrollado como parte del proceso de formación en **Análisis y Desarrollo de Software (SENA)**.
