/* ============================================================
   public/JS/auth.js
   Mostrar/ocultar contraseñas y validación básica del lado cliente.
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---------- Botones "Mostrar" ---------- */
    document.querySelectorAll('button.ver').forEach(btn => {
        btn.addEventListener('click', () => {
            const idInput = btn.getAttribute('aria-controls');
            const input   = document.getElementById(idInput);
            if (!input) return;

            const esPassword = input.type === 'password';
            input.type = esPassword ? 'text' : 'password';
            btn.textContent = esPassword ? 'Ocultar' : 'Mostrar';
        });
    });

    /* ---------- Validación en vivo (registro) ---------- */
    const formReg = document.getElementById('form-registro');
    if (formReg) {
        const clave     = document.getElementById('reg-clave');
        const confirmar = document.getElementById('reg-confirmar');

        const validar = () => {
            if (!clave || !confirmar) return;
            const ok = clave.value === confirmar.value;
            confirmar.setCustomValidity(ok ? '' : 'Las contraseñas no coinciden');
        };

        clave?.addEventListener('input', validar);
        confirmar?.addEventListener('input', validar);
    }

    /* ---------- Quitar mensajes de error al escribir ---------- */
    document.querySelectorAll('.campo input').forEach(input => {
        input.addEventListener('input', () => {
            input.setAttribute('aria-invalid', 'false');
            const error = input.closest('.campo')?.querySelector('.error');
            if (error) error.textContent = '';
        });
    });
});