<?php
include '../../public/includes/header.php';
include '../config/Banco.php';

$db = new Banco();
$conexao = $db->getConexao();

// Verifica se uma categoria foi passada na URL
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

if ($categoria_id > 0) {
    // Se uma categoria foi selecionada, busca as informações da categoria e seus posts
    $query_categoria = "SELECT nome, descricao FROM categorias WHERE id = :categoria_id";
    $stmt_categoria = $conexao->prepare($query_categoria);
    $stmt_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_categoria->execute();
    $categoria = $stmt_categoria->fetch(PDO::FETCH_ASSOC);

    // Consulta para buscar todos os posts da categoria selecionada
    $query_posts = "SELECT p.id, p.titulo, p.conteudo, p.data_criacao, u.nome AS autor_nome
                    FROM post p
                    JOIN usuarios u ON p.autor_id = u.id
                    WHERE p.categoria_id = :categoria_id
                    ORDER BY p.data_criacao DESC";
    $stmt_posts = $conexao->prepare($query_posts);
    $stmt_posts->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_posts->execute();
    $posts = $stmt_posts->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Se nenhuma categoria foi selecionada, busca todas as categorias
    $query_categorias = "SELECT id, nome, descricao FROM categorias ORDER BY nome ASC";
    $stmt_categorias = $conexao->prepare($query_categorias);
    $stmt_categorias->execute();
    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container mt-5">
    <?php if ($categoria_id > 0 && $categoria): ?>
        <h2 class="text-center">Posts na Categoria: <?php echo htmlspecialchars($categoria['nome']); ?></h2>
        <p class="text-center"><?php echo htmlspecialchars($categoria['descricao']); ?></p>

        <?php if (count($posts) > 0): ?>
            <div class="list-group mt-4 mb-4">
                <?php foreach ($posts as $post): ?>
                    <div class="list-group-item mb-3">
                        <h4>
                        <a href="/Gnomos/app/views/show_post.php?id=<?php echo $post['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($post['titulo']); ?>
                        </a>
                        </h4>
                        <p><?php echo substr(strip_tags($post['conteudo']), 0, 150) . '...'; ?></p>
                        <small>Publicado por: <?php echo htmlspecialchars($post['autor_nome']); ?> em <?php echo date('d/m/Y', strtotime($post['data_criacao'])); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Nenhum post encontrado para esta categoria.</p>
        <?php endif; ?>
    <?php elseif ($categoria_id == 0): ?>
        <h2 class="text-center">Categorias Disponíveis</h2>

        <?php if (count($categorias) > 0): ?>
            <div class="list-group mt-4 mb-4">
                <?php foreach ($categorias as $cat): ?>
                    <a href="show_categoria.php?categoria_id=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action">
                        <h4><?php echo htmlspecialchars($cat['nome']); ?></h4>
                        <p><?php echo htmlspecialchars($cat['descricao']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">Nenhuma categoria encontrada.</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="text-center">Categoria não encontrada.</p>
    <?php endif; ?>
</div>

<?php include '../../public/includes/footer.php'; ?>