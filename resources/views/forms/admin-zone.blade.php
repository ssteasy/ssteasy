<p>Usuario con el que el colaborador asignado entrará a la plataforma</p>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectEl = document.getElementById('mi-select-empresa');
  if (!selectEl) return;

  // Oculta el <div data-field-wrapper> que lo envuelve
  const wrapper = selectEl.closest('[data-field-wrapper]');
  if (wrapper) {
    wrapper.style.display = 'none';
  }
});

</script>