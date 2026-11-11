document.addEventListener('DOMContentLoaded', () => {

    /* 1. Confirmar eliminación */
    document.querySelectorAll('a[data-confirm]').forEach(link => {
        link.addEventListener('click', e => {
            const msg = link.getAttribute('data-confirm') || '¿Eliminar esta receta?';
            if (!confirm(msg)) e.preventDefault();
        });
    });

    /* 2. Formulario dinámico de ingredientes */
    const contenedor = document.getElementById('ings');
    const btnAgregar = document.getElementById('add-ing');

    const UNIDADES = ['gramos','kilogramos','libras','onzas','mililitros','litros','tazas','cucharadas','cucharaditas','pizcas','unidad','unidades','dientes','atados','rodajas','hojas','latas','sobres','ramas'];

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
        inputCantidad.placeholder = 'Cant.';
        inputCantidad.step = '0.01';
        inputCantidad.min = '0.1';
        // AGREGA ESTA LÍNEA: Para que por defecto la cantidad sea 1 y no se envíe vacío
        inputCantidad.value = datos.cantidad || '1'; 

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
        btnQuitar.innerHTML = '&times;';
        btnQuitar.title = 'Quitar';
        btnQuitar.addEventListener('click', () => {
            if (contenedor.children.length > 1) {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => row.remove(), 300);
            } else {
                inputNombre.value = '';
                inputCantidad.value = '1'; // También resetea a 1
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

        contenedor.querySelectorAll('.ing-row').forEach(row => {
            if (row.querySelector('.remove-ing')) return;
            const btnQuitar = document.createElement('button');
            btnQuitar.type = 'button';
            btnQuitar.className = 'remove-ing';
            btnQuitar.innerHTML = '&times;';
            btnQuitar.addEventListener('click', () => {
                if (contenedor.children.length > 1) row.remove();
                else {
                    row.querySelectorAll('input[type="text"]').forEach(i => i.value = '');
                    row.querySelectorAll('input[type="number"]').forEach(i => i.value = '1');
                }
            });
            row.appendChild(btnQuitar);
        });
    }

    /* 3. Animación en cascada para las tarjetas de recetas */
    const cards = document.querySelectorAll('.recipe-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });

    /* 4. Loader en formularios */
    const recipeForms = document.querySelectorAll('form[enctype="multipart/form-data"]');
    recipeForms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner"></span> Guardando...';
                submitBtn.disabled = true;
            }
        });
    });

    /* 5. BUSCADOR EN TIEMPO REAL (AJAX) */
    const searchInput = document.getElementById('input-busqueda');
    const contenedorRecetas = document.getElementById('contenedor-recetas');

    if (searchInput && contenedorRecetas) {
        const htmlOriginal = contenedorRecetas.innerHTML;
        let timeoutId;

        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value.trim();

            if (query === '') {
                contenedorRecetas.innerHTML = htmlOriginal;
                return;
            }

            timeoutId = setTimeout(() => {
                fetch(`ajax_buscar.php?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.html === '') {
                            contenedorRecetas.innerHTML = htmlOriginal;
                        } else {
                            contenedorRecetas.innerHTML = `<div class="recipe-grid">${data.html}</div>`;
                        }
                    })
                    .catch(err => console.error('Error en búsqueda:', err));
            }, 300); 
        });
    }

});

/* 6. Función para el Menú Hamburguesa (fuera del DOMContentLoaded para que sea global) */
function toggleMenu() {
    const nav = document.getElementById('nav-container');
    if (nav) {
        nav.classList.toggle('active');
    }
}