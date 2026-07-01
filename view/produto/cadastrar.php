<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo produto</title>

    <link rel="stylesheet" href="../assets/css/categoria.css">
</head>

<body>

    <div class="wrap wrap--narrow">

        <a href="listar.php" class="back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>

            Voltar para produtos
        </a>


        <span class="eyebrow"><span class="eyebrow__dot"></span>Catálogo · Novo registro</span>


        <h1 class="page-title">Cadastrar produto</h1>

        <p class="page-sub">
            Preencha os campos abaixo para cadastrar um novo produto.
        </p>


        <form class="form-card" id="formCadastro" novalidate>


            <div class="field">

                <label for="nomeProduto">
                    Nome do produto
                </label>

                <input
                    type="text"
                    id="nomeProduto"
                    name="nomeProduto"
                    placeholder="Cabecinha de guidão"
                    autocomplete="off"
                    maxlength="120"
                    autofocus>

                <p class="field-hint" id="hintNome">
                    Obrigatório.
                </p>

            </div>



            <div class="field">

                <label for="valorProduto">
                    Valor do produto
                </label>


                <input
                    type="text"
                    id="valorProduto"
                    name="valorProduto"
                    placeholder="R$ 0,00"
                    autocomplete="off" maxlength="">


                <p class="field-hint" id="hintValor">
                    Obrigatório.
                </p>

            </div>

            <div class="field">

                <label for="categoria">
                    Categoria
                </label>


                <select
                    class="form-control"
                    id="categoria"
                    name="categoria">

                    <option value="">
                        Carregando categorias...
                    </option>

                </select>


                <p class="field-hint" id="hintCategoria">
                    Obrigatório.
                </p>

            </div>




            <div class="form-actions">

                <a href="listar.php" class="btn btn--ghost">
                    Cancelar
                </a>


                <button
                    type="submit"
                    class="btn btn--accent"
                    id="btnSalvar">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2.4"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5" />
                    </svg>

                    Salvar produto

                </button>

            </div>


        </form>


    </div>



    <script src="../assets/js/api/httpClient.js"></script>
    <script src="../assets/js/api/produtoApi.js"></script>
    <script src="../assets/js/api/categoriaApi.js"></script>
    <script src="../assets/js/utils/helpers.js"></script>
    <script src="../assets/js/utils/toast.js"></script>



    <script>
        const form = document.getElementById('formCadastro');
        const nome = document.getElementById('nomeProduto');
        const valor = document.getElementById('valorProduto');
        const categoria = document.getElementById('categoria');

        const hintNome = document.getElementById('hintNome');
        const hintValor = document.getElementById('hintValor');
        const hintCategoria = document.getElementById('hintCategoria');

        const btnSalvar = document.getElementById('btnSalvar');
        const btnSalvarHTML = btnSalvar.innerHTML;

        valor.addEventListener("input", formatarMoeda);

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


        function validarCampo(campo, hint, mensagem) {

            if (campo.value.trim() === '') {

                campo.classList.add('field--invalid');

                hint.textContent = mensagem;
                hint.classList.add('field-hint--error');

                return false;
            }

            campo.classList.remove('field--invalid');

            hint.textContent = 'Obrigatório.';
            hint.classList.remove('field-hint--error');

            return true;
        }


        function validar() {

            const nomeValido = validarCampo(
                nome,
                hintNome,
                "Informe o nome do produto."
            );

            const valorValido = validarCampo(
                valor,
                hintValor,
                "Informe o valor do produto."
            );

            const categoriaValida = validarCampo(
                categoria,
                hintCategoria,
                "Selecione uma categoria."
            );

            return nomeValido && valorValido && categoriaValida;
        }


        // revalida em tempo real assim que o campo já estava marcado como inválido
        nome.addEventListener('input', () => {
            if (nome.classList.contains('field--invalid')) {
                validarCampo(nome, hintNome, "Informe o nome do produto.");
            }
        });

        valor.addEventListener('input', () => {
            if (valor.classList.contains('field--invalid')) {
                validarCampo(valor, hintValor, "Informe o valor do produto.");
            }
        });

        categoria.addEventListener('change', () => {
            if (categoria.classList.contains('field--invalid')) {
                validarCampo(categoria, hintCategoria, "Selecione uma categoria.");
            }
        });


        async function carregarCategorias() {

            try {

                const dados = await CategoriaAPI.listar();

                categoria.innerHTML = `
                    <option value="">
                        Selecione uma categoria
                    </option>
                `;

                dados.forEach(cat => {

                    const option = document.createElement("option");

                    option.value = cat.idCategoria;
                    option.textContent = cat.nomeCategoria;

                    categoria.appendChild(option);
                });

            } catch (error) {

                console.error(error);

                categoria.innerHTML = `
                    <option value="">
                        Erro ao carregar categorias
                    </option>
                `;
            }
        }


        carregarCategorias();


        form.addEventListener("submit", async (event) => {

            event.preventDefault();

            if (!validar()) {
                return;
            }

            // limpa a máscara "R$ 0,00" -> número puro antes de enviar
            const valorLimpo = valor.value
                .replace(/[^\d,.-]/g, '') // tira "R$" e espaços
                .replace(/\./g, '') // tira separador de milhar
                .replace(',', '.'); // vírgula decimal -> ponto

            btnSalvar.disabled = true;
            btnSalvar.textContent = 'Salvando…';

            try {

                await ProdutoApi.gravar(
                    nome.value.trim(),
                    valorLimpo,
                    categoria.value
                );

                sessionStorage.setItem('produtoMsg', 'Produto cadastrado com sucesso.');
                window.location.href = 'listar.php';

            } catch (erro) {

                mostrarToast(erro.message, 'error');

                btnSalvar.disabled = false;
                btnSalvar.innerHTML = btnSalvarHTML;
            }
        });
    </script>


</body>

</html>