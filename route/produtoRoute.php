<?php

use OpenApi\Attributes as OA;

require_once __DIR__ . '/../controller/produtoController.php';


/**
 * Documentação Swagger das rotas de Produto
 */

#[OA\Get(
    path: "/produto/listar",
    tags: ["Produto"],
    summary: "Lista todos os produtos",
    responses: [
        new OA\Response(response: 200, description: "Produtos encontrados")
    ]
)]

#[OA\Get(
    path: "/produto/obter/{idProduto}",
    tags: ["Produto"],
    summary: "Obtém um produto pelo ID",
    parameters: [
        new OA\Parameter(
            name: "idProduto",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Produto encontrado"),
        new OA\Response(response: 404, description: "Produto não encontrado")
    ]
)]

#[OA\Post(
    path: "/produto/gravar",
    tags: ["Produto"],
    summary: "Cria um produto",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nomeProduto", "valorProduto", "idCategoria"],
            properties: [
                new OA\Property(property: "nomeProduto",  type: "string",  example: "Café"),
                new OA\Property(property: "valorProduto", type: "number",  example: 10.50),
                new OA\Property(property: "idCategoria",  type: "integer", example: 1)
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Produto criado")
    ]
)]

#[OA\Put(
    path: "/produto/alterar",
    tags: ["Produto"],
    summary: "Altera um produto",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["idProduto", "nomeProduto", "valorProduto", "idCategoria"],
            properties: [
                new OA\Property(property: "idProduto",    type: "integer", example: 1),
                new OA\Property(property: "nomeProduto",  type: "string",  example: "Café Especial"),
                new OA\Property(property: "valorProduto", type: "number",  example: 15.00),
                new OA\Property(property: "idCategoria",  type: "integer", example: 1)
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Produto alterado")
    ]
)]

#[OA\Delete(
    path: "/produto/excluir/{idProduto}",
    tags: ["Produto"],
    summary: "Exclui um produto",
    parameters: [
        new OA\Parameter(
            name: "idProduto",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Produto excluído"),
        new OA\Response(response: 404, description: "Produto não encontrado")
    ]
)]
class ProdutoRoutesSwagger {}


// -------------------------------------------------------
// Dispatcher de rotas
// -------------------------------------------------------

$controller = new ProdutoController();
$metodo     = $_SERVER['REQUEST_METHOD'];
$caminho    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$caminho    = rtrim($caminho, '/');

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
        echo json_encode(["message" => "Rota não encontrada"], JSON_UNESCAPED_UNICODE);
        break;
}
