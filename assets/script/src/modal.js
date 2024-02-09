import bootstrap from './bootstrap.bundle.js';

const dayModal = document.getElementById('dayModal');
if (dayModal) {
  dayModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const recipient = button.getAttribute('data-bs-whatever');
    const modalTitle = dayModal.querySelector('.modal-title');
    const modalInputDateElList = dayModal.querySelectorAll('.date-input');

    const date = new Date(recipient * 1000);
    modalTitle.textContent = `${date.getMonth() + 1}月${date.getDate()}日`;
    [...modalInputDateElList].map((modalInputDateEl) => {
      modalInputDateEl.value = date.toLocaleDateString(
        "ja-JP", {year: "numeric", month: "2-digit", day: "2-digit"}
      ).replaceAll('/', '-');
    });
  })
}

const audioModal = document.getElementById('audioModal');
if (audioModal) {
  audioModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const recipient = button.getAttribute('data-bs-whatever');
    const modalAudio = audioModal.querySelector('audio');

    modalAudio.src = recipient;
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