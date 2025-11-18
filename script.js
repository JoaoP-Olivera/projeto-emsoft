/* -------- helpers UI -------- */
function showAlert(msg, type='success', timeout=3500) {
  const id = 'a' + Date.now();
  const wrap = document.getElementById('alerts');
  const el = document.createElement('div');
  el.id = id;
  el.className = `alert alert-${type} shadow-sm`;
  el.textContent = msg;
  wrap.appendChild(el);

  requestAnimationFrame(() => el.classList.add('show'));

  setTimeout(() => {
    el.classList.remove('show');
    setTimeout(() => el.remove(), 400);
  }, timeout);
}

function showLoading() {
  if (!document.getElementById('loadingOverlay')) {
    document.body.insertAdjacentHTML('beforeend', `
      <div id="loadingOverlay"
           class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50"
           style="z-index:9999">
        <div class="spinner-border text-light" role="status"></div>
      </div>`);
  }
}

function hideLoading() {
  document.getElementById('loadingOverlay')?.remove();
}

const cepForm = document.getElementById('cepForm');
const cepInput = document.getElementById('cepInput');
const enderecoArea = document.getElementById('enderecoArea');
const saveForm = document.getElementById('saveForm');

function isValidCep(v) {
  return /^\d{8}$/.test(v);
}

function preencherCampos(data) {
  document.getElementById('endereco').value = data.logradouro || '';
  document.getElementById('bairro').value = data.bairro || '';
  document.getElementById('cidade').value = data.localidade || '';
  document.getElementById('estado').value = data.uf || '';
  document.getElementById('pais').value = 'Brasil';
}

/* ------- Eventos ------- */
cepForm.addEventListener('submit', async function(e){
  e.preventDefault();
  const cep = (cepInput.value || '').replace(/\D/g, '');

  if (!isValidCep(cep)) {
    showAlert('Digite um CEP válido com 8 dígitos.', 'danger');
    return;
  }

  showLoading();
  try {
    const res = await fetch('https://viacep.com.br/ws/' + cep + '/json/');
    const data = await res.json();
    hideLoading();

    if (data.erro) {
      showAlert('CEP não encontrado.', 'danger');
      enderecoArea.style.display = 'none';
      return;
    }

    preencherCampos(data);
    enderecoArea.style.display = 'block';
    showAlert('Endereço encontrado. Revise os campos se necessário.', 'success');

  } catch (err) {
    hideLoading();
    showAlert('Erro na consulta do ViaCEP.', 'danger');
  }
});

saveForm.addEventListener('submit', async function(e){
  e.preventDefault();

   const dateNow = new Date();
   const dateFormated = dateNow.toISOString().slice(0, 19);

  const payload = {
    cep: (cepInput.value || '').replace(/\D/g, ''),
    endereco: document.getElementById('endereco').value.trim(),
    bairro: document.getElementById('bairro').value.trim(),
    cidade: document.getElementById('cidade').value.trim(),
    estado: document.getElementById('estado').value.trim(),
    pais: document.getElementById('pais').value.trim(),
    dataHora: dateFormated
  };

  if (!isValidCep(payload.cep)) {
    showAlert('CEP inválido.', 'danger');
    return;
  }

  showLoading();
  try {
    const res = await fetch('http://localhost:8000/Backend/api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    const json = await res.json();
    hideLoading();

    if (json.statusCode === 201) {
      showAlert(json.message || 'Cadastro salvo com sucesso!', 'success');
      enderecoArea.style.display = 'none';
      cepForm.reset();
      saveForm.reset();
    } else {
      showAlert(json.message || 'Erro ao salvar.', 'danger');
    }

  } catch (err) {
    hideLoading();
    showAlert('Erro ao conectar com o servidor.', 'danger');
  }
});
