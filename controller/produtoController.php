<?php


require_once __DIR__ . '/../model/produtoModel.php';

class ProdutoController
{

    // GET /produto/listar
    public function listar(): void
    {
        try {
            $produto = new Produto();
            $lista   = $produto->listar();

            $this->resposta(200, $lista);

        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha"   => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }


    // GET /produto/obter/{idProduto}
    public function obter(): void
    {
        try {
            $idProduto = (int) ($_GET['idProduto'] ?? 0);

            if ($idProduto <= 0) {
                $this->resposta(400, ["message" => "ID do produto inválido"]);
                return;
            }

            $produto   = new Produto();
            $resultado = $produto->obter($idProduto);

            if ($resultado) {
                $this->resposta(200, $resultado);
            } else {
                $this->resposta(404, ["message" => "Produto não encontrado"]);
            }

        } catch (Exception $e) {
            $this->resposta(500, ["message" => "Erro interno do servidor"]);
        }
    }


    // POST /produto/criar
    public function gravar(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (
                empty($data['nomeProduto']) ||
                !isset($data['valorProduto']) ||
                empty($data['idCategoria'])
            ) {
                $this->resposta(400, ["message" => "Campos obrigatórios: nomeProduto, valorProduto, idCategoria"]);
                return;
            }

            $produto = new Produto(
                0,
                $data['nomeProduto'],
                (float) $data['valorProduto'],
                (int)   $data['idCategoria']
            );

            if ($produto->gravar()) {
                $this->resposta(201, ["message" => "Produto criado com sucesso"]);
            } else {
                $this->resposta(400, ["message" => "Erro ao criar produto"]);
            }

        } catch (Exception $e) {
            $this->resposta(500, ["message" => "Erro interno do servidor"]);
        }
    }


    // PUT /produto/alterar
    public function alterar(): void
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (
                empty($data['idProduto']) ||
                empty($data['nomeProduto']) ||
                !isset($data['valorProduto']) ||
                empty($data['idCategoria'])
            ) {
                $this->resposta(400, ["message" => "Campos obrigatórios: idProduto, nomeProduto, valorProduto, idCategoria"]);
                return;
            }

            $produto = new Produto(
                (int)   $data['idProduto'],
                        $data['nomeProduto'],
                (float) $data['valorProduto'],
                (int)   $data['idCategoria']
            );

            if ($produto->gravar()) {
                $this->resposta(200, ["message" => "Produto alterado com sucesso"]);
            } else {
                $this->resposta(400, ["message" => "Erro ao alterar produto"]);
            }

        } catch (Exception $e) {
            $this->resposta(500, ["message" => "Erro interno do servidor"]);
        }
    }


    // DELETE /produto/excluir/{idProduto}
    public function excluir(): void
    {
        try {
            // ID vem da rota (capturado em produtoRoute.php via regex)
            $idProduto = (int) ($_GET['idProduto'] ?? 0);

            if ($idProduto <= 0) {
                $this->resposta(400, ["message" => "ID do produto inválido"]);
                return;
            }

            $produto = new Produto();
            $produto->setIdProduto($idProduto);

            if ($produto->excluir()) {
                $this->resposta(200, ["message" => "Produto excluído com sucesso"]);
            } else {
                $this->resposta(404, ["message" => "Produto não encontrado ou já excluído"]);
            }

        } catch (Exception $e) {
            $this->resposta(500, ["message" => "Erro interno do servidor"]);
        }
    }


    private function resposta(int $codigo, mixed $dados): void
    {
        http_response_code($codigo);
        header('Content-Type: application/json');
        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    }
}
