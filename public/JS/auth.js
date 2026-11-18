/* ============================================================
   public/JS/auth.js
   Mostrar/ocultar contraseñas, medidor de seguridad y
   validación básica de los formularios de acceso.
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---------- Botones "Mostrar / Ocultar" ---------- */
    document.querySelectorAll('button.ver').forEach(btn => {
        btn.addEventListener('click', () => {
            const idInput = btn.getAttribute('aria-controls');
            const input = document.getElementById(idInput);
            if (!input) return;

            const esPassword = input.type === 'password';
            input.type = esPassword ? 'text' : 'password';
            btn.textContent = esPassword ? 'Ocultar' : 'Mostrar';
            btn.setAttribute('aria-pressed', String(esPassword));
        });
    });

    /* ---------- Medidor de seguridad (registro) ---------- */
    const clave = document.getElementById('reg-clave');
    const medidor = document.getElementById('medidor-clave');

    if (clave && medidor) {
        const texto = medidor.querySelector('.medidor-texto');
        const barras = Array.from(medidor.querySelectorAll('.medidor-barras span'));
        const reglas = {
            longitud: valor => valor.length >= 8,
            mayuscula: valor => /[A-ZÁÉÍÓÚÑ]/.test(valor),
            minuscula: valor => /[a-záéíóúñ]/.test(valor),
            numero: valor => /\d/.test(valor),
            simbolo: valor => /[^A-Za-z0-9ÁÉÍÓÚáéíóúÑñ]/.test(valor)
        };
        const etiquetas = ['Débil', 'Aceptable', 'Buena', 'Excelente'];

        const actualizar = () => {
            const valor = clave.value;
            const cumplidas = Object.keys(reglas).filter(regla => reglas[regla](valor)).length;
            const nivel = valor === '' ? 0 : cumplidas <= 2 ? 1 : cumplidas === 3 ? 2 : cumplidas === 4 ? 3 : 4;

            medidor.dataset.nivel = String(nivel);
            texto.textContent = valor === ''
                ? 'Escribe una contraseña para ver su seguridad'
                : 'Seguridad: ' + etiquetas[nivel - 1];
            barras.forEach((barra, i) => barra.classList.toggle('activa', i < nivel));

            document.querySelectorAll('#requisitos-clave li').forEach(li => {
                const regla = reglas[li.dataset.regla];
                li.classList.toggle('cumplido', regla ? regla(valor) : false);
            });
        };

        clave.addEventListener('input', actualizar);
        actualizar();
    }

    /* ---------- Coincidencia de contraseñas (registro) ---------- */
    const formReg = document.getElementById('form-registro');
    if (formReg) {
        const confirmar = document.getElementById('reg-confirmar');

        const validar = () => {
            if (!clave || !confirmar) return;
            const ok = clave.value === confirmar.value;
            confirmar.setCustomValidity(ok ? '' : 'Las contraseñas no coinciden');
        };

        clave?.addEventListener('input', validar);
        confirmar?.addEventListener('input', validar);
    }

    /* ---------- Quitar el resaltado de error al corregir ---------- */
    document.querySelectorAll('.campo input').forEach(input => {
        input.addEventListener('input', () => {
            input.setAttribute('aria-invalid', 'false');
            input.classList.remove('invalido');
            const error = input.closest('.campo')?.querySelector('.error');
            if (error) error.textContent = '';
        });
    });
});
