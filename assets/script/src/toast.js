import bootstrap from './bootstrap.bundle.js';

const toastElList = document.querySelectorAll(".toast");
const toastList = [...toastElList].map((toastEl) => {
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
});