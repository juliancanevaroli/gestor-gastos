const selectTipo = document.getElementById('tipo');
const selectCategoria = document.getElementById('categoria_id');

function filtrarCategorias(tipo) {
    const options = selectCategoria.querySelectorAll('option');
    options.forEach(opt => {
        if (!opt.value) return; 
        opt.hidden = opt.dataset.tipo !== tipo;
    });
    selectCategoria.value = '';
}

selectTipo.addEventListener('change', () => {
    filtrarCategorias(selectTipo.value);
});