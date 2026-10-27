<?php
// manual.php
require_once __DIR__ . '/includes/auth.php';
requerirLogin();

$titulo = 'Manual de Usuario';
require_once __DIR__ . '/includes/header.php';
?>

<section class="panel" style="display: grid; grid-template-columns: 260px 1fr; gap: 2rem; align-items: start;">
    
    <!-- MENÚ LATERAL (Índice) -->
    <aside style="position: sticky; top: 100px; background: var(--bg-color); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
        <h3 style="margin-top: 0; color: var(--primary); font-size: 1.1rem;">Índice del Manual</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.8rem;">
            <li><a href="#registro" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">1. Registro y Acceso</a></li>
            <li><a href="#intro" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">2. Introducción</a></li>
            <li><a href="#perfil" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">3. Mi Perfil</a></li>
            <li><a href="#crear" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">4. Crear Receta</a></li>
            <li><a href="#explorar" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">5. Explorar Recetas</a></li>
            <li><a href="#editar" style="color: var(--text-main); font-weight: 700; text-decoration: none; font-size: 0.9rem;">6. Editar y Eliminar</a></li>
        </ul>
    </aside>

    <!-- CONTENIDO DEL MANUAL -->
    <div style="line-height: 1.7; color: var(--text-main);">
        <h1 style="font-size: 2rem; margin-top: 0; color: var(--text-main);">Manual de Usuario - Cookly 🍳</h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2rem;">
            Bienvenido a la guía oficial de Cookly. Aquí aprenderás a utilizar todas las herramientas de la plataforma, desde cómo crear tu cuenta hasta cómo compartir tus mejores recetas con el mundo.
        </p>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 1 -->
        <section id="registro">
            <h2>1. Registro e Inicio de Sesión</h2>
            <p>Para poder guardar tus recetas y acceder a la comunidad, necesitas una cuenta. El proceso es muy rápido:</p>
            <ul>
                <li><strong>Crear Cuenta:</strong> En la pantalla de inicio, haz clic en <em>"¿No tienes cuenta? Regístrate"</em>. Solo necesitas un nombre, un correo válido y una contraseña (mínimo 8 caracteres, con al menos una letra y un número).</li>
                <li><strong>Iniciar Sesión:</strong> Ingresa tu correo y contraseña. El sistema validará tus datos y te llevará directamente a tu panel de recetas.</li>
                <li><strong>Seguridad:</strong> Tu contraseña se guarda encriptada en la base de datos, por lo que nadie (ni siquiera los administradores) puede verla.</li>
            </ul>
            
            <img src="public/IMG/login.jpg" alt="Pantalla de Login" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 2 -->
        <section id="intro">
            <h2>2. Introducción</h2>
            <p>Cookly es tu recetario digital personal y comunitario. Te permite guardar, organizar y compartir tus mejores platos. La plataforma está diseñada para ser intuitiva: una vez dentro, serás recibido por tu panel principal de <strong>"Mis recetas"</strong>.</p>
            
            <img src="public/IMG/introduccion.jpg" alt="Pantalla de inicio" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 3 -->
        <section id="perfil">
            <h2>3. Mi Perfil</h2>
            <p>Haz clic en tu nombre (👋) en la esquina superior derecha para acceder a la configuración de tu perfil. Desde aquí podrás:</p>
            <ul>
                <li><strong>Personalizar tu identidad:</strong> Cambia tu nombre, añade una biografía para que la comunidad te conozca y actualiza tu correo.</li>
                <li><strong>Foto de perfil (Avatar):</strong> Tienes total libertad. Puedes pegar el enlace (URL) de una imagen de internet o subir una foto directamente desde tu computadora.</li>
                <li><strong>Seguridad:</strong> En la sección inferior puedes cambiar tu contraseña actual por una nueva en cualquier momento.</li>
                <li><strong>Zona de peligro:</strong> Si decides dejar la plataforma, puedes eliminar tu cuenta. <em>Ojo: Esto borrará también todas las recetas que hayas creado.</em></li>
            </ul>

            <img src="public/IMG/perfil.jpg" alt="Pantalla de Perfil" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 4 -->
        <section id="crear">
            <h2>4. Crear una Receta</h2>
            <p>Para añadir un nuevo plato a tu recetario, haz clic en el botón <strong>"+ Nueva receta"</strong> en la barra superior. Deberás llenar los siguientes campos:</p>
            <ul>
                <li><strong>Título y Categoría:</strong> Dale un nombre atractivo a tu receta y clasifícala (Desayuno, Almuerzo, Postre, etc.).</li>
                <li><strong>Tiempo y Dificultad:</strong> Especifica cuántos minutos toma prepararla y qué tan difícil es.</li>
                <li><strong>Ingredientes:</strong> Usa el botón "+ Ingrediente" para añadir todas las filas que necesites. Escribe el ingrediente, la cantidad (ej. 200) y selecciona la unidad (gramos, tazas, unidades, etc.).</li>
                <li><strong>Imagen del plato:</strong> Sube una foto desde tu PC o pega una URL. Si no pones imagen, el sistema usará un ícono por defecto.</li>
                <li><strong>Instrucciones:</strong> Escribe el paso a paso de forma clara. ¡No olvides guardar los cambios al final!</li>
            </ul>

            <img src="public/IMG/crear_receta.jpg" alt="Formulario de nueva receta" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 5 -->
        <section id="explorar">
            <h2>5. Explorar Recetas</h2>
            <p>En la pestaña <strong>"Explorar"</strong> podrás ver un catálogo con todas las recetas creadas por todos los usuarios de la comunidad.</p>
            <p>Esto es ideal para inspirarte, descubrir nuevos platos o buscar qué cocinar hoy. Puedes usar la barra de búsqueda para filtrar recetas por su título o categoría.</p>

            <img src="public/IMG/explorar.jpg" alt="Galería de recetas" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 6 -->
        <section id="editar">
            <h2>6. Editar Receta</h2>
            <p>Si necesitas hacer cambios en una receta que ya publicaste (corregir un ingrediente, cambiar la foto o mejorar las instrucciones), dirígete a <strong>"Mis recetas"</strong> y haz clic en el botón <strong>"Editar"</strong>.</p>
            <p>Se abrirá el mismo formulario que usaste para crearla, pero con todos los datos ya cargados. Modifica lo que necesites y pulsa en "Actualizar receta" para guardar los cambios.</p>

            <img src="public/IMG/editar.jpg" alt="Editar receta" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

        <!-- SECCIÓN 7 -->
        <section id="eliminar">
            <h2>7. Eliminar Receta</h2>
            <p>Si ya no quieres que una receta aparezca en tu perfil ni en la comunidad, puedes borrarla. En <strong>"Mis recetas"</strong>, haz clic en el botón rojo <strong>"Eliminar"</strong>.</p>
            <p>Por seguridad, el sistema te pedirá confirmación antes de borrarla definitivamente. <em>Ten en cuenta que esta acción no se puede deshacer.</em></p>

            <img src="public/IMG/eliminar.jpg" alt="Eliminar receta" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); margin: 1.5rem 0; box-shadow: var(--shadow-sm);">
        </section>

        <div style="background: var(--primary-light); color: var(--primary-hover); padding: 1.5rem; border-radius: var(--radius-md); margin-top: 3rem; text-align: center;">
            <strong>¡Ya estás listo! 🚀</strong><br>
            Ahora puedes empezar a llenar el mundo de sabor con tus recetas.
        </div>

    </div>
</section>

<!-- Estilos extras para que en celular el menú quede arriba -->
<style>
    @media (max-width: 768px) {
        .panel[style*="grid-template-columns"] {
            grid-template-columns: 1fr !important;
        }
        aside[style*="sticky"] {
            position: relative !important;
            top: 0 !important;
        }
    }
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>