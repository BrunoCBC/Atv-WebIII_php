<?php

class Post {
    private $conexao;
    private $id;
    private $titulo;
    private $conteudo;
    private $autor_id;
    private $categoria;
    private $imagem;

    public function __construct($db) {
        $this->conexao = $db;
    }

    public function setId($id) {
        $this->id = htmlspecialchars(strip_tags($id));
    }

    public function setTitulo($titulo) {
        $this->titulo = htmlspecialchars(strip_tags($titulo));
    }

    public function setConteudo($conteudo) {
        $this->conteudo = htmlspecialchars(strip_tags($conteudo));
    }

    public function setAutorId($autor_id) {
        $this->autor_id = htmlspecialchars(strip_tags($autor_id));
    }

    public function setCategoria($categoria) {
        $this->categoria = htmlspecialchars(strip_tags($categoria));
    }

    public function setImagem($imagem) {
        $this->imagem = htmlspecialchars(strip_tags($imagem));
    }

    public function create() {
        try {
            $query = "INSERT INTO posts (titulo, conteudo, autor_id, categoria, data_criacao, imagem) VALUES (:titulo, :conteudo, :autor_id, :categoria, NOW(), :imagem)";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":titulo", $this->titulo);
            $stmt->bindParam(":conteudo", $this->conteudo);
            $stmt->bindParam(":autor_id", $this->autor_id);
            $stmt->bindParam(":categoria", $this->categoria);
            $stmt->bindParam(":imagem", $this->imagem);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao criar post: " . $e->getMessage());
            return false;
        }
    }

    public function read() {
        try {
            $query = "SELECT * FROM posts WHERE id = :id LIMIT 1";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao ler post: " . $e->getMessage());
            return false;
        }
    }

    public function update() {
        try {
            $query = "UPDATE posts SET titulo = :titulo, conteudo = :conteudo, categoria = :categoria WHERE id = :id";
            $stmt = $this->conexao->prepare($query);

            $stmt->bindParam(":titulo", $this->titulo);
            $stmt->bindParam(":conteudo", $this->conteudo);
            $stmt->bindParam(":categoria", $this->categoria);
            $stmt->bindParam(":id", $this->id);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar post: " . $e->getMessage());
            return false;
        }
    }

    public function delete() {
        try {
            $query = "DELETE FROM posts WHERE id = :id";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindParam(":id", $this->id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar post: " . $e->getMessage());
            return false;
        }
    }

    public function readAll() {
        try {
            $query = "SELECT * FROM posts ORDER BY data_criacao DESC";
            $stmt = $this->conexao->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao ler posts: " . $e->getMessage());
            return false;
        }
    }

    public function readLatestPosts($limit = 3) {
        try {
            $query = "SELECT * FROM post ORDER BY data_criacao DESC LIMIT :limit";  
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao ler últimos posts: " . $e->getMessage());
            return false;
        }
    }    
}
?>
