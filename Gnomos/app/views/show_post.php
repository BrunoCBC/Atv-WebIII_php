<?php
include '../config/banco.php';

$banco = new Banco();
$conn = $banco->getConexao();

// Obtém o ID do post a partir da URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id <= 0) {
    echo "<script>alert('ID do post inválido!'); window.location.href='index.php';</script>";
    exit;
}

// Busca o post e a categoria associada no banco de dados
$query = "SELECT p.*, c.nome AS categoria_nome, c.id AS categoria_id 
          FROM post p 
          LEFT JOIN categorias c ON p.categoria_id = c.id 
          WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $post_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $post = $result[0];
} else {
    echo "<script>alert('Post não encontrado!'); window.location.href='index.php';</script>";
    exit;
}

// Função para contar os likes de um comentário
function contarLikes($conn, $comentario_id) {
    $query = "SELECT COUNT(*) FROM likes WHERE comentario_id = :comentario_id";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':comentario_id', $comentario_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Função para exibir comentários recursivamente
function exibirComentarios($conn, $post_id, $usuario_id, $parent_id = null) {
    $query = "SELECT c.*, u.nome, 
            (SELECT COUNT(*) FROM likes WHERE comentario_id = c.id AND usuario_id = :usuario_id) AS liked 
            FROM comentarios c 
            JOIN usuarios u ON c.usuario_id = u.id 
            WHERE c.post_id = :post_id AND c.parent_id " . ($parent_id ? "= :parent_id" : "IS NULL");

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    if ($parent_id) {
        $stmt->bindValue(':parent_id', $parent_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os comentários encontrados
}

// Função para exibir comentários e suas respostas aninhadas
function renderizarComentarios($comentarios, $conn, $post_id, $usuario_id, $nivel = 0) {
    foreach ($comentarios as $comentario) {
        // Exibe o comentário principal sem a linha azul
        if ($nivel === 0) {
            echo "<div class='comentario mb-3' data-id='" . $comentario['id'] . "'>"; 
        } else {
            // Exibe a resposta com a linha azul
            echo "<div class='resposta mb-2' style='margin-left: " . ($nivel * 20) . "px; border-left: 3px solid blue; padding-left: 10px;' data-id='" . $comentario['id'] . "'>"; // Linha azul ao lado
        }
        
        echo "<strong>" . htmlspecialchars($comentario['nome']) . "</strong>";
        echo "<p>" . htmlspecialchars($comentario['comentario']) . "</p>";
        echo "<div class='d-flex justify-content-between align-items-center'>";
        echo "<small>" . date('d/m/Y H:i:s', strtotime($comentario['data_criacao'])) . "</small>";
        echo "<div>";
        $likedClass = $comentario['liked'] > 0 ? 'liked' : ''; // Verifica se o comentário foi curtido
        echo "<button class='btn btn-link like $likedClass' data-id='" . $comentario['id'] . "'>👍 " . contarLikes($conn, $comentario['id']) . "</button>";
        echo "<button class='btn btn-link responder' data-id='" . $comentario['id'] . "'>Responder</button>";
        echo "</div>";
        echo "</div>";

        // Formulário de resposta dinâmico que será exibido ao clicar em "Responder"
        echo "<div class='form-resposta-container' style='display: none;' data-parent-id='" . $comentario['id'] . "'>";
        echo "<form class='resposta-form' action='responder_comentario.php' method='POST'>";
        echo "<input type='hidden' name='post_id' value='$post_id'>";
        echo "<input type='hidden' name='parent_id' value='" . $comentario['id'] . "'>"; // ID do comentário pai
        echo "<textarea name='comentario' required class='form-control' placeholder='Digite sua resposta...'></textarea>";
        echo "<button type='submit' class='btn btn-primary mt-2'>Enviar</button>";
        echo "</form>";
        echo "</div>";

        // Chama a função para obter as respostas para o comentário atual
        $respostas = exibirComentarios($conn, $post_id, $usuario_id, $comentario['id']);
        
        // Verifica se existem respostas
        if (count($respostas) > 0) {
            echo "<div class='respostas mt-3'>"; // Contêiner para respostas
            renderizarComentarios($respostas, $conn, $post_id, $usuario_id, $nivel + 1); // Aumenta o nível para respostas aninhadas
            echo "</div>"; // Fecha div respostas
        }

        echo "</div>"; // Fecha a div do comentário ou resposta
    }
}

include '../../public/includes/header.php'; 
?>

<div class="container mt-5">
    <!-- Exibição do link para a categoria antes do título do post -->
    <?php if (!empty($post['categoria_nome'])): ?>
        <a href="show_categoria.php?categoria_id=<?php echo htmlspecialchars($post['categoria_id']); ?>" class="categoria-link">
            <?php echo htmlspecialchars($post['categoria_nome']); ?>
        </a>
    <?php endif; ?>

    <h2 class="title-home"><?php echo htmlspecialchars($post['titulo']); ?></h2>
    <div class="img-post-box">
        <img src="/gnomos/public/images/<?php echo htmlspecialchars($post['imagem']); ?>" class="img-fluid mb-4 img-post" alt="<?php echo htmlspecialchars($post['titulo']); ?>">
    </div>
    <div><?php echo $post['conteudo']; ?></div> <!-- Conteúdo do post -->
</div>

<div class="container mt-5">
    <h3>Comentários</h3>
    <?php
        // Certifique-se de que o $usuario_id esteja definido antes de chamar a função
        $usuario_id = isset($_SESSION['usuario_logado']) ? $_SESSION['usuario_logado'] : null;

        // Chama a função para exibir os comentários
        $comentarios = exibirComentarios($conn, $post_id, $usuario_id);

        // Verifica se existem comentários
        if (count($comentarios) > 0) {
            renderizarComentarios($comentarios, $conn, $post_id, $usuario_id); // Chama a função para renderizar os comentários
        } else {
            echo "<p>Nenhum comentário ainda.</p>"; // Mensagem se não houver comentários
        }
    ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.responder').forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault(); // Impede o comportamento padrão do botão, se necessário
            const comentarioId = button.getAttribute('data-id');
            const formContainer = document.querySelector(`.form-resposta-container[data-parent-id='${comentarioId}']`);

            // Alterna a visibilidade do formulário específico
            if (formContainer) {
                formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});
</script>

<?php if (isset($_SESSION['usuario_logado'])): ?>
    <div class="container mt-5  mb-4">
        <h3>Deixe um comentário</h3>

        <!-- Formulário de comentário inicial, sempre visível -->
        <form class='resposta-form' action='adicionar_comentario.php' method='POST'>
            <input type='hidden' name='post_id' value='<?php echo $post_id; ?>'>
            <input type='hidden' name='parent_id' value='0'> <!-- 0 para comentários principais -->
            <textarea name='comentario' required class='form-control' placeholder='Digite seu comentário...'></textarea>
            <button type='submit' class='btn btn-primary mt-2'>Enviar</button>
        </form>
    </div>
<?php else: ?>
    <div class="container mt-5 mb-4">
        <p>Você precisa estar logado para comentar.</p>
    </div>
<?php endif; ?>

<?php include '../../public/includes/footer.php'; ?>
