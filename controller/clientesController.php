<?php

require_once __DIR__ . '/../model/clientesModel.php';

class ClienteController
{

    private function res(int $statusCode, mixed $data): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function listar()
    {
        try {

            $clientesModel = new ClientesModel($idCliente = 0, $nomeCliente = '');
            $lista = $clientesModel->listar();
            $this->res(200, $lista);
        } catch (Exception $e) {
            http_response_code(500);

            echo json_encode([
                "message" => $e->getMessage(),
                "linha" => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }

    public function obter(): void
    {
        try {

            $idCliente = (int)($_GET["idCliente"] ?? 0);

            if ($idCliente <= 0) {
                $this->res(400, ["message" => "ID de cliente inválido."]);
                return;
            }

            $clientesModel = new ClientesModel($idCliente, '');
            $cliente = $clientesModel->obter($idCliente);

            if ($cliente) {
                $this->res(200, $cliente);
            } else {
                $this->res(404, ["message" => "Cliente não encontrado"]);
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

            if (empty($data["nomeCliente"])) {
                $this->res(400, ["message" => "Nome do cliente é obrigatório."]);
                return;
            }

            $clientesModel = new ClientesModel(0, '');
            $clientesModel->setNomeCliente($data["nomeCliente"]);

            $ok = $clientesModel->gravar();

            if ($ok) {
                $this->res(201, ["message" => "Cliente gravado com sucesso."]);
            } else {
                $this->res(400, ["message" => "Erro ao gravar cliente."]);
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

    public function alterar(): void
    {
        try {

            $data = json_decode(file_get_contents('php://input'), true);

            $idCliente = (int)($data["idCliente"] ?? 0);

            if ($idCliente <= 0) {
                $this->res(400, ["message" => "ID de cliente inválido."]);
                return;
            }

            if (empty($data["nomeCliente"])) {
                $this->res(400, ["message" => "Nome do cliente é obrigatório."]);
                return;
            }

            $clientesModel = new ClientesModel($idCliente, '');
            $clientesModel->setNomeCliente($data["nomeCliente"]);

            $ok = $clientesModel->gravar();

            if ($ok) {
                $this->res(200, ["message" => "Cliente alterado com sucesso."]);
            } else {
                $this->res(400, ["message" => "Erro ao alterar cliente."]);
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

    public function excluir(): void
    {
        try{

            if($_GET["idCliente"] === null){
                $this->res(400, ["message" => "ID de cliente inválido."]);
                return;
            }

            $idCliente = (int) $_GET["idCliente"];

            $clientesModel = new ClientesModel($idCliente, '');
            $ok = $clientesModel->excluir($idCliente);

            if($ok){
                $this->res(200, ["message" => "Cliente excluido com sucesso."]);
            }else{
                $this->res(400, ["message" => "Erro ao excluir cliente."]);
            }

        }catch(Exception $e){
            http_response_code(500);
            echo json_encode([
                "message" => $e->getMessage(),
                "linha" => $e->getLine(),
                "arquivo" => $e->getFile()
            ]);
        }
    }
}
