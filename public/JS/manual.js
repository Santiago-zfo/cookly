/* Mejora progresiva: el manual, las imágenes y el PDF funcionan sin JavaScript. */
(() => {
    'use strict';
    const root = document.querySelector('.manual-root');
    if (!root) return;
    const search = document.getElementById('manual-busqueda');
    const clear = document.getElementById('manual-limpiar');
    const status = document.getElementById('manual-estado');
    const empty = document.getElementById('manual-sin-resultados');
    const chapters = [...document.querySelectorAll('.manual-chapter')];
    const items = [...document.querySelectorAll('[data-index-item]')];
    const normalize = text => text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('es');
    const texts = chapters.map(ch => normalize(ch.textContent + ' ' + ch.dataset.keywords));
    const index = document.getElementById('manual-indice');
    const toggle = document.getElementById('manual-toggle-indice');
    const mobile = window.matchMedia('(max-width: 760px)');

    function filter() {
        const terms = normalize(search.value.trim()).split(/\s+/).filter(Boolean);
        let count = 0;
        chapters.forEach((chapter, i) => {
            const match = terms.every(term => texts[i].includes(term));
            chapter.hidden = !match;
            items[i].hidden = !match;
            if (match) count++;
        });
        clear.hidden = !search.value;
        empty.hidden = count > 0;
        status.textContent = terms.length
            ? `${count} ${count === 1 ? 'capítulo encontrado' : 'capítulos encontrados'}. El PDF siempre incluye el manual completo.`
            : `${chapters.length} capítulos · Capturas reales con datos de prueba · Haz clic en una imagen para ampliarla.`;
        updateChapterLinks();
    }
    function reset() { search.value = ''; filter(); }
    function updateChapterLinks() {
        const visible = chapters.filter(ch => !ch.hidden);
        visible.forEach((chapter, i) => {
            chapter.querySelectorAll('[data-chapter-direction]').forEach(link => {
                const target = visible[i + Number(link.dataset.chapterDirection)];
                link.href = target ? '#' + target.id : '#manual-portada';
                link.textContent = target ? (link.dataset.chapterDirection === '1' ? 'Siguiente →' : '← Anterior') : '↑ Portada';
            });
        });
    }
    search.addEventListener('input', filter);
    search.addEventListener('keydown', event => { if (event.key === 'Escape') { reset(); search.focus(); } });
    clear.addEventListener('click', () => { reset(); search.focus(); });
    document.querySelectorAll('[data-manual-reset]').forEach(button => button.addEventListener('click', reset));

    function setIndex(open) { index.hidden = !open; toggle.setAttribute('aria-expanded', String(open)); toggle.textContent = open ? 'Ocultar índice' : 'Ver índice'; }
    function sizeIndex() { toggle.hidden = !mobile.matches; setIndex(!mobile.matches); }
    toggle.addEventListener('click', () => setIndex(index.hidden));
    mobile.addEventListener('change', sizeIndex);
    sizeIndex();
    root.addEventListener('click', event => {
        const anchor = event.target.closest('a[href^="#"]');
        if (!anchor) return;
        const target = document.getElementById(anchor.hash.slice(1));
        if (target && target.hidden && target.classList.contains('manual-chapter')) reset();
        if (anchor.closest('#manual-indice') && mobile.matches) setIndex(false);
    });
    window.addEventListener('hashchange', () => {
        const target = document.getElementById(location.hash.slice(1));
        if (target?.hidden && target.classList.contains('manual-chapter')) { reset(); target.scrollIntoView(); }
    });
    const topbar = document.querySelector('.topbar');
    const measure = () => document.body.style.setProperty('--manual-offset', `${Math.ceil(topbar.getBoundingClientRect().height) + 24}px`);
    new ResizeObserver(measure).observe(topbar);
    measure();
    const observer = new IntersectionObserver(entries => {
        const entry = entries.filter(e => e.isIntersecting && !e.target.hidden).sort((a,b) => a.boundingClientRect.top - b.boundingClientRect.top)[0];
        if (!entry) return;
        items.forEach(item => {
            const link = item.querySelector('a');
            if (item.dataset.indexItem === entry.target.id) link.setAttribute('aria-current','location');
            else link.removeAttribute('aria-current');
        });
    }, { rootMargin: '-15% 0px -65% 0px', threshold: 0 });
    chapters.forEach(chapter => observer.observe(chapter));

    const dialog = document.getElementById('manual-visor');
    let opener = null;
    if (typeof dialog.showModal === 'function') {
        root.querySelectorAll('[data-manual-image]').forEach(link => link.addEventListener('click', event => {
            event.preventDefault();
            opener = link;
            const image = document.getElementById('manual-visor-img');
            image.src = link.href;
            image.alt = link.querySelector('img').alt;
            document.getElementById('manual-visor-caption').textContent = link.dataset.caption;
            document.getElementById('manual-visor-original').href = link.href;
            dialog.showModal();
        }));
        document.getElementById('manual-cerrar-visor').addEventListener('click', () => dialog.close());
        dialog.addEventListener('click', event => { if (event.target === dialog) { const box = dialog.getBoundingClientRect(); if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) dialog.close(); } });
        dialog.addEventListener('close', () => opener?.focus({preventScroll:true}));
    }
    const printButton = document.getElementById('manual-imprimir');
    printButton.hidden = false;
    printButton.addEventListener('click', () => window.print());
    let printState;
    window.addEventListener('beforeprint', () => {
        printState = {query: search.value, faq: [...root.querySelectorAll('details')].map(d => d.open)};
        reset();
        root.querySelectorAll('details').forEach(d => d.open = true);
        root.querySelectorAll('img[loading]').forEach(img => img.loading = 'eager');
    });
    window.addEventListener('afterprint', () => {
        if (!printState) return;
        search.value = printState.query;
        filter();
        root.querySelectorAll('details').forEach((d,i) => d.open = printState.faq[i]);
    });
})();
