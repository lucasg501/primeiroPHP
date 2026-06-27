<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * docs/swagger.php
 *
 * Chamado via GET /swagger/openapi
 * Escaneia TODOS os arquivos PHP do projeto que contêm anotações OpenAPI
 * e devolve o JSON gerado em tempo real.
 *
 * Não precisa rodar nenhum script manual — basta adicionar novas rotas
 * que elas aparecem automaticamente no Swagger.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;

// Pastas que serão escaneadas em busca de atributos #[OA\...]
$scanPaths = [
    __DIR__,               // docs/  (openApi.php, categoriaSwagger.php, produtoSwagger.php)
    __DIR__ . '/../route', // route/ (futuramente pode ter anotações aqui também)
];

try {
    $openapi = Generator::scan($scanPaths);

    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');  // sempre atualizado

    echo $openapi->toJson();

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        "error"   => "Erro ao gerar documentação OpenAPI",
        "message" => $e->getMessage(),
    ]);
}
