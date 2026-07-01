<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Categorias · Catálogo</title>
<link rel="stylesheet" href="../assets/css/categoria.css">
</head>
<body>

<div class="wrap">

  <span class="eyebrow"><span class="eyebrow__dot"></span>Catálogo · 01</span>

  <a href="/view/index.php">
    <h1 class="page-title">
      <button class="btn btn-primary">Página Inicial</button>
    </h1>
  </a>

  <div class="page-header">
    <div>
      <h1 class="page-title">Categorias</h1>
      <p class="page-sub">Todas as categorias cadastradas no sistema, prontas para organizar seus produtos.</p>
    </div>
    <a href="cadastrar.php" class="btn btn--accent">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      Nova categoria
    </a>
  </div>

  <hr class="rule">

  <div class="card">
    <div class="table-head">
      <span>ID</span>
      <span>Nome</span>
      <span style="text-align:right">Ações</span>
    </div>
    <div id="corpoTabela">
      <div class="state">
        <div class="spinner"></div>
        <p class="state__text">Carregando categorias…</p>
      </div>
    </div>
  </div>

</div>

<!-- Modal de confirmação de exclusão -->
<div id="modalExcluir" class="modal-backdrop" style="display:none;">
  <div class="modal">
    <div class="modal__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
    </div>
    <h3>Excluir categoria?</h3>
    <p id="modalTexto">Essa ação não pode ser desfeita.</p>
    <div class="modal__actions">
      <button class="btn btn--ghost" id="btnCancelarExclusao">Cancelar</button>
      <button class="btn btn--primary" id="btnConfirmarExclusao" style="background:var(--color-danger)">Excluir</button>
    </div>
  </div>
</div>

<script src="../assets/js/api/httpClient.js"></script>
<script src="../assets/js/api/categoriaApi.js"></script>
<script src="../assets/js/utils/helpers.js"></script>
<script src="../assets/js/utils/toast.js"></script>

<script>
  const corpoTabela = document.getElementById('corpoTabela');
  const modalExcluir = document.getElementById('modalExcluir');
  const modalTexto = document.getElementById('modalTexto');
  const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
  const btnConfirmarExclusao = document.getElementById('btnConfirmarExclusao');

  let idParaExcluir = null;

  const iconeEditar = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>';
  const iconeExcluir = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6M10 11v6M14 11v6"/></svg>';

  function estadoVazio() {
    corpoTabela.innerHTML = `
      <div class="state">
        <svg class="state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M4 10h16M10 4v16"/></svg>
        <p class="state__title">Nenhuma categoria ainda</p>
        <p class="state__text">Comece cadastrando a primeira categoria do seu catálogo.</p>
        <a href="cadastrar.php" class="btn btn--accent">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Nova categoria
        </a>
      </div>`;
  }

  function estadoErro(mensagem) {
    corpoTabela.innerHTML = `
      <div class="state">
        <svg class="state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
        <p class="state__title">Não foi possível carregar</p>
        <p class="state__text">${escaparHtml(mensagem)}</p>
        <button class="btn btn--ghost" onclick="carregarCategorias()">Tentar novamente</button>
      </div>`;
  }

  function formatarId(id) {
    return '#' + String(id).padStart(3, '0');
  }

  function renderizarLista(categorias) {
    if (!categorias || categorias.length === 0) {
      estadoVazio();
      return;
    }

    corpoTabela.innerHTML = categorias.map((cat) => `
      <div class="row" data-id="${cat.idCategoria}">
        <span class="row__id">${formatarId(cat.idCategoria)}</span>
        <span class="row__name">${escaparHtml(cat.nomeCategoria)}</span>
        <span class="row__actions">
          <a class="btn btn--ghost btn--icon" href="alterar.php?id=${cat.idCategoria}" title="Alterar">${iconeEditar}</a>
          <button class="btn btn--danger-ghost btn--icon" title="Excluir" onclick="pedirExclusao(${cat.idCategoria}, '${escaparHtml(cat.nomeCategoria).replace(/'/g, "\\'")}')">${iconeExcluir}</button>
        </span>
      </div>
    `).join('');
  }

  async function carregarCategorias() {
    corpoTabela.innerHTML = `
      <div class="state">
        <div class="spinner"></div>
        <p class="state__text">Carregando categorias…</p>
      </div>`;

    try {
      const categorias = await CategoriaAPI.listar();
      renderizarLista(categorias);
    } catch (erro) {
      estadoErro(erro.message);
    }
  }

  function pedirExclusao(id, nome) {
    idParaExcluir = id;
    modalTexto.textContent = `Tem certeza que deseja excluir "${nome}"? Essa ação não pode ser desfeita.`;
    modalExcluir.style.display = 'flex';
  }

  function fecharModal() {
    modalExcluir.style.display = 'none';
    idParaExcluir = null;
  }

  btnCancelarExclusao.addEventListener('click', fecharModal);
  modalExcluir.addEventListener('click', (e) => {
    if (e.target === modalExcluir) fecharModal();
  });

  btnConfirmarExclusao.addEventListener('click', async () => {
    if (idParaExcluir === null) return;

    btnConfirmarExclusao.disabled = true;
    btnConfirmarExclusao.textContent = 'Excluindo…';

    try {
      await CategoriaAPI.excluir(idParaExcluir);
      mostrarToast('Categoria excluída com sucesso.', 'success');
      fecharModal();
      carregarCategorias();
    } catch (erro) {
      mostrarToast(erro.message, 'error');
    } finally {
      btnConfirmarExclusao.disabled = false;
      btnConfirmarExclusao.textContent = 'Excluir';
    }
  });

  (function exibirMensagemPendente() {
    const msg = sessionStorage.getItem('categoriaMsg');
    if (msg) {
      mostrarToast(msg, 'success');
      sessionStorage.removeItem('categoriaMsg');
    }
  })();

  carregarCategorias();
</script>

</body>
</html>
