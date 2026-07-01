<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos · Catálogo</title>
  <link rel="stylesheet" href="../assets/css/categoria.css">
</head>

<body>

  <div class="wrap">
    <span class="eyebrow"><span class="eyebrow_dot"></span>Catálogo · 01</span>

    <a href="/view/index.php">
      <button class="btn btn--primary">Página Inicial</button>
    </a>

    <div class="page-header">
      <div>
        <h1 class="page-title">Produtos</h1>
        <p class="page-sub">Todos os produtos cadastrados no sistema.</p>
      </div>

      <a href="cadastrar.php" class="btn btn--accent">Cadastrar
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 5v14M5 12h14" />
        </svg>
      </a>
    </div>

    <hr class="rule">

    <div class="card">
      <div class="table-head">
        <span>ID</span>
        <span>Produto</span>
        <span>Valor</span>
        <span>Categoria</span>
        <span style="text-align: right;">Ações</span>
      </div>
      <div id="corpoTabela">
        <div class="state">
          <div class="spinner"></div>
          <p class="state__text">Carregando produtos...</p>
        </div>
      </div>
    </div>

  </div>

  <!-- Modal de confirmação de exclusão -->
  <div id="modalExcluir" class="modal-backdrop" style="display:none;">
    <div class="modal">
      <div class="modal__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6" />
        </svg>
      </div>
      <h3>Excluir Produto?</h3>
      <p id="modalTexto">Essa ação não pode ser desfeita.</p>
      <div class="modal__actions">
        <button class="btn btn--ghost" id="btnCancelarExclusao">Cancelar</button>
        <button class="btn btn--primary" id="btnConfirmarExclusao" style="background:var(--color-danger)">Excluir</button>
      </div>
    </div>
  </div>

  <script src="../assets/js/api/httpClient.js"></script>
  <script src="../assets/js/api/ProdutoApi.js"></script>
  <script src="../assets/js/api/CategoriaApi.js"></script>
  <script src="../assets/js/utils/helpers.js"></script>
  <script src="../assets/js/utils/toast.js"></script>

  <script>
    const corpoTabela = document.getElementById('corpoTabela');
    const modalExcluir = document.getElementById('modalExcluir');
    const modalTexto = document.getElementById('modalTexto');
    const btnCancelarExclusao = document.getElementById('btnCancelarExclusao');
    const btnConfirmarExclusao = document.getElementById('btnConfirmarExclusao');

    let idParaExcluir = null;

    // Mapa de categorias (id -> nome)
    const mapaCategorias = new Map();

    const iconeEditar = `
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 20h9M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/>
</svg>`;

    const iconeExcluir = `
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6M10 11v6M14 11v6"/>
</svg>`;

    function estadoVazio() {
      corpoTabela.innerHTML = `
    <div class="state">
        <svg class="state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <path d="M4 4h16v16H4z"/>
            <path d="M4 10h16M10 4v16"/>
        </svg>

        <p class="state__title">Nenhum produto ainda</p>

        <p class="state__text">
            Comece cadastrando o primeiro produto do seu catálogo.
        </p>

        <a href="cadastrar.php" class="btn btn--accent">
            Novo produto
        </a>
    </div>`;
    }

    function estadoErro(mensagem) {
      corpoTabela.innerHTML = `
    <div class="state">

        <p class="state__title">
            Não foi possível carregar
        </p>

        <p class="state__text">
            ${escaparHtml(mensagem)}
        </p>

        <button class="btn btn--ghost" onclick="carregarProdutos()">
            Tentar novamente
        </button>

    </div>`;
    }

    function formatarId(id) {
      return "#" + id.toString().padStart(3, "0");
    }

    function formatarMoeda(valor) {

      return Number(valor).toLocaleString("pt-BR", {
        style: "currency",
        currency: "BRL"
      });

    }

    function renderizarLista(produtos) {

      if (!produtos.length) {
        estadoVazio();
        return;
      }

      corpoTabela.innerHTML = produtos.map(prod => {

        const nomeCategoria =
          mapaCategorias.get(prod.idCategoria) ?? "-";

        return `

        <div class="row" data-id="${prod.idProduto}">

            <span class="row__id" style="width:90px">
                ${formatarId(prod.idProduto)}
            </span>

            <span style="flex:2">
                ${escaparHtml(prod.nomeProduto)}
            </span>

            <span style="width:140px">
                ${formatarMoeda(prod.valorProduto)}
            </span>

            <span style="width:180px">
                ${escaparHtml(nomeCategoria)}
            </span>

            <span class="row__actions">

                <a
                    class="btn btn--ghost btn--icon"
                    href="alterar.php?id=${prod.idProduto}"
                    title="Alterar">

                    ${iconeEditar}

                </a>

                <button
                    class="btn btn--danger-ghost btn--icon"
                    title="Excluir"
                    onclick="pedirExclusao(${prod.idProduto}, '${escaparHtml(prod.nomeProduto).replace(/'/g,"\\'")}')">

                    ${iconeExcluir}

                </button>

            </span>

        </div>

        `;

      }).join("");

    }

    async function carregarCategorias() {

      const categorias = await CategoriaAPI.listar();

      mapaCategorias.clear();

      categorias.forEach(cat => {

        mapaCategorias.set(
          cat.idCategoria,
          cat.nomeCategoria
        );

      });

    }

    async function carregarProdutos() {

      corpoTabela.innerHTML = `
    <div class="state">
        <div class="spinner"></div>
        <p class="state__text">
            Carregando produtos...
        </p>
    </div>`;

      try {

        await carregarCategorias();

        const produtos = await ProdutoApi.listar();

        renderizarLista(produtos);

      } catch (erro) {

        estadoErro(erro.message);

      }

    }

    function pedirExclusao(id, nome) {

      idParaExcluir = id;

      modalTexto.textContent =
        `Deseja realmente excluir o produto "${nome}"?`;

      modalExcluir.style.display = "flex";

    }

    function fecharModal() {

      modalExcluir.style.display = "none";

      idParaExcluir = null;

    }

    btnCancelarExclusao.addEventListener("click", fecharModal);

    modalExcluir.addEventListener("click", e => {

      if (e.target === modalExcluir) {

        fecharModal();

      }

    });

    btnConfirmarExclusao.addEventListener("click", async () => {

      if (idParaExcluir == null) return;

      btnConfirmarExclusao.disabled = true;
      btnConfirmarExclusao.textContent = "Excluindo...";

      try {

        await ProdutoApi.excluir(idParaExcluir);

        mostrarToast(
          "Produto excluído com sucesso.",
          "success"
        );

        fecharModal();

        carregarProdutos();

      } catch (erro) {

        mostrarToast(
          erro.message,
          "error"
        );

      } finally {

        btnConfirmarExclusao.disabled = false;
        btnConfirmarExclusao.textContent = "Excluir";

      }

    });

    (function() {

      const msg = sessionStorage.getItem("produtoMsg");

      if (msg) {

        mostrarToast(msg, "success");

        sessionStorage.removeItem("produtoMsg");

      }

    })();

    carregarProdutos();
  </script>

</body>

</html>