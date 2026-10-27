/* ============================================================
   public/JS/main.js
   Comportamiento del lado cliente en el dashboard.
   ============================================================ */

document.addEventListener('DOMContentLoaded', () => {

    /* ============================================================
       1. Confirmar eliminación de recetas
       ============================================================ */
    document.querySelectorAll('a[data-confirm]').forEach(link => {
        link.addEventListener('click', e => {
            const msg = link.getAttribute('data-confirm') || '¿Eliminar esta receta?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    /* ============================================================
       2. Formulario dinámico de ingredientes
       (receta_crear.php y receta_editar.php)
       ============================================================ */
    const contenedor = document.getElementById('ings');
    const btnAgregar = document.getElementById('add-ing');

    const UNIDADES = [
        'gramos', 'kilogramos', 'libras', 'onzas',
        'mililitros', 'litros', 'tazas', 'cucharadas',
        'cucharaditas', 'pizcas', 'unidad', 'unidades',
        'dientes', 'atados', 'rodajas', 'hojas',
        'latas', 'sobres', 'ramas'
    ];

    function crearFilaIngrediente(datos = {}) {
        const row = document.createElement('div');
        row.className = 'ing-row';

        const inputNombre = document.createElement('input');
        inputNombre.type = 'text';
        inputNombre.name = 'ing_nombre[]';
        inputNombre.placeholder = 'Ingrediente';
        inputNombre.value = datos.nombre || '';

        const inputCantidad = document.createElement('input');
        inputCantidad.type = 'number';
        inputCantidad.name = 'ing_cantidad[]';
        inputCantidad.placeholder = 'Cantidad';
        inputCantidad.step = '0.01';
        inputCantidad.min = '0';
        inputCantidad.value = datos.cantidad || '';

        const selectUnidad = document.createElement('select');
        selectUnidad.name = 'ing_unidad[]';
        UNIDADES.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u;
            opt.textContent = u;
            if (datos.unidad_medida === u) opt.selected = true;
            selectUnidad.appendChild(opt);
        });

        const btnQuitar = document.createElement('button');
        btnQuitar.type = 'button';
        btnQuitar.className = 'remove-ing';
        btnQuitar.textContent = '×';
        btnQuitar.title = 'Quitar ingrediente';
        btnQuitar.addEventListener('click', () => {
            if (contenedor.children.length > 1) {
                row.remove();
            } else {
                inputNombre.value = '';
                inputCantidad.value = '';
                selectUnidad.selectedIndex = 0;
            }
        });

        row.append(inputNombre, inputCantidad, selectUnidad, btnQuitar);
        return row;
    }

    if (contenedor && btnAgregar) {
        btnAgregar.addEventListener('click', () => {
            contenedor.appendChild(crearFilaIngrediente());
        });

        // Añadir botón "quitar" a filas existentes (las generadas por PHP).
        contenedor.querySelectorAll('.ing-row').forEach(row => {
            if (row.querySelector('.remove-ing')) return;

            const btnQuitar = document.createElement('button');
            btnQuitar.type = 'button';
            btnQuitar.className = 'remove-ing';
            btnQuitar.textContent = '×';
            btnQuitar.title = 'Quitar ingrediente';
            btnQuitar.addEventListener('click', () => {
                if (contenedor.children.length > 1) {
                    row.remove();
                } else {
                    row.querySelectorAll('input').forEach(i => i.value = '');
                }
            });
            row.appendChild(btnQuitar);
        });
    }

    /* ============================================================
       3. Contador de caracteres para descripción / instrucciones
       ============================================================ */
    document.querySelectorAll('textarea[data-max]').forEach(ta => {
        const max = parseInt(ta.getAttribute('data-max'), 10);
        const contador = document.createElement('small');
        contador.className = 'contador';
        contador.style.color = 'var(--cookly-muted)';
        contador.style.fontSize = '0.75rem';
        contador.style.alignSelf = 'flex-end';

        const actualizar = () => {
            const len = ta.value.length;
            contador.textContent = `${len} / ${max}`;
            contador.style.color = len > max ? 'var(--cookly-error)' : 'var(--cookly-muted)';
        };

        ta.addEventListener('input', actualizar);
        ta.parentElement.appendChild(contador);
        actualizar();
    });

    /* ============================================================
       4. Auto-cierre de mensajes de éxito
       ============================================================ */
    document.querySelectorAll('.estado.exito').forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 4000);
    });

    /* ============================================================
       5. Confirmación al enviar formulario de eliminación
       (por si se accede desde un botón form en receta_eliminar.php)
       ============================================================ */
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            const msg = form.getAttribute('data-confirm') || '¿Estás seguro?';
            if (!confirm(msg)) e.preventDefault();
        });
    });
});