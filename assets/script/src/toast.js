const toastElList = document.querySelectorAll(".toast");
const toastList = [...toastElList].map((toastEl) => {
  const toast = new bootstrap.Toast(toastEl);
  toast.show();
});

console.log("toast.js imported");