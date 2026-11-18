<section class="panel">
    <div class="section-heading">
        <h2>Mi Perfil</h2>
        <a href="<?= cookly_page('index.php') ?>" class="logout-btn">← Volver al inicio</a>
    </div>

    <!-- FOTO Y DATOS PRINCIPALES -->
    <div style="display: flex; gap: 2rem; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap;">
        <div style="text-align: center;">
            <?php if (!empty($perfil['avatar_url'])): ?>
                <img src="<?= htmlspecialchars(cookly_media_url($perfil['avatar_url'])) ?>" alt="Avatar" 
                     style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--primary-light); box-shadow: var(--shadow-sm);">
            <?php else: ?>
                <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--bg-color); display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--text-muted); border: 4px solid var(--border-color);">
                    👤
                </div>
            <?php endif; ?>
            <p style="font-weight: 800; margin-top: 1rem; font-size: 1.1rem;"><?= htmlspecialchars($perfil['nombre']) ?></p>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="perfil">
                <div class="form-grid">
                    <label>Nombre
                        <input type="text" name="nombre" value="<?= htmlspecialchars($perfil['nombre']) ?>" required>
                    </label>
                    <label>Correo electrónico
                        <input type="email" name="correo" value="<?= htmlspecialchars($perfil['correo_electronico']) ?>" required>
                    </label>

                    <label class="full-width">
                        Foto de perfil (URL o Archivo)
                        <div style="display: flex; gap: 1rem; flex-direction: column; background: var(--bg-color); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); margin-top: 0.3rem;">
                            <input type="url" name="avatar_url" value="<?= htmlspecialchars($perfil['avatar_url'] ?? '') ?>" placeholder="https://ejemplo.com/mifoto.jpg">
                            <small style="text-align: center; color: var(--text-muted); font-weight: 700;">— O selecciona una imagen de tu PC —</small>
                            <input type="file" name="avatar_file" accept="image/*" style="background: var(--surface); padding: 0.5rem;">
                        </div>
                    </label>

                    <label class="full-width">Biografía
                        <textarea name="biografia" rows="3" placeholder="Cuéntanos un poco sobre ti..."><?= htmlspecialchars($perfil['biografia'] ?? '') ?></textarea>
                    </label>
                </div>
                <button type="submit" class="submit-btn">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

    <!-- CAMBIO DE CONTRASEÑA -->
    <h3>Cambiar Contraseña</h3>
    <form method="POST" style="max-width: 500px; margin-top: 1rem;">
        <input type="hidden" name="accion" value="password">
        <div class="form-grid">
            <label class="full-width">Contraseña actual
                <input type="password" name="pass_actual" required>
            </label>
            <label class="full-width">Nueva contraseña
                <input type="password" name="pass_nueva" required>
            </label>
            <label class="full-width">Confirmar nueva contraseña
                <input type="password" name="pass_confirmar" required>
            </label>
        </div>
        <button type="submit" class="secondary" style="margin-top: 1rem;">Actualizar Contraseña</button>
    </form>

    <!-- ZONA DE PELIGRO -->
    <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">
    
    <div class="danger-zone">
        <h3>Zona de Peligro</h3>
        <p>Si eliminas tu cuenta, <strong>todas tus recetas e ingredientes serán borrados permanentemente</strong>. Esta acción no se puede deshacer.</p>
        <form method="POST" id="form-eliminar-cuenta">
            <input type="hidden" name="accion" value="eliminar_cuenta">
            <button type="button" class="danger-btn" onclick="confirmarEliminacion()">Eliminar mi cuenta definitivamente</button>
        </form>
    </div>
</section>

<script>
function confirmarEliminacion() {
    const confirmacion = prompt("Esta acción borrará TODAS tus recetas y no se puede deshacer.\n\nPara confirmar, escribe la palabra: ELIMINAR");
    if (confirmacion === "ELIMINAR") {
        document.getElementById('form-eliminar-cuenta').submit();
    } else if (confirmacion !== null) {
        alert("Confirmación incorrecta. Tu cuenta está a salvo.");
    }
}
</script>
