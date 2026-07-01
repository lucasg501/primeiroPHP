<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova categoria · Catálogo</title>
<link rel="stylesheet" href="../assets/css/categoria.css">
</head>
<body>

<div class="wrap wrap--narrow">

  <a href="listar.php" class="back-link">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    Voltar para categorias
  </a>

  <span class="eyebrow"><span class="eyebrow__dot"></span>Catálogo · Novo registro</span>

  <h1 class="page-title">Cadastrar categoria</h1>
  <p class="page-sub">Dê um nome claro — ele será usado para agrupar os produtos desse tipo.</p>

  <form class="form-card" id="formCadastro" novalidate>
    <div class="field">
      <label for="nomeCategoria">Nome da categoria</label>
      <input type="text" id="nomeCategoria" name="nomeCategoria" placeholder="Ex.: Eletrônicos" autocomplete="off" maxlength="120" autofocus>
      <p class="field-hint" id="hintNome">Obrigatório.</p>
    </div>

    <div class="form-actions">
      <a href="listar.php" class="btn btn--ghost">Cancelar</a>
      <button type="submit" class="btn btn--accent" id="btnSalvar">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        Salvar categoria
      </button>
    </div>
  </form>

</div>

<script src="../assets/js/api.js"></script>
<script>
  const form = document.getElementById('formCadastro');
  const input = document.getElementById('nomeCategoria');
  const hint = document.getElementById('hintNome');
  const btnSalvar = document.getElementById('btnSalvar');

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
      await CategoriaAPI.gravar(input.value.trim());
      sessionStorage.setItem('categoriaMsg', 'Categoria cadastrada com sucesso.');
      window.location.href = 'listar.php';
    } catch (erro) {
      mostrarToast(erro.message, 'error');
      btnSalvar.disabled = false;
      btnSalvar.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Salvar categoria';
    }
  });
</script>

</body>
</html>
