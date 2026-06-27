<?php

require_once __DIR__ . '/../utils/database.php';

class CategoriaModel implements JsonSerializable
{
    private int $idCategoria;
    private string $nomeCategoria;

    private Database $banco;

    public function __construct(
        int $idCategoria,
        string $nomeCategoria
    ) {
        $this->idCategoria = $idCategoria;
        $this->nomeCategoria = $nomeCategoria;

        $this->banco = new Database();
    }

    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }
    public function setIdCategoria(int $idCategoria): void
    {
        $this->idCategoria = $idCategoria;
    }

    public function getNomeCategoria(): string
    {
        return $this->nomeCategoria;
    }
    public function setNomeCategoria(string $nomeCategoria): void
    {
        $this->nomeCategoria = $nomeCategoria;
    }

    public function jsonSerialize(): array
    {
        return [
            'idCategoria' => $this->idCategoria,
            'nomeCategoria' => $this->nomeCategoria
        ];
    }

    public function listar(): array
    {
        $sql = "select * from categoria";
        $rows = $this->banco->executarComando($sql, []);
        $lista = [];
        foreach ($rows as $row) {
            $lista[] = new CategoriaModel(
                (int)$row['idCategoria'],
                $row['nomeCategoria']
            );
        }
        return $lista;
    }

    public function obter(int $idCategoria): CategoriaModel|false
    {
        $sql = "select * from categoria where idCategoria = ?";
        $valores = [$idCategoria];
        $rows = $this->banco->executarComando($sql, $valores);
        if (count($rows) > 0) {
            return new CategoriaModel(
                (int)$rows[0]['idCategoria'],
                $rows[0]['nomeCategoria']
            );
        }
        return false;
    }

    public function gravar(): bool
    {
        if ($this->idCategoria == 0) {

            $sql = "INSERT INTO categoria (nomeCategoria) VALUES (?)";

            $valores = [
                $this->nomeCategoria
            ];

            return $this->banco->executarComandoNonQuery($sql, $valores);
        } else {

            $sql = "UPDATE categoria 
                SET nomeCategoria = ?
                WHERE idCategoria = ?";

            $valores = [
                $this->nomeCategoria,
                $this->idCategoria
            ];

            return $this->banco->executarComandoNonQuery($sql, $valores);
        }
    }

    public function excluir(int $idCategoria): bool
    {
        $sql = "delete from categoria where idCategoria = ?";
        $valores = [$idCategoria];
        $ok = $this->banco->executarComandoNonQuery($sql, $valores);
        return $ok;
    }
}
