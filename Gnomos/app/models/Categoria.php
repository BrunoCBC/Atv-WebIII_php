<?php

class Categoria {
    private $conexao;
    private $id;
    private $nome;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function setId($id) {
        $this->id = htmlspecialchars(strip_tags($id));
    }

    public function setNome($nome) {
        $this->nome = htmlspecialchars(strip_tags($nome));
    }

    public function create() {
        try {
            $query = "INSERT INTO categorias (nome) VALUES (:nome)";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":nome", $this->nome);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function read() {
        try {
            $query = "SELECT * FROM categorias WHERE id = :id LIMIT 1";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao ler categoria: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $query = "UPDATE categorias SET nome = :nome WHERE id = :id";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":nome", $this->nome);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar categoria: " . $e->getMessage());
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM categorias WHERE id = :id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar categoria: " . $e->getMessage());
            return false;
        }
    }
}
?>
