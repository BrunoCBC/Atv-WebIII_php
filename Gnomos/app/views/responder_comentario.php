<?php
session_start();
include '../config/Banco.php';

$banco = new Banco();
$conn = $banco->getConexao();

// Obtenha os dados do comentário
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : 0; // Mantém o parent_id para respostas
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

if ($post_id <= 0 || empty($comentario)) {
    echo "<script>alert('Dados inválidos.'); window.location.href='index.php';</script>";
    exit;
}

// Obtenha o ID do usuário logado
$usuario_id = $_SESSION['usuario_logado'];

// Inserir o novo comentário no banco de dados
$query = "INSERT INTO comentarios (post_id, parent_id, usuario_id, comentario, data_criacao) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);

try {
    $stmt->execute([$post_id, $parent_id, $usuario_id, $comentario]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo "<script>alert('Erro ao enviar o comentário: " . addslashes($e->getMessage()) . "'); window.location.href='show_post.php?id=$post_id';</script>";
}
?>
