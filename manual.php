<?php
// manual.php
require_once __DIR__ . '/includes/auth.php';
requerirLogin();

$titulo = 'Manual de Usuario';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    /* Estilos tipo Wikipedia para el manual */
    .wiki-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
        background: var(--surface);
        padding: 2.5rem;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-md);
    }

    .wiki-sidebar {
        position: sticky;
        top: 100px;
        background: var(--bg-color);
        padding: 1.5rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .wiki-sidebar h3 {
        margin-top: 0;
        font-size: 1rem;
        color: var(--text-main);
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }

    .wiki-sidebar ul {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .wiki-sidebar a {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: var(--transition);
        display: block;
        padding: 0.3rem 0.5rem;
        border-radius: 6px;
        border-left: 3px solid transparent;
    }

    .wiki-sidebar a:hover {
        color: var(--primary);
        background: var(--surface);
        border-left-color: var(--primary);
    }

    .wiki-content {
        line-height: 1.8;
        color: var(--text-main);
        font-size: 1.05rem;
    }

    .wiki-content h1 {
        font-size: 2.5rem;
        margin-top: 0;
        margin-bottom: 0.5rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .wiki-content .subtitle {
        color: var(--text-muted);
        font-size: 1.1rem;
        margin-bottom: 2rem;
        font-style: italic;
    }

    .wiki-content h2 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        margin-top: 3rem;
        margin-bottom: 1rem;
    }

    .wiki-content h3 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--text-main);
        margin-top: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .wiki-content ul {
        margin-bottom: 1.5rem;
        padding-left: 1.5rem;
    }

    .wiki-content li {
        margin-bottom: 0.5rem;
    }

    /* Cuadro de imágenes estilo Wiki */
    .wiki-image-box {
        margin: 2rem 0;
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1rem;
        text-align: center;
    }

    .wiki-image-box img {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
        background: var(--surface);
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
    }

    .wiki-image-box .caption {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 600;
    }

    /* Tarjeta de información rápida */
    .info-card {
        background: var(--primary-light);
        border: 1px solid var(--primary);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .info-card h4 {
        margin-top: 0;
        color: var(--primary-hover);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    /* Cajas de advertencia / notas */
    .nota-box {
        background: #FFFBEB;
        border-left: 4px solid #F59E0B;
        padding: 1rem 1.5rem;
        border-radius: 0 var(--radius-md) var(--radius-md) 0;
        margin: 1.5rem 0;
        color: #92400E;
    }
    .nota-box strong { color: #B45309; }

    @media (max-width: 900px) {
        .wiki-container {
            grid-template-columns: 1fr;
            padding: 1.5rem;
        }
        .wiki-sidebar {
            position: relative;
            top: 0;
            margin-bottom: 2rem;
            max-height: none;
        }
    }
</style>

<div class="wiki-container">
    
    <!-- MENÚ LATERAL (Estilo Wikipedia) -->
    <aside class="wiki-sidebar">
        <h3>Contenido</h3>
        <ul>
            <li><a href="#intro">1. Introducción y Filosofía</a></li>
            <li><a href="#registro">2. Registro y Seguridad</a></li>
            <li><a href="#perfil">3. Gestión del Perfil</a></li>
            <li><a href="#crear">4. Anatomía de una Receta</a></li>
            <li><a href="#explorar">5. Exploración y Búsqueda</a></li>
            <li><a href="#editar">6. Edición y Moderación</a></li>
            <li><a href="#eliminar">7. Eliminación de Contenido</a></li>
            <li><a href="#consejos">8. Mejores Prácticas</a></li>
            <li><a href="#faq">9. Preguntas Frecuentes</a></li>
        </ul>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="wiki-content">
        
        <h1>Manual de Usuario de Cookly</h1>
        <p class="subtitle">Documentación oficial del sistema. Aprende a gestionar tu recetario digital, interactuar con la comunidad y aprovechar todas las herramientas de la plataforma.</p>

        <div class="info-card">
            <h4>📌 Resumen de la Plataforma</h4>
            <p style="margin: 0; font-size: 0.95rem; color: var(--text-main);">
                <strong>Nombre:</strong> Cookly<br>
                <strong>Propósito:</strong> Creación, organización y difusión de recetas de cocina.<br>
                <strong>Modelo:</strong> Recetario comunitario con sistema de moderación.<br>
                <strong>Idioma:</strong> Español.
            </p>
        </div>

        <!-- SECCIÓN 1: INTRO -->
        <section id="intro">
            <h2>1. Introducción y Filosofía</h2>
            <p>Cookly nace con la idea de ser más que un simple bloc de notas. Es un ecosistema donde cocineros empíricos y profesionales pueden compartir su sazón. La plataforma está diseñada bajo un modelo de <strong>moderación previa</strong>: esto significa que cada receta pasa por una revisión antes de ser visible para el mundo, garantizando así la calidad y el respeto en la comunidad.</p>
            <p>Al iniciar sesión, aterrizarás directamente en tu panel de control personal: <strong>"Mis recetas"</strong>.</p>
            
            <div class="wiki-image-box">
                <img src="public/IMG/introduccion.jpg" alt="Pantalla de inicio" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Inicio';">
                <div class="caption">Figura 1. Vista principal del panel de usuario al iniciar sesión.</div>
            </div>
        </section>

        <!-- SECCIÓN 2: REGISTRO -->
        <section id="registro">
            <h2>2. Registro, Acceso y Seguridad</h2>
            <p>Para proteger tu contenido y evitar el spam, es obligatorio crear una cuenta. El proceso es rápido, pero cuenta con altos estándares de seguridad.</p>
            
            <h3>Creación de cuenta</h3>
            <ul>
                <li>Haz clic en el enlace inferior <em>"¿No tienes cuenta? Regístrate"</em>.</li>
                <li><strong>Requisitos de contraseña:</strong> Debe tener un mínimo de 8 caracteres, incluyendo al menos una letra y un número. Esto evita que terceros accedan a tu perfil.</li>
            </ul>

            <div class="nota-box">
                <strong>Nota de Privacidad:</strong> Tu contraseña es procesada mediante el algoritmo de encriptación <em>Bcrypt</em>. Ni siquiera los administradores del sistema pueden verla; solo se comparan los hashes criptográficos al iniciar sesión.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/login.jpg" alt="Pantalla de Login" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Login';">
                <div class="caption">Figura 2. Formulario de inicio de sesión con validación de credenciales.</div>
            </div>
        </section>

        <!-- SECCIÓN 3: PERFIL -->
        <section id="perfil">
            <h2>3. Gestión del Perfil</h2>
            <p>Tu perfil es tu carta de presentación. Puedes acceder a él haciendo clic en tu nombre (con el ícono de saludo 👋) en la esquina superior derecha. Desde aquí podrás:</p>
            <ul>
                <li><strong>Actualizar datos personales:</strong> Nombre, correo electrónico y una biografía donde puedes contar tu experiencia en la cocina.</li>
                <li><strong>Foto de perfil (Avatar):</strong> Puedes subir una imagen local (JPG, PNG, WEBP) o pegar una URL directa.</li>
                <li><strong>Cambiar contraseña:</strong> Introduce tu contraseña actual y confirma la nueva.</li>
                <li><strong>Zona de Peligro:</strong> Aquí puedes eliminar tu cuenta permanentemente. <em>Al hacerlo, todas tus recetas e ingredientes registrados desaparecerán en cascada.</em></li>
            </ul>

            <div class="wiki-image-box">
                <img src="public/IMG/perfil.jpg" alt="Pantalla de Perfil" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Perfil';">
                <div class="caption">Figura 3. Panel de configuración del perfil del usuario.</div>
            </div>
        </section>

        <!-- SECCIÓN 4: CREAR -->
        <section id="crear">
            <h2>4. Anatomía de una Receta</h2>
            <p>Para publicar un plato, ve a la pestaña <strong>"+ Nueva receta"</strong>. El sistema te pedirá detalles precisos para que otros usuarios puedan replicarlo:</p>
            
            <ul>
                <li><strong>Información Básica:</strong> Título atractivo, categoría (Desayuno, Cena, Postre, etc.), tiempo estimado en minutos y número de porciones. Los valores deben ser mayores a 0.</li>
                <li><strong>Imagen:</strong> Puedes subir una foto desde tu dispositivo o pegar el enlace de una imagen web. Si se deja en blanco, el sistema colocará un ícono por defecto.</li>
                <li><strong>Ingredientes Dinámicos:</strong> Usa el botón <strong>"+ Ingrediente"</strong> para abrir cuantas filas necesites. Especifica el nombre, la cantidad numérica y selecciona la unidad (gramos, tazas, unidad, cucharadas, etc.).</li>
                <li><strong>Instrucciones:</strong> El paso a paso. Sé muy claro, ya que es la base de la receta.</li>
            </ul>

            <div class="nota-box">
                <strong>Estado Inicial:</strong> Toda receta nueva entra con el estado <strong>"Pendiente"</strong>. Esto significa que un moderador la revisará antes de publicarla en el catálogo global.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/crear_receta.jpg" alt="Formulario de nueva receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Crear+Receta';">
                <div class="caption">Figura 4. Formulario de carga con campos dinámicos para ingredientes.</div>
            </div>
        </section>

        <!-- SECCIÓN 5: EXPLORAR -->
        <section id="explorar">
            <h2>5. Exploración y Búsqueda</h2>
            <p>La pestaña <strong>"Explorar"</strong> es el corazón público de Cookly. Aquí solo aparecen las recetas que han sido aprobadas por el equipo de moderación.</p>
            
            <h3>Buscador inteligente</h3>
            <p>En la parte superior encontrarás una barra de búsqueda. Tiene la particularidad de ser <strong>reactiva (AJAX)</strong>: mientras escribes, las tarjetas se van filtrando instantáneamente sin recargar la página. Puedes buscar por:</p>
            <ul>
                <li>Nombre del plato (Ej. "Pollo").</li>
                <li>Categoría (Ej. "Postres").</li>
                <li>Nombre del autor (Ej. "Alison").</li>
            </ul>

            <div class="wiki-image-box">
                <img src="public/IMG/explorar.jpg" alt="Galería de recetas" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Explorar';">
                <div class="caption">Figura 5. Galería general con buscador dinámico en tiempo real.</div>
            </div>
        </section>

        <!-- SECCIÓN 6: EDITAR -->
        <section id="editar">
            <h2>6. Edición y Moderación</h2>
            <p>Si cometes un error o quieres mejorar una receta que ya publicaste, dirígete a tu panel <strong>"Mis recetas"</strong> y pulsa el botón <strong>"Editar"</strong>.</p>
            
            <ul>
                <li>El formulario se abrirá con todos los datos precargados.</li>
                <li>Puedes añadir nuevos ingredientes, borrar los que ya no uses o cambiar la foto.</li>
                <li><strong>Regla de moderación:</strong> Cada vez que edites una receta (así ya estuviera aprobada), su estado volverá automáticamente a <strong>"Pendiente"</strong>. Esto obliga a los moderadores a revisar el nuevo contenido para asegurar que no se haya cambiado por algo inapropiado.</li>
            </ul>

            <div class="wiki-image-box">
                <img src="public/IMG/editar.jpg" alt="Editar receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Editar';">
                <div class="caption">Figura 6. Formulario de edición con datos precargados de la base de datos.</div>
            </div>
        </section>

        <!-- SECCIÓN 7: ELIMINAR -->
        <section id="eliminar">
            <h2>7. Eliminación de Contenido</h2>
            <p>Si deseas retirar una receta de la plataforma, ve a "Mis recetas" y pulsa el botón <strong>"Eliminar"</strong>.</p>
            
            <div class="nota-box">
                <strong>Advertencia:</strong> El sistema te pedirá una confirmación. Si aceptas, la receta y <em>todos sus ingredientes asociados</em> se borrarán de forma permanente. Esta acción no se puede deshacer.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/eliminar.jpg" alt="Eliminar receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Eliminar';">
                <div class="caption">Figura 7. Cuadro de diálogo para confirmar la eliminación de una receta.</div>
            </div>
        </section>

        <!-- SECCIÓN 8: CONSEJOS -->
        <section id="consejos">
            <h2>8. Mejores Prácticas para tus Recetas</h2>
            <ul>
                <li><strong>Fotografía gastronómica:</strong> Las recetas con fotos claras y bien iluminadas tienen un 80% más de probabilidades de ser guardadas por otros usuarios.</li>
                <li><strong>Sé específico con las medidas:</strong> No escribas "un poco de sal". Usa "1 cucharadita de sal". La plataforma tiene unidades exactas; aprovéchalas.</li>
                <li><strong>Revisa la ortografía:</strong> Un texto bien redactado inspira confianza y profesionalismo.</li>
                <li><strong>Instrucciones ordenadas:</strong> Divide la preparación en pasos lógicos. Si usas el botón "Enter" para separar párrafos, el sistema lo respetará al mostrar la receta.</li>
            </ul>
        </section>

        <!-- SECCIÓN 9: FAQ -->
        <section id="faq">
            <h2>9. Preguntas Frecuentes (FAQ)</h2>
            
            <h3>¿Por qué no veo mi receta en la pestaña "Explorar"?</h3>
            <p>Porque aún no ha sido aprobada. Ve a "Mis recetas" y revisa la etiqueta de color: si dice <span style="color:#F59E0B; font-weight:bold;">Pendiente</span>, significa que un administrador debe revisarla. Si dice <span style="color:var(--success); font-weight:bold;">Aceptado</span>, ya es pública.</p>

            <h3>¿Puedo cambiar mi foto de perfil cuando quiera?</h3>
            <p>Sí. Desde tu perfil puedes subir una imagen nueva o pegar un enlace. Los cambios se reflejan de inmediato en tu barra de navegación.</p>

            <h3>¿Olvidé mi contraseña, qué hago?</h3>
            <p>Actualmente, por ser un sistema cerrado, debes contactar con el administrador del sistema para que te asigne una nueva o reinicie tu cuenta.</p>

            <h3>¿Si elimino mi cuenta se borran mis recetas?</h3>
            <p>Sí. Al eliminar el usuario, el sistema aplica un borrado en cascada (<em>ON DELETE CASCADE</em>) y elimina absolutamente todo tu historial culinario de la base de datos para no dejar basura digital.</p>
        </section>

        <div style="background: var(--primary-light); color: var(--primary-hover); padding: 2rem; border-radius: var(--radius-md); margin-top: 4rem; text-align: center; border: 1px solid var(--primary);">
            <strong style="font-size: 1.2rem;">¡Fin del manual! 🚀</strong><br>
            <p style="margin-top: 0.5rem; margin-bottom: 0;">Ahora tienes el conocimiento técnico y práctico para dominar Cookly. ¡Buen provecho!</p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>