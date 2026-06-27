<?php

class Database
{

    private $conexao;


    public function __construct()
    {

        $host = "127.0.0.1";
        $port = 3306;
        $database = "php";
        $user = "root";
        $password = "root";


        $this->conexao = new PDO(
            "mysql:host=$host;port=$port;dbname=$database;charset=utf8",
            $user,
            $password
        );


        $this->conexao->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
    }


    public function getConexao()
    {
        return $this->conexao;
    }



    public function iniciarTransacao()
    {

        return $this->conexao->beginTransaction();
    }



    public function rollback()
    {

        return $this->conexao->rollBack();
    }



    public function commit()
    {

        return $this->conexao->commit();
    }



    public function executarComando($sql, $valores = [])
    {

        $stmt = $this->conexao->prepare($sql);


        $stmt->execute($valores);


        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function executarComandoNonQuery($sql, $valores = [])
    {
        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute($valores);
    }



    public function executarComandoLastInserted($sql, $valores = [])
    {

        $stmt = $this->conexao->prepare($sql);


        $stmt->execute($valores);


        return $this->conexao->lastInsertId();
    }
}
