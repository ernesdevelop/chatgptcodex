const STORAGE_KEY = "clients";

const form = document.getElementById("client-form");
const idField = document.getElementById("client-id");
const nameField = document.getElementById("name");
const emailField = document.getElementById("email");
const formTitle = document.getElementById("form-title");
const saveButton = document.getElementById("save-button");
const cancelButton = document.getElementById("cancel-button");
const clientList = document.getElementById("client-list");
const emptyState = document.getElementById("empty-state");

function readClients() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) ?? [];
  } catch (_error) {
    return [];
  }
}

function writeClients(clients) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(clients));
}

function resetForm() {
  idField.value = "";
  formTitle.textContent = "Add Client";
  saveButton.textContent = "Add Client";
  cancelButton.classList.add("hidden");
  form.reset();
  nameField.focus();
}

function startEdit(id) {
  const clients = readClients();
  const client = clients.find((item) => item.id === id);

  if (!client) {
    return;
  }

  idField.value = client.id;
  nameField.value = client.name;
  emailField.value = client.email;
  formTitle.textContent = "Edit Client";
  saveButton.textContent = "Save Changes";
  cancelButton.classList.remove("hidden");
  nameField.focus();
}

function deleteClient(id) {
  const clients = readClients();
  const updatedClients = clients.filter((item) => item.id !== id);
  writeClients(updatedClients);

  if (idField.value === id) {
    resetForm();
  }

  renderClients();
}

function renderClients() {
  const clients = readClients();
  clientList.innerHTML = "";

  emptyState.classList.toggle("hidden", clients.length > 0);

  for (const client of clients) {
    const item = document.createElement("li");
    item.className = "client-item";
    item.innerHTML = `
      <div>
        <strong>${client.name}</strong>
        <p>${client.email}</p>
      </div>
      <div class="actions">
        <button type="button" data-action="edit" data-id="${client.id}" class="secondary">Edit</button>
        <button type="button" data-action="delete" data-id="${client.id}" class="danger">Delete</button>
      </div>
    `;

    clientList.append(item);
  }
}

form.addEventListener("submit", (event) => {
  event.preventDefault();

  const clients = readClients();
  const payload = {
    name: nameField.value.trim(),
    email: emailField.value.trim(),
  };

  if (!payload.name || !payload.email) {
    return;
  }

  if (idField.value) {
    const index = clients.findIndex((client) => client.id === idField.value);
    if (index >= 0) {
      clients[index] = { ...clients[index], ...payload };
    }
  } else {
    clients.push({
      id: crypto.randomUUID(),
      ...payload,
    });
  }

  writeClients(clients);
  resetForm();
  renderClients();
});

cancelButton.addEventListener("click", resetForm);

clientList.addEventListener("click", (event) => {
  const button = event.target.closest("button");

  if (!button) {
    return;
  }

  const { action, id } = button.dataset;

  if (action === "edit") {
    startEdit(id);
  }

  if (action === "delete") {
    deleteClient(id);
  }
});

renderClients();
