<?php

// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Responde preflight do navegador
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Autoload do Composer (necessário para Swagger e outras dependências)
require_once __DIR__ . '/vendor/autoload.php';

// Swagger: redireciona para a documentação
if (isset($_GET['docs'])) {
    header("Location: docs/index.html");
    exit;
}

// Captura a URI sem query string e normaliza
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Descobre o segmento inicial da rota (ex: /produto/listar -> "produto")
$segmentos = explode('/', ltrim($uri, '/'));
$recurso = $segmentos[0] ?? '';

switch ($recurso) {

    case 'produto':
        require_once __DIR__ . '/route/produtoRoute.php';
        break;

    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode([
            "message" => "Rota não encontrada"
        ]);
        break;
}
