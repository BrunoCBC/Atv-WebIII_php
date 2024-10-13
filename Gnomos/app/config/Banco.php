<?php

class Banco {
    private $host = 'localhost';
    private $dbname = 'Gnomos';
    private $username = 'root';
    private $password = '';
    private $conexao;

    public function getConexao() {
        try {
            $this->conexao = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->dbname, $this->username, $this->password);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conexao;
        } catch (PDOException $e) {
            echo "Erro na conexão: " . $e->getMessage();
            return null;
        }
    }
}
