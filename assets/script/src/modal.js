const dayModal = document.getElementById('dayModal');
if (dayModal) {
  dayModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget
    const recipient = button.getAttribute('data-bs-whatever');
    const modalTitle = dayModal.querySelector('.modalBodyMsg');
    modalTitle.textContent = `New message to ${recipient}`;
  })
}

if (location.pathname === "/home") {
  console.log('/home');
  const surveysCreateModalEl = document.getElementById('surveysCreateModal');
  const surveysCreateModal = new bootstrap.Modal(surveysCreateModalEl);
  surveysCreateModalEl.addEventListener('hide.bs.modal', function() {
    location.hash = '';
  });

  if (location.hash === '#create') {
    surveysCreateModal.show();
  }

  window.addEventListener('hashchange', function() {
    if (location.hash === '#create') {
      surveysCreateModal.show();
    }
  });
}