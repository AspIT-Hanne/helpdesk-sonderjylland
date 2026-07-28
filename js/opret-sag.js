import { addTicket } from '../api/add_ticket.js';

function initCreateDate() {
  const createdDateInput = document.getElementById('create-date');
  if (!createdDateInput) return;

  const [year, month, day] = new Date().toISOString().split('T')[0].split('-');
  const today = `${day}-${month}-${year}`;
  createdDateInput.value = today;
}

async function handleCreateSagSubmit() {
  const title = document.getElementById('create-title');
  const type = document.getElementById('create-type');

  if (title && !title.value.trim()) {
    showBottomMessage('Titel er et påkrævet felt.', 'warning');
    title.focus();
    return;
  }

  if (type && !type.value.trim()) {
    showBottomMessage('Type er et påkrævet felt.', 'warning');
    type.focus();
    return;
  }

  const description = document.getElementById('create-description');
  const place = document.getElementById('create-location');
    const statusInput = document.getElementById('sager-create-status');
  // Sikrer at der står noget brugbart i status også selvom brugeren ikke har valgt noget
  const status = statusInput ? (statusInput.value || 'Ikke-startet') : 'Ikke-startet';
  
  const priorityInput = document.getElementById('sager-create-priority');
  // Sikrer at der står noget brugbart i priority også selvom brugeren ikke har valgt noget
  const priority = priorityInput ? (priorityInput.value || 'Medium') : 'Medium';
  const createdby = document.getElementById('create-createdby');
  const assigned = document.getElementById('create-assigned');
  const created = document.getElementById('create-date');

   // Brug af den importerede addTicket funktion
    const result = await addTicket(title.value, description.value, place.value, type.value, priority.value, createdby.value, assigned.value, status.value)
      .then(result => {
        if (result === true) {
            showBottomMessage(`Sag ${title.value} oprettet`, 'success');
            setTimeout(() => {
                window.location.href = '/index.php';
            }, 2000);
        } else {
          showBottomMessage('Der opstod en fejl ved oprettelse af sagen.' + result.error, 'error');
        }
      })
      .catch(error => {
        showBottomMessage('Der opstod en uventet fejl: ' + error, 'error');
      });
}

function initAddSagButton() {
  const addBtn = document.getElementById('add-sag-btn');
  if (!addBtn) return;
  addBtn.addEventListener('click', handleCreateSagSubmit);
}


(function init() {
  initCreateDate();
  initAddSagButton() 
})();
