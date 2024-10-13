<?php

class Cliente {
    private $conexao;
    private $id;
    private $nome;
    private $telefone;
    private $email;
    private $cpf;

    // Construtor para inicializar a conexão com o banco de dados
    public function __construct($db) {
        $this->conexao = $db;
    }
    
    // Métodos de 'Setter' para atribuir valores às propriedades privadas
    public function setId($id) {
        $this->id = htmlspecialchars(strip_tags($id));
    }

    public function setNome($nome) {
        $this->nome = htmlspecialchars(strip_tags($nome));
    }

    public function setCPF($cpf) {
        $this->cpf = htmlspecialchars(strip_tags($cpf));
    }

    public function setTelefone($telefone) {
        $this->telefone = htmlspecialchars(strip_tags($telefone));
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars(strip_tags($email));
    }

    // Método para criar um novo cliente no banco de dados
    public function create() {
        try {
            $query = "INSERT INTO cliente (nome, telefone, email, cpf) VALUES (:nome, :telefone, :email, :cpf)";
            $stmt = $this->conexao->prepare($query);

            // Bind dos parâmetros
            $stmt->bindParam(":nome", $this->nome);
            $stmt->bindParam(":telefone", $this->telefone);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":cpf", $this->cpf);

            // Executa a query e retorna true se bem-sucedida
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao criar cliente: " . $e->getMessage();
            return false;
        }
    }
    
    // Método para ler todos os clientes do banco de dados
    public function read() {
        try {
            $query = "SELECT * FROM cliente";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Erro ao ler clientes: " . $e->getMessage();
            return null;
        }
    }

    // Método para atualizar os dados de um cliente existente
    public function update() {
        try {
            $query = "UPDATE cliente SET nome=:nome, telefone=:telefone, email=:email, cpf=:cpf WHERE id=:id";
            $stmt = $this->conexao->prepare($query);

            // Bind dos parâmetros
            $stmt->bindParam(":nome", $this->nome);
            $stmt->bindParam(":telefone", $this->telefone);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":cpf", $this->cpf);
            $stmt->bindParam(":id", $this->id);

            // Executa a query e retorna true se bem-sucedida
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar cliente: " . $e->getMessage();
            return false;
        }
    }

    // Método para excluir um cliente do banco de dados
    public function delete() {
        try {
            $query = "DELETE FROM cliente WHERE id=:id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);

            // Executa a query e retorna true se bem-sucedida
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao deletar cliente: " . $e->getMessage();
            return false;
        }
    }

    // Método para consultar um cliente específico pelo ID
    public function consultar() {
        try {
            $query = "SELECT * FROM cliente WHERE id = :id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();

            return $stmt;
        } catch (PDOException $e) {
            echo "Erro ao consultar cliente: " . $e->getMessage();
            return null;
        }
    }
}
?>