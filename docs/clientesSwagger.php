<?php

/**
 * docs/categoriaSwagger.php
 *
 * Anotações OpenAPI para as rotas de Categoria.
 * Escaneado automaticamente pelo swagger.php.
 */

namespace Docs;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/clientes/listar",
    tags: ["Clientes"],
    summary: "Lista todos os clientes",
    responses: [
        new OA\Response(response: 200, description: "Lista todos os clientes cadastrados."),
        new OA\Response(response: 404, description: "Nenhum cliente cadastrado.")
    ]
)]

#[OA\Get(
    path:"/clientes/obter/{idCliente}",
    tags: ["Clientes"],
    summary: "Obtem um cliente pelo id",
    parameters: [
        new OA\Parameter(
            name: "idCliente",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Retorna um cliente pelo id."),
        new OA\Response(response: 404, description: "Nenhum cliente encontrado.")
    ]
)]

#[OA\Post(
    path: "/clientes/gravar",
    tags: ["Clientes"],
    summary: "Cria um novo cliente",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nomeCliente"],
            properties: [
                new OA\Property(property: "nomeCliente", type: "string", example: "João da Silva")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Cria um novo cliente."),
        new OA\Response(response: 404, description: "Nenhum cliente cadastrado.")
    ]
)]

#[OA\Put(
    path: "/clientes/alterar",
    tags: ["Clientes"],
    summary: "Altera um cliente",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["idCliente", "nomeCliente"],
            properties: [
                new OA\Property(property: "idCliente", type: "integer", example: 1),
                new OA\Property(property: "nomeCliente", type: "string", example: "João da Silva")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Altera um cliente."),
        new OA\Response(response: 404, description: "Nenhum cliente cadastrado.")
    ]
)]

#[OA\Delete(
    path: "/clientes/excluir/{idCliente}",
    tags: ["Clientes"],
    summary: "Exclui um cliente",
    parameters: [
        new OA\Parameter(
            name: "idCliente",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Exclui um cliente."),
        new OA\Response(response: 404, description: "Nenhum cliente cadastrado.")
    ]
)]

class ClientesSwagger 
{
}