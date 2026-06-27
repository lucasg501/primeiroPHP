<?php

require_once __DIR__ . '/../controller/categoriaController.php';


$controller = new CategoriaController();


$metodo = $_SERVER['REQUEST_METHOD'];

$caminho = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);


$caminho = rtrim($caminho, '/');


switch(true){


    // GET /categoria/listar
    case $metodo == 'GET' 
    && $caminho == '/categoria/listar':

        $controller->listar();

        break;



    // GET /categoria/obter/{idCategoria}
    case $metodo == 'GET' 
    && preg_match(
        '#^/categoria/obter/(\d+)$#',
        $caminho,
        $m
    ):

        $_GET['idCategoria'] = $m[1];

        $controller->obter();

        break;



    // POST /categoria/gravar
    case $metodo == 'POST'
    && $caminho == '/categoria/gravar':

        $controller->gravar();

        break;



    // PUT /categoria/alterar
    case $metodo == 'PUT'
    && $caminho == '/categoria/alterar':

        $controller->alterar();

        break;



    // DELETE /categoria/excluir/{idCategoria}
    case $metodo == 'DELETE'
    && preg_match(
        '#^/categoria/excluir/(\d+)$#',
        $caminho,
        $m
    ):


        $_GET['idCategoria'] = $m[1];

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