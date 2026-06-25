<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gerenciamento de Estoque</title>

    <link rel="stylesheet" href="public/css/template/css/styles.css">
    <link rel="stylesheet" href="public/css/template/css/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="public/css/template/css/sb-admin-2.min.css">
</head>


<body>

<div id="wrapper">


    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">


        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/admin">

            <div class="sidebar-brand-icon rotate-n-15">

                <i class="fas fa-solid fa-trowel-bricks"></i>

            </div>


            <div class="sidebar-brand-text mx-3">

                <sup>
                    Gerenciamento de Estoque
                </sup>

            </div>


        </a>


        <hr class="sidebar-divider my-0">


        <li class="nav-item">

            <a class="nav-link" href="/">

                <i class="fas fa-home"></i>

                <span>
                    Início
                </span>

            </a>

        </li>



        <hr class="sidebar-divider">


        <div class="sidebar-heading">
            Menu
        </div>



        <li class="nav-item">

            <a class="nav-link" href="/view/produtos">

                <i class="fas fa-shopping-cart"></i>

                <span>
                    Produtos
                </span>

            </a>

        </li>



        <li class="nav-item">

            <a class="nav-link" href="/view/categorias">

                <i class="fas fa-list"></i>

                <span>
                    Categorias
                </span>

            </a>

        </li>

    </ul>




    <!-- Conteúdo -->
    <div id="content-wrapper" class="d-flex flex-column">


        <div id="content">



            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                <span class="navbar-brand mb-0 h1">

                    Sistema de Gerenciamento de Estoque

                </span>


            </nav>

            <div>
            <h1>Olha o teste chegando</h1>
        </div>



            <div class="container-fluid">


                <?php

                // Aqui entra o conteúdo da página
                // equivalente ao {children} do Next

                echo $content ?? '';

                ?>


            </div>


        </div>


    </div>



</div>



<script src="/template/js/jquery.min.js"></script>
<script src="/template/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    try {
        const response = await fetch('cadastro_api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                nome: 'Produto Teste',
                valor: 100
            })
        });

        const resultado = await response.text();

        console.log('Status:', response.status);
        console.log('Resposta:', resultado);

    } catch (erro) {
        console.error('Erro na requisição:', erro);
    }
});
</script>

</body>

</html>