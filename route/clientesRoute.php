<?php

require_once __DIR__ . '/../controller/clientesController.php';

$controller = new ClienteController();

$metodo = $_SERVER['REQUEST_METHOD'];

$caminho = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$caminho = rtrim($caminho, '/');

switch (true) {

    case $metodo == 'GET' && $caminho == '/clientes/listar':
        $controller->listar();
        break;

    case $metodo == 'GET' && preg_match('#^/clientes/obter/(\d+)$#', $caminho, $m):
        $_GET['idCliente'] = $m[1];
        $controller->obter();
        break;

    case $metodo == 'POST' && $caminho == '/clientes/gravar':
        $controller->gravar();
        break;

    case $metodo == 'PUT' && $caminho == '/clientes/alterar':
        $controller->alterar();
        break;

    case $metodo == 'DELETE' && preg_match('#^/clientes/excluir/(\d+)$#', $caminho, $m):
        $_GET['idCliente'] = $m[1];
        $controller->excluir();
        break;

    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Rota nao encontrada']);
        break;
}
