<?php

class Comentario {
    private $conexao;
    private $id;
    private $post_id;
    private $usuario_id;
    private $texto;
    private $parent_id; // Adicione o parent_id aqui

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function setId($id) {
        $this->id = htmlspecialchars(strip_tags($id));
    }

    public function setPostId($post_id) {
        $this->post_id = htmlspecialchars(strip_tags($post_id));
    }

    public function setUsuarioId($usuario_id) {
        $this->usuario_id = htmlspecialchars(strip_tags($usuario_id));
    }

    public function setTexto($texto) {
        $this->texto = htmlspecialchars(strip_tags($texto));
    }

    public function setParentId($parent_id) { // Adicione este método
        $this->parent_id = htmlspecialchars(strip_tags($parent_id));
    }

    public function create() {
        try {
            $query = "INSERT INTO comentarios (post_id, usuario_id, texto, data_criacao, parent_id) VALUES (:post_id, :usuario_id, :texto, NOW(), :parent_id)";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":post_id", $this->post_id);
            $stmt->bindParam(":usuario_id", $this->usuario_id);
            $stmt->bindParam(":texto", $this->texto);
            $stmt->bindParam(":parent_id", $this->parent_id); // Adicione este bind

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar comentário: " . $e->getMessage());
            return false;
        }
    }

    public function read() {
        try {
            $query = "SELECT * FROM comentarios WHERE id = :id LIMIT 1";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao ler comentário: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $query = "UPDATE comentarios SET texto = :texto WHERE id = :id";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":texto", $this->texto);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar comentário: " . $e->getMessage());
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM comentarios WHERE id = :id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar comentário: " . $e->getMessage());
            return false;
        }
    }
}
?>
