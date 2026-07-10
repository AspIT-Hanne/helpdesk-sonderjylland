function initCreateDate() {
  const createdDateInput = document.getElementById('create-date');
  if (!createdDateInput) return;

  const today = new Date().toISOString().split('T')[0];
  createdDateInput.value = today;
}

(function init() {
  initCreateDate();
})();
