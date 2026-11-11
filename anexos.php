<?php
// anexos.php
require_once __DIR__ . '/includes/auth.php';
requerirLogin();

$titulo = 'Anexos y Documentación';
require_once __DIR__ . '/includes/header.php';
?>

<style>
    .documentos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .doc-card {
        background: var(--surface);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .doc-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background: var(--primary);
        opacity: 0;
        transition: var(--transition);
    }
    
    .doc-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }
    
    .doc-card:hover::before {
        opacity: 1;
    }
    
    .doc-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    
    .doc-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-main);
        margin: 0 0 0.5rem 0;
    }
    
    .doc-desc {
        font-size: 0.9rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    /* ESTILOS DEL MODAL (VISOR) */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        backdrop-filter: blur(4px);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
    }

    .modal-content {
        background: var(--surface);
        width: 90%;
        max-width: 1000px;
        height: 85vh;
        border-radius: var(--radius-lg);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-content {
        transform: scale(1);
    }

    .modal-header {
        padding: 1rem 1.5rem;
        background: var(--bg-color);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: var(--text-main);
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
        transition: var(--transition);
    }

    .modal-close:hover {
        color: var(--error);
        transform: rotate(90deg);
    }

    .modal-body {
        flex-grow: 1;
        background: #323639; /* Color oscuro para el visor PDF */
    }

    .modal-body iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>

<section class="panel">
    <div class="section-heading">
        <div>
            <h2>📚 Anexos y Documentación del Sistema</h2>
            <p style="color: var(--text-muted); margin-top: 0.5rem; font-size: 0.95rem;">
                Haz clic en "Ver Documento" para leer el archivo sin salir de la plataforma.
            </p>
        </div>
    </div>

    <div class="documentos-grid">
        <!-- 1. SRS -->
        <div class="doc-card">
            <div class="doc-icon">📄</div>
            <h3 class="doc-title">Especificación de Requisitos (SRS)</h3>
            <p class="doc-desc">Documento detallado que describe la arquitectura del sistema y los requisitos funcionales.</p>
            <!-- IMPORTANTE: Cambia .docx por .pdf si es posible -->
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/SRS.pdf', 'Especificación de Requisitos (SRS)')" style="width: 100%;">Ver Documento</button>
        </div>

        <!-- 2. Manual Técnico -->
        <div class="doc-card">
            <div class="doc-icon">⚙️</div>
            <h3 class="doc-title">Manual Técnico</h3>
            <p class="doc-desc">Guía de instalación, configuración de la base de datos y detalles del código fuente.</p>
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/Manual_Tecnico.pdf', 'Manual Técnico')" style="width: 100%;">Ver Documento</button>
        </div>

        <div class="doc-card">
            <div class="doc-icon">⚙️</div>
            <h3 class="doc-title">Manual de Usuario</h3>
            <p class="doc-desc">Guía de uso del sistema para los usuarios finales.</p>
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/Manual_Usuario.pdf', 'Manual de Usuario')" style="width: 100%;">Ver Documento</button>
        </div>

        <!-- 3. PAC -->
        <div class="doc-card">
            <div class="doc-icon">📋</div>
            <h3 class="doc-title">Plan de Aseguramiento de Calidad (PAC)</h3>
            <p class="doc-desc">Metodología y estándares utilizados para garantizar la calidad del software.</p>
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/PAC_Cookly.pdf', 'Plan de Aseguramiento de Calidad')" style="width: 100%;">Ver Documento</button>
        </div>

        <!-- 4. Errores y Soluciones -->
        <div class="doc-card">
            <div class="doc-icon">🐛</div>
            <h3 class="doc-title">Errores y Soluciones</h3>
            <p class="doc-desc">Registro histórico de los errores encontrados durante el desarrollo y sus soluciones.</p>
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/errores.pdf', 'Errores y Soluciones')" style="width: 100%;">Ver Documento</button>
        </div>

        <!-- 5. Herramientas de QA -->
        <div class="doc-card">
            <div class="doc-icon">✅</div>
            <h3 class="doc-title">Herramientas de Aseguramiento</h3>
            <p class="doc-desc">Documentación sobre las herramientas de testing y control de calidad aplicadas.</p>
            <button class="submit-btn" onclick="abrirModal('<?= $base_url ?>/public/docs/Herramientas_QA.pdf', 'Herramientas de Aseguramiento de Calidad')" style="width: 100%;">Ver Documento</button>
        </div>
    </div>
</section>

<!-- MODAL OCULTO (VISOR) -->
<div class="modal-overlay" id="doc-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modal-title">Documento</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <div class="modal-body">
            <iframe id="modal-iframe" src=""></iframe>
        </div>
    </div>
</div>

<script>
    function abrirModal(url, titulo) {
        document.getElementById('modal-title').innerText = titulo;
        document.getElementById('modal-iframe').src = url;
        document.getElementById('doc-modal').classList.add('active');
        // Evitar scroll en el fondo
        document.body.style.overflow = 'hidden';
    }

    function cerrarModal() {
        document.getElementById('doc-modal').classList.remove('active');
        document.getElementById('modal-iframe').src = ''; // Detener la carga
        document.body.style.overflow = 'auto';
    }

    // Cerrar el modal si se hace clic fuera de la caja blanca
    document.getElementById('doc-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModal();
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>