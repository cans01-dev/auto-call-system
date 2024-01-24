const dayModal = document.getElementById('dayModal');
if (dayModal) {
  dayModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const recipient = button.getAttribute('data-bs-whatever');
    const modalTitle = dayModal.querySelector('.modal-title');

    const date = new Date(recipient * 1000);
    modalTitle.textContent = date.getMonth() + 1 + "月" + date.getDate() + "日";
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