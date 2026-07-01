<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar produto · Catálogo</title>
    <link rel="stylesheet" href="../assets/css/categoria.css">
</head>

<body>

    <div class="wrap wrap--narrow">
        <a href="listar.php" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Voltar para produtos
        </a>

        <span class="eyebrow" id="eyebrowId"><span class="eyebrow__dot"></span>Catálogo · Carregando…</span>

        <h1 class="page-title">Alterar Produto</h1>
        <p class="page-sub">Atualize os dados do produto abaixo.</p>

        <div id="areaConteudo">
            <div class="form-card">
                <div class="field">
                    <label>Nome do Produto</label>
                    <div class="field-skeleton"></div>
                </div>
            </div>
        </div>

    </div>

    <script src="../assets/js/api/httpClient.js"></script>
    <script src="../assets/js/api/categoriaApi.js"></script>
    <script src="../assets/js/api/produtoApi.js"></script>
    <script src="../assets/js/utils/helpers.js"></script>
    <script src="../assets/js/utils/toast.js"></script>

    <script>
        const params = new URLSearchParams(window.location.search);
        const idProduto = parseInt(params.get('id'), 10);
        const eyebrowId = document.getElementById('eyebrowId');
        const areaConteudo = document.getElementById('areaConteudo');

        function formatarId(id) {
            return '#' + String(id).padStart(3, '0');
        }

        function formatarMoeda(event) {

            let numero = event.target.value.replace(/\D/g, "");

            if (numero === "") {
                event.target.value = "";
                return;
            }

            numero = (parseInt(numero, 10) / 100).toFixed(2);

            event.target.value = Number(numero).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL"
            });
        }

        function paraCentavos(valorFormatado) {
            const numero = valorFormatado.replace(/\D/g, "");
            if (numero === "") return NaN;
            return parseInt(numero, 10) / 100;
        }

        function renderizarErro(mensagem) {
            areaConteudo.innerHTML = `
      <div class="card">
        <div class="state">
          <svg class="state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
          <p class="state__title">Não foi possível carregar</p>
          <p class="state__text">${escaparHtml(mensagem)}</p>
          <a href="listar.php" class="btn btn--ghost">Voltar para produtos</a>
        </div>
      </div>`;
        }

        function renderizarForm(produto, categorias) {
            eyebrowId.innerHTML = `<span class="eyebrow__dot"></span>Catálogo · ${formatarId(produto.idProduto)}`;

            const opcoesCategoria = categorias.map(cat =>
                `<option value="${cat.idCategoria}">${escaparHtml(cat.nomeCategoria)}</option>`
            ).join('');

            areaConteudo.innerHTML = `
      <form class="form-card" id="formAlterar" novalidate>
        <div class="field">
          <label for="nomeProduto">Nome do produto</label>
          <input type="text" id="nomeProduto" name="nomeProduto" maxlength="120" autocomplete="off" autofocus>
          <p class="field-hint" id="hintNome">Obrigatório.</p>
        </div>

        <div class="field">
          <label for="valorProduto">Valor</label>
          <input type="text" id="valorProduto" name="valorProduto" placeholder="R$ 0,00" autocomplete="off">
          <p class="field-hint" id="hintValor">Obrigatório, maior que zero.</p>
        </div>

        <div class="field">
          <label for="idCategoria">Categoria</label>
          <select id="idCategoria" name="idCategoria" class="form-control">
            ${opcoesCategoria}
          </select>
          <p class="field-hint">Obrigatório.</p>
        </div>

        <div class="form-actions">
          <a href="listar.php" class="btn btn--ghost">Cancelar</a>
          <button type="submit" class="btn btn--accent" id="btnSalvar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Salvar alterações
          </button>
        </div>
      </form>`;

            const inputNome = document.getElementById('nomeProduto');
            const hintNome = document.getElementById('hintNome');
            const inputValor = document.getElementById('valorProduto');
            const hintValor = document.getElementById('hintValor');
            const selectCategoria = document.getElementById('idCategoria');
            const btnSalvar = document.getElementById('btnSalvar');
            const form = document.getElementById('formAlterar');

            inputNome.value = produto.nomeProduto;
            inputValor.value = Number(produto.valorProduto).toLocaleString("pt-BR", {
                style: "currency",
                currency: "BRL"
            });
            selectCategoria.value = produto.idCategoria;

            inputValor.addEventListener("input", formatarMoeda);

            function validarNome() {
                const valor = inputNome.value.trim();
                if (!valor) {
                    inputNome.classList.add('field--invalid');
                    hintNome.textContent = 'Informe o nome do produto.';
                    hintNome.classList.add('field-hint--error');
                    return false;
                }
                inputNome.classList.remove('field--invalid');
                hintNome.textContent = 'Obrigatório.';
                hintNome.classList.remove('field-hint--error');
                return true;
            }

            function validarValor() {
                const valor = paraCentavos(inputValor.value);
                if (isNaN(valor) || valor <= 0) {
                    inputValor.classList.add('field--invalid');
                    hintValor.textContent = 'Informe um valor válido, maior que zero.';
                    hintValor.classList.add('field-hint--error');
                    return false;
                }
                inputValor.classList.remove('field--invalid');
                hintValor.textContent = 'Obrigatório, maior que zero.';
                hintValor.classList.remove('field-hint--error');
                return true;
            }

            inputNome.addEventListener('input', () => {
                if (inputNome.classList.contains('field--invalid')) validarNome();
            });

            inputValor.addEventListener('input', () => {
                if (inputValor.classList.contains('field--invalid')) validarValor();
            });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const nomeValido = validarNome();
                const valorValido = validarValor();

                if (!nomeValido || !valorValido) {
                    if (!nomeValido) inputNome.focus();
                    else inputValor.focus();
                    return;
                }

                btnSalvar.disabled = true;
                btnSalvar.textContent = 'Salvando…';

                try {
                    await ProdutoApi.alterar(
                        produto.idProduto,
                        inputNome.value.trim(),
                        paraCentavos(inputValor.value),
                        parseInt(selectCategoria.value, 10)
                    );
                    sessionStorage.setItem('produtoMsg', 'Produto alterado com sucesso.');
                    window.location.href = 'listar.php';
                } catch (erro) {
                    mostrarToast(erro.message, 'error');
                    btnSalvar.disabled = false;
                    btnSalvar.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg> Salvar alterações';
                }
            });
        }

        async function carregarProduto() {
            if (!idProduto || idProduto <= 0) {
                renderizarErro('ID de produto inválido.');
                return;
            }

            try {
                const [produto, categorias] = await Promise.all([
                    ProdutoApi.obter(idProduto),
                    CategoriaAPI.listar()
                ]);
                renderizarForm(produto, categorias);
            } catch (erro) {
                renderizarErro(erro.message);
            }
        }

        carregarProduto();
    </script>

</body>

</html>