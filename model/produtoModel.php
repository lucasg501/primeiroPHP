<?php

require_once __DIR__ . '/../utils/database.php';

class Produto implements JsonSerializable
{
    private int $idProduto;
    private string $nomeProduto;
    private float $valorProduto;
    private int $idCategoria;

    private Database $banco;

    public function __construct(
        int $idProduto = 0,
        string $nomeProduto = '',
        float $valorProduto = 0,
        int $idCategoria = 0
    ) {
        $this->idProduto    = $idProduto;
        $this->nomeProduto  = $nomeProduto;
        $this->valorProduto = $valorProduto;
        $this->idCategoria  = $idCategoria;

        $this->banco = new Database();
    }

    // --- Getters e Setters ---

    public function getIdProduto(): int
    {
        return $this->idProduto;
    }

    public function setIdProduto(int $idProduto): void
    {
        $this->idProduto = $idProduto;
    }

    public function getNomeProduto(): string
    {
        return $this->nomeProduto;
    }

    public function setNomeProduto(string $nomeProduto): void
    {
        $this->nomeProduto = $nomeProduto;
    }

    public function getValorProduto(): float
    {
        return $this->valorProduto;
    }

    public function setValorProduto(float $valorProduto): void
    {
        $this->valorProduto = $valorProduto;
    }

    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(int $idCategoria): void
    {
        $this->idCategoria = $idCategoria;
    }

    // --- JsonSerializable: permite json_encode() funcionar corretamente ---

    public function jsonSerialize(): array
    {
        return [
            'idProduto'    => $this->idProduto,
            'nomeProduto'  => $this->nomeProduto,
            'valorProduto' => $this->valorProduto,
            'idCategoria'  => $this->idCategoria
        ];
    }

    // --- Operações de banco ---

    public function listar(): array
    {
        $sql  = "SELECT * FROM produto";
        $rows = $this->banco->executarComando($sql, []);

        $lista = [];
        foreach ($rows as $row) {
            $lista[] = new Produto(
                (int)   $row['idProduto'],
                        $row['nomeProduto'],
                (float) $row['valorProduto'],
                (int)   $row['idCategoria']
            );
        }
        return $lista;
    }

    public function obter(int $idProduto): Produto|false
    {
        $sql    = "SELECT * FROM produto WHERE idProduto = ?";
        $rows   = $this->banco->executarComando($sql, [$idProduto]);

        if (count($rows) > 0) {
            return new Produto(
                (int)   $rows[0]['idProduto'],
                        $rows[0]['nomeProduto'],
                (float) $rows[0]['valorProduto'],
                (int)   $rows[0]['idCategoria']
            );
        }
        return false;
    }

    public function gravar(): bool
    {
        if ($this->idProduto == 0) {
            // INSERT
            $sql    = "INSERT INTO produto (nomeProduto, valorProduto, idCategoria) VALUES (?, ?, ?)";
            $valores = [$this->nomeProduto, $this->valorProduto, $this->idCategoria];
        } else {
            // UPDATE
            $sql    = "UPDATE produto SET nomeProduto = ?, valorProduto = ?, idCategoria = ? WHERE idProduto = ?";
            $valores = [$this->nomeProduto, $this->valorProduto, $this->idCategoria, $this->idProduto];
        }

        return $this->banco->executarComandoNonQuery($sql, $valores);
    }

    public function excluir(): bool
    {
        $sql    = "DELETE FROM produto WHERE idProduto = ?";
        $valores = [$this->idProduto];
        return $this->banco->executarComandoNonQuery($sql, $valores);
    }
}
