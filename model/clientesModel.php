<?php

require_once __DIR__ .'/../utils/database.php';

class ClientesModel implements JsonSerializable
{
    private int $idCliente;
    private string $nomeCliente;
    private Database $banco;

    public function __construct(int $idCliente, string $nomeCliente)
    {
        $this->idCliente = $idCliente;
        $this->nomeCliente = $nomeCliente;

        $this->banco = new Database();

    }

    public function getIdCliente(): int
    {
        return $this->idCliente;
    }
    public function setIdCliente(int $idCliente): void
    {
        $this->idCliente = $idCliente;
    }

    public function getNomeCliente(): string
    {
        return $this->nomeCliente;
    }
    public function setNomeCliente(string $nomeCliente): void
    {
        $this->nomeCliente = $nomeCliente;
    }

    public function jsonSerialize(): array
    {
        return [
            'idCliente' => $this->idCliente,
            'nomeCliente' => $this->nomeCliente
        ];
    }

    public function listar(): array
    {
        $sql = "select * from clientes";
        $rows = $this->banco->executarComando($sql, []);
        $lista = [];
        foreach($rows as $row){
            $lista[] = new ClientesModel(
                (int)$row['idCliente'],
                $row['nomeCliente']
            );
        }
        return $lista;
    }

    public function obter(int $idCliente): ClientesModel|false
    {
        $sql = "select * from clientes where idCliente = ?";
        $valores = [$idCliente];
        $rows = $this->banco->executarComando($sql, $valores);
        if(count($rows) > 0){
            return new ClientesModel(
                (int)$rows[0]['idCliente'],
                $rows[0]['nomeCliente']
            );
        }
        return false;
    }

    public function gravar(): bool
    {
        if($this->idCliente == 0){
            $sql = "insert into clientes (nomeCliente) values (?)";
            $valores = [$this->nomeCliente];
            return $this->banco->executarComandoNonQuery($sql, $valores);
        }else{
            $sql = "update clientes set nomeCliente = ? where idCliente = ?";
            $valores = [$this->nomeCliente, $this->idCliente];
            return $this->banco->executarComandoNonQuery($sql, $valores);
        }
    }

    public function excluir(int $idCliente): bool
    {
        $sql = "delete from clientes where idCliente = ?";
        $valores = [$idCliente];
        $ok = $this->banco->executarComandoNonQuery($sql, $valores);
        return $ok;
    }

}