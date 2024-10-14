<?php
session_start();
header('Content-Type: application/json'); // Define o cabeçalho como JSON

include '../config/banco.php';

$banco = new Banco();
$conn = $banco->getConexao();

// Verifica se a requisição é do tipo POST e se o usuário está logado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_logado'])) {
    // Receber o corpo da requisição e decodificar
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['comentario_id'])) {
        $comentario_id = (int)$data['comentario_id']; // Obter o ID do comentário do JSON
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

            echo json_encode(['status' => 'success', 'message' => 'Descurtiu o comentário!', 'comentario_id' => $comentario_id]);
        } else {
            // Se não curtiu, curte
            $query = "INSERT INTO likes (comentario_id, usuario_id) VALUES (:comentario_id, :usuario_id)";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':comentario_id', $comentario_id, PDO::PARAM_INT);
            $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(['status' => 'success', 'message' => 'Curtiu o comentário!', 'comentario_id' => $comentario_id]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID do comentário não fornecido.', 'comentario_id' => null]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Requisição inválida ou usuário não autenticado.']);
}
