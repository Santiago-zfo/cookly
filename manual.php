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
        font-size: 1rem;
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
            <li><a href="#intro">1. Introducción a Cookly</a></li>
            <li><a href="#registro">2. Registro y Acceso</a></li>
            <li><a href="#perfil">3. Mi Perfil</a></li>
            <li><a href="#crear">4. Crear una Receta</a></li>
            <li><a href="#explorar">5. Explorar y Buscar</a></li>
            <li><a href="#ver">6. Ver Detalle</a></li>
            <li><a href="#editar">7. Editar Receta</a></li>
            <li><a href="#eliminar">8. Eliminar Receta</a></li>
            <li><a href="#consejos">9. Consejos Útiles</a></li>
            <li><a href="#faq">10. Preguntas Frecuentes</a></li>
        </ul>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="wiki-content">
        
        <h1>Manual de Usuario de Cookly</h1>
        <p class="subtitle">De la enciclopedia culinaria Cookly. Esta guía detalla todo el funcionamiento del sistema, desde el registro hasta los consejos para publicar recetas perfectas.</p>

        <div class="info-card">
            <h4>📌 Información General</h4>
            <p style="margin: 0; font-size: 0.95rem; color: var(--text-main);">
                <strong>Plataforma:</strong> Cookly<br>
                <strong>Propósito:</strong> Gestión, creación y compartición de recetas de cocina.<br>
                <strong>Idioma:</strong> Español.
            </p>
        </div>

        <!-- SECCIÓN 1: INTRO -->
        <section id="intro">
            <h2>1. Introducción a Cookly</h2>
            <p>Cookly es un recetario digital diseñado para almacenar, organizar y compartir los mejores platos. Su objetivo es brindar una experiencia intuitiva tanto para chefs experimentados como para principiantes que buscan inspiración.</p>
            <p>Al ingresar, el usuario es recibido por su panel <strong>"Mis recetas"</strong>, donde puede administrar sus publicaciones personales, ver su estado de aprobación y crear nuevas recetas.</p>
            
            <div class="wiki-image-box">
                <img src="public/IMG/introduccion.jpg" alt="Pantalla de inicio" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Inicio';">
                <div class="caption">Figura 1. Vista principal del panel de usuario.</div>
            </div>
        </section>

        <!-- SECCIÓN 2: REGISTRO -->
        <section id="registro">
            <h2>2. Registro e Inicio de Sesión</h2>
            <p>Para poder guardar recetas y acceder a la comunidad, es necesario contar con una cuenta.</p>
            <ul>
                <li><strong>Crear Cuenta:</strong> En la pantalla inicial, haz clic en <em>"¿No tienes cuenta? Regístrate"</em>. Se solicitará un nombre, correo y contraseña (mín. 8 caracteres, una letra y un número).</li>
                <li><strong>Iniciar Sesión:</strong> Introduce tu correo y contraseña para acceder a tu panel.</li>
                <li><strong>Seguridad:</strong> Las contraseñas son encriptadas con el algoritmo Bcrypt, garantizando que nadie, ni siquiera los administradores, puedan verla en texto plano.</li>
            </ul>

            <div class="nota-box">
                <strong>Nota de Seguridad:</strong> Nunca compartas tu contraseña con otras personas. Si olvidas tu contraseña, deberás contactar con el soporte del sistema para restablecerla.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/login.jpg" alt="Pantalla de Login" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Login';">
                <div class="caption">Figura 2. Interfaz de inicio de sesión de Cookly.</div>
            </div>
        </section>

        <!-- SECCIÓN 3: PERFIL -->
        <section id="perfil">
            <h2>3. Mi Perfil</h2>
            <p>Al hacer clic en tu nombre (esquina superior derecha), accederás a la configuración del perfil. Esta sección es tu carta de presentación ante la comunidad.</p>
            <ul>
                <li><strong>Datos Personales:</strong> Permite cambiar nombre, correo y añadir una biografía. La biografía es el espacio para contar tu experiencia culinaria.</li>
                <li><strong>Avatar:</strong> Se puede subir una imagen local o pegar una URL de internet. Se recomienda una imagen cuadrada o circular.</li>
                <li><strong>Seguridad:</strong> Opción de cambiar la contraseña actual por una nueva en cualquier momento.</li>
                <li><strong>Eliminar cuenta:</strong> Borra de forma permanente el perfil y <strong>todas las recetas asociadas</strong>. Esta acción no se puede deshacer.</li>
            </ul>

            <div class="wiki-image-box">
                <img src="public/IMG/perfil.jpg" alt="Pantalla de Perfil" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Perfil';">
                <div class="caption">Figura 3. Sección de edición del perfil de usuario.</div>
            </div>
        </section>

        <!-- SECCIÓN 4: CREAR -->
        <section id="crear">
            <h2>4. Crear una Receta</h2>
            <p>Para publicar un plato, dirígete al botón <strong>"+ Nueva receta"</strong>. El formulario solicita los siguientes parámetros:</p>
            <ul>
                <li><strong>Título y Categoría:</strong> Identificación y clasificación del plato (Desayuno, Almuerzo, Postre, etc.).</li>
                <li><strong>Tiempo, Dificultad y Porciones:</strong> Datos clave para el comensal. El tiempo se mide en minutos y las porciones deben ser mayores a cero.</li>
                <li><strong>Ingredientes:</strong> Se pueden agregar filas ilimitadas indicando cantidad y unidad de medida (gramos, tazas, cucharadas, etc.).</li>
                <li><strong>Imagen y Pasos:</strong> Fotografía del platillo e instrucciones detalladas. La imagen es opcional; si no se coloca, el sistema usa un ícono genérico.</li>
            </ul>
            
            <div class="nota-box">
                <strong>Consejo:</strong> Al agregar ingredientes, escribe primero el nombre, luego la cantidad (ej. 200) y finalmente selecciona la unidad de medida. Puedes usar el botón <strong>"+ Ingrediente"</strong> para añadir más filas.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/crear_receta.jpg" alt="Formulario de nueva receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Crear+Receta';">
                <div class="caption">Figura 4. Formulario de carga de recetas con ingredientes dinámicos.</div>
            </div>
        </section>

        <!-- SECCIÓN 5: EXPLORAR -->
        <section id="explorar">
            <h2>5. Explorar y Buscar</h2>
            <p>La pestaña <strong>"Explorar"</strong> despliega un catálogo público con las recetas aprobadas. Es el espacio ideal para inspirarse.</p>
            <ul>
                <li><strong>Galería:</strong> Las recetas se muestran en tarjetas con imagen, título, autor y categoría.</li>
                <li><strong>Búsqueda:</strong> Puedes usar la barra de búsqueda para filtrar por el nombre del plato, la categoría o el nombre del autor.</li>
            </ul>

            <div class="wiki-image-box">
                <img src="public/IMG/explorar.jpg" alt="Galería de recetas" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Explorar';">
                <div class="caption">Figura 5. Galería general de la comunidad.</div>
            </div>
        </section>

        <!-- SECCIÓN 6: VER DETALLE -->
        <section id="ver">
            <h2>6. Ver Detalle de una Receta</h2>
            <p>Al hacer clic en el botón <strong>"Ver"</strong> de cualquier receta, accederás a su vista completa. En esta pantalla encontrarás:</p>
            <ul>
                <li>La imagen en alta resolución.</li>
                <li>El autor y datos clave (tiempo, porciones, dificultad).</li>
                <li>La lista completa de ingredientes con sus cantidades exactas.</li>
                <li>Las instrucciones paso a paso para la preparación.</li>
            </ul>
            <p>Si la receta es de tu autoría, verás botones adicionales para <strong>Editar</strong> o <strong>Eliminar</strong> en la parte inferior.</p>
        </section>

        <!-- SECCIÓN 7: EDITAR -->
        <section id="editar">
            <h2>7. Editar Receta</h2>
            <p>Si necesitas hacer cambios en una receta que ya publicaste, dirígete a tu panel <strong>"Mis recetas"</strong> y busca la tarjeta de la receta. En la parte inferior verás los botones de acción.</p>
            <p>Haz clic en el botón <strong>"Editar"</strong> (texto de color verde). Se abrirá el mismo formulario que usaste para crearla, pero con todos los datos ya cargados.</p>
            <ul>
                <li>Puedes cambiar el título, categoría, tiempo, dificultad, porciones, descripción e instrucciones.</li>
                <li>Puedes añadir, modificar o eliminar ingredientes existentes.</li>
                <li>Puedes reemplazar la imagen de la receta (ya sea con una nueva URL o subiendo un archivo).</li>
            </ul>
            <p>Una vez hayas terminado, pulsa el botón <strong>"Actualizar receta"</strong> para guardar los cambios.</p>

            <div class="wiki-image-box">
                <img src="public/IMG/editar.jpg" alt="Editar receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Editar';">
                <div class="caption">Figura 6. Formulario de edición con los datos precargados.</div>
            </div>
        </section>

        <!-- SECCIÓN 8: ELIMINAR -->
        <section id="eliminar">
            <h2>8. Eliminar Receta</h2>
            <p>Si ya no quieres que una receta aparezca en tu perfil ni en la comunidad, puedes borrarla. En tu panel <strong>"Mis recetas"</strong>, busca la receta y haz clic en el botón <strong>"Eliminar"</strong> (texto de color rojo).</p>
            
            <div class="nota-box">
                <strong>Advertencia:</strong> El sistema te pedirá una confirmación antes de borrarla definitivamente. Ten en cuenta que esta acción <strong>no se puede deshacer</strong> y la receta se eliminará de la base de datos junto con sus ingredientes.
            </div>

            <div class="wiki-image-box">
                <img src="public/IMG/eliminar.jpg" alt="Eliminar receta" onerror="this.src='https://placehold.co/800x400/F3F4F6/6B7280?text=Imagen+de+Eliminar';">
                <div class="caption">Figura 7. Confirmación de eliminación de receta.</div>
            </div>
        </section>

        <!-- SECCIÓN 9: CONSEJOS -->
        <section id="consejos">
            <h2>9. Consejos Útiles</h2>
            <ul>
                <li><strong>Usa buenas fotos:</strong> Las recetas con imágenes atractivas reciben más visitas. Procura tener buena iluminación y un fondo limpio.</li>
                <li><strong>Sé específico:</strong> En las instrucciones, detalla los tiempos de cocción y las temperaturas. Esto ayuda a otros usuarios a replicar tu receta con éxito.</li>
                <li><strong>Clasifica bien:</strong> Elige la categoría correcta para que tu receta sea fácil de encontrar.</li>
                <li><strong>Revisa antes de guardar:</strong> Asegúrate de que todos los ingredientes tengan cantidad y unidad antes de enviar el formulario.</li>
            </ul>
        </section>

        <!-- SECCIÓN 10: FAQ -->
        <section id="faq">
            <h2>10. Preguntas Frecuentes (FAQ)</h2>
            <p><strong>¿Por qué no veo mi receta en "Explorar"?</strong><br>
            Las recetas pasan por un proceso de revisión antes de ser públicas. Si tu receta no aparece, puede que esté en estado <em>Pendiente</em> o <em>Rechazada</em>. Puedes ver su estado en el panel "Mis recetas".</p>
            
            <p><strong>¿Puedo cambiar mi foto de perfil?</strong><br>
            Sí, desde tu perfil puedes subir una nueva imagen o pegar una URL. Los cambios se guardan al instante.</p>

            <p><strong>¿Qué pasa si elimino mi cuenta?</strong><br>
            Al eliminar tu cuenta, todas tus recetas también serán eliminadas de la base de datos de manera permanente. Esta acción no se puede deshacer.</p>
        </section>

        <div style="background: var(--primary-light); color: var(--primary-hover); padding: 2rem; border-radius: var(--radius-md); margin-top: 4rem; text-align: center; border: 1px solid var(--primary);">
            <strong style="font-size: 1.2rem;">¡Fin del manual! 🚀</strong><br>
            <p style="margin-top: 0.5rem; margin-bottom: 0;">Ahora tienes el conocimiento para usar Cookly al máximo.</p>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>