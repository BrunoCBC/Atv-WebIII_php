<?php
session_start();
include '../config/banco.php';

$banco = new Banco();
$conn = $banco->getConexao();

if (isset($_POST['comentario_id'])) {
    $comentario_id = (int)$_POST['comentario_id'];
    $usuario_id = $_SESSION['usuario_logado'];

    // Verifica se o usuário já curtiu o comentário
    $query = "SELECT * FROM likes WHERE comentario_id = :comentario_id AND usuario_id = :usuario_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':comentario_id', $comentario_id, PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Se já curtiu, descurte
        $query = "DELETE FROM likes WHERE comentario_id = :comentario_id AND usuario_id = :usuario_id";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':comentario_id', $comentario_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Descurtiu o comentário!']);
    } else {
        // Se não curtiu, curte
        $query = "INSERT INTO likes (comentario_id, usuario_id) VALUES (:comentario_id, :usuario_id)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':comentario_id', $comentario_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'message' => 'Curtiu o comentário!']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do comentário não fornecido.']);
}