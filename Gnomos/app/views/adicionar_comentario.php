<?php
session_start();
include '../config/Banco.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado'])) {
    echo "<script>alert('Você precisa estar logado para deixar um comentário.'); window.location.href='/Gnomos/public/login.php';</script>";
    exit();
}

// Cria a conexão com o banco de dados
$db = new Banco();
$conn = $db->getConexao();

if (!$conn) {
    die("Falha na conexão com o banco de dados.");
}

// Processa o envio de um comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];
    $usuario_id = $_SESSION['usuario_logado'];
    $comentario = trim($_POST['comentario']);
    
    // Não usamos mais parent_id
    if ($post_id > 0 && !empty($comentario)) {
        $query = "INSERT INTO comentarios (post_id, usuario_id, comentario, parent_id) VALUES (:post_id, :usuario_id, :comentario, NULL)"; // Define parent_id como NULL
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindValue(':comentario', $comentario, PDO::PARAM_STR);

        try {
            if ($stmt->execute()) {
                echo "<script>alert('Comentário adicionado com sucesso!'); window.location.href='show_post.php?id=$post_id';</script>";
            } else {
                echo "<script>alert('Erro ao adicionar o comentário. Tente novamente.'); window.location.href='show_post.php?id=$post_id';</script>";
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            echo "<script>alert('Erro ao adicionar o comentário: " . addslashes($e->getMessage()) . "'); window.location.href='show_post.php?id=$post_id';</script>";
        }
        exit();
    } else {
        echo "<script>alert('Dados inválidos.'); window.location.href='show_post.php?id=$post_id';</script>";
        exit();
    }
}
?>
