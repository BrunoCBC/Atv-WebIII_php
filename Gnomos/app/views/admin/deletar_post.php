<?php
session_start();
include '../../config/Banco.php';

if (!isset($_SESSION['usuario_logado']) || $_SESSION['role'] !== 'administrador') {
    header("Location: ../../../public/index.php");
    exit();
}

// Conexão com o banco de dados
$db = new Banco();
$conexao = $db->getConexao();

if (isset($_GET['id'])) {
    $post_id = (int)$_GET['id']; // Certifique-se de que o ID seja um inteiro

    // Prepare a consulta para excluir o post
    $query = "DELETE FROM post WHERE id = :id";
    $stmt = $conexao->prepare($query);
    $stmt->bindValue(':id', $post_id);

    if ($stmt->execute()) {
        // Supondo que você deseje redirecionar de volta para o dashboard com uma mensagem de sucesso
        echo "<script>alert('Post excluído com sucesso!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao excluir o post. Tente novamente.'); window.location.href='dashboard.php';</script>";
    }
} else {
    echo "<script>alert('ID do post não fornecido.'); window.location.href='dashboard.php';</script>";
}
?>
