<?php

// ─── ROUTER SCRIPT (servidor embutido do PHP) ──────────────────────────────
// Quando rodado como `php -S localhost:4000 index.php`, este bloco deixa o
// próprio servidor servir arquivos que já existem em disco (ex.: tudo que
// está em /view e /public), e só passa para o roteamento de API abaixo
// quando a URL não corresponde a nenhum arquivo real.
if (php_sapi_name() === 'cli-server') {
    $caminhoSolicitado = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $caminhoArquivo    = __DIR__ . $caminhoSolicitado;

    if ($caminhoSolicitado !== '/' && is_file($caminhoArquivo)) {
        return false; // deixa o PHP servir o arquivo (html/css/js/php) diretamente
    }
}

// ─── CORS ─────────────────────────────────────────────────────────────────
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ─── AUTOLOAD ─────────────────────────────────────────────────────────────
require_once __DIR__ . '/vendor/autoload.php';

// ─── ROTEAMENTO ───────────────────────────────────────────────────────────
$uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri      = rtrim($uri, '/');
$segmentos = explode('/', ltrim($uri, '/'));
$recurso  = $segmentos[0] ?? '';

switch ($recurso) {

    // ── Documentação Swagger ───────────────────────────────────────────────
    //
    // GET /?docs              → abre o Swagger UI
    // GET /swagger/openapi    → retorna o JSON gerado dinamicamente
    // GET /swagger/ui         → retorna o HTML do Swagger UI
    //
    case '':
    case 'swagger':
        if (isset($_GET['docs']) || $uri === '') {
            // Redireciona para o UI
            header("Location: /swagger/ui");
            exit;
        }

        $acao = $segmentos[1] ?? '';

        if ($acao === 'openapi') {
            // Gera e devolve o JSON do OpenAPI em tempo real
            require_once __DIR__ . '/docs/swagger.php';
            exit;
        }

        if ($acao === 'ui') {
            // Devolve o HTML do Swagger UI embutindo o spec dinâmico
            require_once __DIR__ . '/docs/index.php';
            exit;
        }

        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Rota Swagger não encontrada"]);
        break;

    // ── API: Produto ───────────────────────────────────────────────────────
    case 'produto':
        require_once __DIR__ . '/route/produtoRoute.php';
        break;

    // ── API: Categoria ─────────────────────────────────────────────────────
    case 'categoria':
        require_once __DIR__ . '/route/categoriaRoute.php';
        break;

    case 'clientes':
        require_once __DIR__ . '/route/clientesRoute.php';
        break;    

    // ── Fallback ───────────────────────────────────────────────────────────
    default:
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Rota não encontrada"]);
        break;
}