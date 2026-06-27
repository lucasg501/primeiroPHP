<?php

/**
 * docs/produtoSwagger.php
 *
 * Anotações OpenAPI para as rotas de Produto.
 * Escaneado automaticamente pelo swagger.php.
 */

namespace Docs;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/produto/listar",
    tags: ["Produto"],
    summary: "Listar todos os produtos",
    responses: [
        new OA\Response(response: 200, description: "Lista de produtos retornada com sucesso")
    ]
)]

#[OA\Get(
    path: "/produto/obter/{idProduto}",
    tags: ["Produto"],
    summary: "Obter produto pelo ID",
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
    summary: "Criar novo produto",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nomeProduto", "valorProduto", "idCategoria"],
            properties: [
                new OA\Property(property: "nomeProduto",  type: "string",  example: "Notebook"),
                new OA\Property(property: "valorProduto", type: "number",  example: 3499.90),
                new OA\Property(property: "idCategoria",  type: "integer", example: 1)
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Produto criado com sucesso"),
        new OA\Response(response: 400, description: "Erro de validação")
    ]
)]

#[OA\Put(
    path: "/produto/alterar",
    tags: ["Produto"],
    summary: "Alterar produto existente",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["idProduto", "nomeProduto", "valorProduto", "idCategoria"],
            properties: [
                new OA\Property(property: "idProduto",    type: "integer", example: 1),
                new OA\Property(property: "nomeProduto",  type: "string",  example: "Notebook Pro"),
                new OA\Property(property: "valorProduto", type: "number",  example: 4299.90),
                new OA\Property(property: "idCategoria",  type: "integer", example: 1)
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Produto alterado com sucesso"),
        new OA\Response(response: 400, description: "Erro de validação")
    ]
)]

#[OA\Delete(
    path: "/produto/excluir/{idProduto}",
    tags: ["Produto"],
    summary: "Excluir produto pelo ID",
    parameters: [
        new OA\Parameter(
            name: "idProduto",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Produto excluído com sucesso"),
        new OA\Response(response: 404, description: "Produto não encontrado")
    ]
)]

class ProdutoSwagger
{
}
