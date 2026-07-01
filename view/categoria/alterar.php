<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alterar categoria · Catálogo</title>
<link rel="stylesheet" href="../assets/css/categoria.css">
</head>
<body>

<div class="wrap wrap--narrow">

  <a href="listar.php" class="back-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Voltar para categorias
  </a>

  <span class="eyebrow" id="eyebrowId"><span class="eyebrow__dot"></span>Catálogo · Carregando…</span>

  <h1 class="page-title">Alterar categoria</h1>
  <p class="page-sub">Atualize o nome abaixo. As mudanças refletem em todos os produtos vinculados.</p>

  <div id="areaConteudo">
    <div class="form-card">
      <div class="field">
        <label>Nome da categoria</label>
        <div class="field-skeleton"></div>
      </div>
    </div>
  </div>

</div>

<script src="../assets/js/api.js"></script>
<script>
  const params = new URLSearchParams(window.location.search);
  const idCategoria = parseInt(params.get('id'), 10);
  const eyebrowId = document.getElementById('eyebrowId');
  const areaConteudo = document.getElementById('areaConteudo');

  function formatarId(id) {
    return '#' + String(id).padStart(3, '0');
  }

  function renderizarErro(mensagem) {
    areaConteudo.innerHTML = `
      <div class="card">
        <div class="state">
          <svg class="state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <p class="state__title">Não foi possível carregar</p>
          <p class="state__text">${escaparHtml(mensagem)}</p>
          <a href="listar.php" class="btn btn--ghost">Voltar para categorias</a>
        </div>
      </div>`;
  }

  function renderizarForm(categoria) {
    eyebrowId.innerHTML = `<span class="eyebrow__dot"></span>Catálogo · ${formatarId(categoria.idCategoria)}`;

    areaConteudo.innerHTML = `
      <form class="form-card" id="formAlterar" novalidate>
        <div class="field">
          <label for="nomeCategoria">Nome da categoria</label>
          <input type="text" id="nomeCategoria" name="nomeCategoria" maxlength="120" autocomplete="off" autofocus>
          <p class="field-hint" id="hintNome">Obrigatório.</p>
        </div>

        <div class="form-actions">
          <a href="listar.php" class="btn btn--ghost">Cancelar</a>
          <button type="submit" class="btn btn--accent" id="btnSalvar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Salvar alterações
          </button>
        </div>
      </form>`;

    const input = document.getElementById('nomeCategoria');
    const hint = document.getElementById('hintNome');
    const btnSalvar = document.getElementById('btnSalvar');
    const form = document.getElementById('formAlterar');

    input.value = categoria.nomeCategoria;

    function validar() {
      const valor = input.value.trim();
      if (!valor) {
        input.classList.add('field--invalid');
        hint.textContent = 'Informe o nome da categoria.';
        hint.classList.add('field-hint--error');
        return false;
      }
      input.classList.remove('field--invalid');
      hint.textContent = 'Obrigatório.';
      hint.classList.remove('field-hint--error');
      return true;
    }

    input.addEventListener('input', () => {
      if (input.classList.contains('field--invalid')) validar();
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (!validar()) {
        input.focus();
        return;
      }

      btnSalvar.disabled = true;
      btnSalvar.textContent = 'Salvando…';

      try {
        await CategoriaAPI.alterar(categoria.idCategoria, input.value.trim());
        sessionStorage.setItem('categoriaMsg', 'Categoria alterada com sucesso.');
        window.location.href = 'listar.php';
      } catch (erro) {
        mostrarToast(erro.message, 'error');
        btnSalvar.disabled = false;
        btnSalvar.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Salvar alterações';
      }
    });
  }

  async function carregarCategoria() {
    if (!idCategoria || idCategoria <= 0) {
      renderizarErro('ID de categoria inválido.');
      return;
    }

    try {
      const categoria = await CategoriaAPI.obter(idCategoria);
      renderizarForm(categoria);
    } catch (erro) {
      renderizarErro(erro.message);
    }
  }

  carregarCategoria();
</script>

</body>
</html>
