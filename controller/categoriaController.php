<?php

require_once __DIR__ . '/../model/categoriaModel.php';

class CategoriaController
{
    public function listar()
    {
        try {
            $categoriaModel = new CategoriaModel($idCategoria = 0, $nomeCategoria = '');
            $lista = $categoriaModel->listar();
            $this->res(200, $lista);
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha"   => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }

    public function obter(): void
    {
        try {

            $idCategoria = (int) ($_GET["idCategoria"] ?? 0);

            if ($idCategoria <= 0) {
                $this->res(400, ["message" => "ID de categoria inválida."]);
                return;
            }

            $categoriaModel = new CategoriaModel($idCategoria, '');
            $categoria = $categoriaModel->obter($idCategoria);

            if ($categoria) {
                $this->res(200, $categoria);
            } else {
                $this->res(404, ["message" => "Categoria nao encontrada"]);
            }
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha" => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }

    public function gravar(): void
    {
        try {

            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data["nomeCategoria"])) {
                $this->res(400, ["message" => "Nome da categoria é obrigatório."]);
                return;
            }

            $categoriaModel = new CategoriaModel($idCategoria = 0, $nomeCategoria = '');

            $categoriaModel->setIdCategoria(0);
            $categoriaModel->setNomeCategoria($data["nomeCategoria"]);

            $ok = $categoriaModel->gravar();

            if ($ok) {
                $this->res(201, ["message" => "Categoria gravada com sucesso."]);
            } else {
                $this->res(400, ["message" => "Erro ao gravar categoria."]);
            }
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha"   => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }

    public function alterar(): void
    {
        try {

            $data = json_decode(file_get_contents('php://input'), true);

            if (
                empty($data["idCategoria"]) ||
                empty($data["nomeCategoria"])
            ) {
                $this->res(400, [
                    "message" => "ID e Nome da categoria são obrigatórios."
                ]);
                return;
            }

            $categoriaModel = new CategoriaModel(
                (int) $data["idCategoria"],
                $data["nomeCategoria"]
            );

            $ok = $categoriaModel->gravar();

            if ($ok) {
                $this->res(200, [
                    "message" => "Categoria alterada com sucesso."
                ]);
            } else {
                $this->res(400, [
                    "message" => "Erro ao alterar categoria."
                ]);
            }
        } catch (Exception $e) {

            $this->res(500, [
                "message" => $e->getMessage(),
                "linha" => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }

    public function excluir(): void
    {
        try {

            if ($_GET["idCategoria"] === null) {
                $this->res(400, ["message" => "ID de categoria é obrigatório."]);
                return;
            }

            $idCategoria = (int) $_GET["idCategoria"];

            $categoriaModel = new CategoriaModel($idCategoria, '');
            $ok = $categoriaModel->excluir($idCategoria);

            if ($ok) {
                $this->res(200, ["message" => "Categoria excluida com sucesso."]);
            } else {
                $this->res(400, ["message" => "Erro ao excluir categoria."]);
            }
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha"   => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }


    private function res(int $statusCode, mixed $data): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
