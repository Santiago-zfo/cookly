/* ============================================================
   public/JS/auth.js
   Mostrar/ocultar contraseñas, barra de seguridad, requisitos
   obligatorios y validación de los formularios de acceso.
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ---------- Botones "Mostrar / Ocultar" ---------- */
    document.querySelectorAll('button.ver').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.getAttribute('aria-controls'));
            if (!input) return;

            const esPassword = input.type === 'password';
            input.type = esPassword ? 'text' : 'password';
            btn.textContent = esPassword ? 'Ocultar' : 'Mostrar';
            btn.setAttribute('aria-pressed', String(esPassword));
        });
    });

    const clave = document.getElementById('reg-clave');
    const confirmar = document.getElementById('reg-confirmar');

    /* ---------- Registro: barra de seguridad y requisitos ---------- */
    if (clave) {
        const medidor = document.getElementById('medidor-clave');
        const barras = Array.from(medidor?.querySelectorAll('.medidor-barras span') || []);
        const barraContinua = medidor?.querySelector('.medidor-barra');
        const relleno = medidor?.querySelector('.medidor-barra-relleno');
        const texto = medidor?.querySelector('.medidor-texto');

        const reglas = {
            longitud: valor => valor.length >= 8,
            mayuscula: valor => /[A-ZÁÉÍÓÚÑ]/.test(valor),
            minuscula: valor => /[a-záéíóúñ]/.test(valor),
            numero: valor => /\d/.test(valor),
            simbolo: valor => /[^\p{L}\p{N}]/u.test(valor)
        };
        const etiquetas = ['Débil', 'Aceptable', 'Fuerte', 'Muy fuerte'];

        const actualizarMedidor = () => {
            const valor = clave.value;
            const cumplidas = Object.keys(reglas).filter(regla => reglas[regla](valor)).length;
            const nivel = valor === '' ? 0 : cumplidas <= 2 ? 1 : cumplidas === 3 ? 2 : cumplidas === 4 ? 3 : 4;
            const porcentaje = Math.round((cumplidas / 5) * 100);

            if (medidor) medidor.dataset.nivel = String(nivel);
            barras.forEach((barra, i) => barra.classList.toggle('activa', i < nivel));
            if (relleno) relleno.style.width = porcentaje + '%';
            barraContinua?.setAttribute('aria-valuenow', String(porcentaje));
            if (texto) {
                texto.textContent = valor === ''
                    ? 'Escribe una contraseña para ver su seguridad'
                    : 'Contraseña ' + etiquetas[nivel - 1].toLowerCase();
            }
            clave.classList.toggle('clave-debil', nivel === 1);

            document.querySelectorAll('#requisitos-clave li').forEach(li => {
                const regla = reglas[li.dataset.regla];
                li.classList.toggle('cumplido', regla ? regla(valor) : false);
            });
        };

        const validarRequisitos = () => {
            if (!clave.value) {
                clave.setCustomValidity('');
                return;
            }
            const incompleto = Object.keys(reglas).some(regla => !reglas[regla](clave.value));
            clave.setCustomValidity(incompleto ? 'Contraseña débil: debe cumplir todos los requisitos.' : '');
        };

        const validarCoincidencia = () => {
            if (!confirmar) return;
            const ok = clave.value === confirmar.value;
            confirmar.setCustomValidity(ok ? '' : 'Las contraseñas no coinciden');
        };

        clave.addEventListener('input', () => {
            actualizarMedidor();
            validarRequisitos();
            validarCoincidencia();
        });
        confirmar?.addEventListener('input', validarCoincidencia);

        actualizarMedidor();
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
