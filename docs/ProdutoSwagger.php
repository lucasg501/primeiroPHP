<?php

use OpenApi\Attributes as OA;


#[OA\Info(
    title: "API Primeiro PHP",
    version: "1.0.0",
    description: "Documentação da API"
)]

#[OA\Server(url: "http://localhost", description: "Servidor local")]

class ProdutoSwagger
{

}
