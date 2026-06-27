<?php

require_once __DIR__ . '/../controller/produtoController.php';


$controller = new ProdutoController();

$metodo  = $_SERVER['REQUEST_METHOD'];
$caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$caminho = rtrim($caminho, '/');


switch (true) {


    // GET /produto/listar
    case $metodo === 'GET' && $caminho === '/produto/listar':

        $controller->listar();

        break;



    // GET /produto/obter/{idProduto}
    case $metodo === 'GET' && preg_match('#^/produto/obter/(\d+)$#', $caminho, $m):

        $_GET['idProduto'] = $m[1];

        $controller->obter();

        break;



    // POST /produto/gravar
    case $metodo === 'POST' && $caminho === '/produto/gravar':

        $controller->gravar();

        break;



    // PUT /produto/alterar
    case $metodo === 'PUT' && $caminho === '/produto/alterar':

        $controller->alterar();

        break;



    // DELETE /produto/excluir/{idProduto}
    case $metodo === 'DELETE' && preg_match('#^/produto/excluir/(\d+)$#', $caminho, $m):

        $_GET['idProduto'] = $m[1];

        $controller->excluir();

        break;



    default:

        http_response_code(404);

        header('Content-Type: application/json');

        echo json_encode([
            "message" => "Rota não encontrada"
        ]);

        break;
}