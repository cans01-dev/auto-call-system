import bootstrap from './bootstrap.bundle.js';

const popoverElList = document.querySelectorAll('.popover-trigger');
const popoverList = [...popoverElList].map((popoverEl) => {
  const popover = new bootstrap.Popover(popoverEl);
});