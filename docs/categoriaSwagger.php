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
    path: "/categoria/listar",
    tags: ["Categoria"],
    summary: "Listar todas as categorias",
    responses: [
        new OA\Response(response: 200, description: "Lista de categorias retornada com sucesso")
    ]
)]

#[OA\Get(
    path: "/categoria/obter/{idCategoria}",
    tags: ["Categoria"],
    summary: "Obter categoria pelo ID",
    parameters: [
        new OA\Parameter(
            name: "idCategoria",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Categoria encontrada"),
        new OA\Response(response: 404, description: "Categoria não encontrada")
    ]
)]

#[OA\Post(
    path: "/categoria/gravar",
    tags: ["Categoria"],
    summary: "Criar nova categoria",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["nomeCategoria"],
            properties: [
                new OA\Property(property: "nomeCategoria", type: "string", example: "Eletrônicos")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 201, description: "Categoria criada com sucesso"),
        new OA\Response(response: 400, description: "Erro de validação")
    ]
)]

#[OA\Put(
    path: "/categoria/alterar",
    tags: ["Categoria"],
    summary: "Alterar categoria existente",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["idCategoria", "nomeCategoria"],
            properties: [
                new OA\Property(property: "idCategoria", type: "integer", example: 1),
                new OA\Property(property: "nomeCategoria", type: "string", example: "Eletrodomésticos")
            ]
        )
    ),
    responses: [
        new OA\Response(response: 200, description: "Categoria alterada com sucesso"),
        new OA\Response(response: 400, description: "Erro de validação")
    ]
)]

#[OA\Delete(
    path: "/categoria/excluir/{idCategoria}",
    tags: ["Categoria"],
    summary: "Excluir categoria pelo ID",
    parameters: [
        new OA\Parameter(
            name: "idCategoria",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(response: 200, description: "Categoria excluída com sucesso"),
        new OA\Response(response: 404, description: "Categoria não encontrada")
    ]
)]

class CategoriaSwagger
{
}
