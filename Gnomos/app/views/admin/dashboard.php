<?php
include '../../config/Banco.php';

session_start();

if (!isset($_SESSION['usuario_logado']) || $_SESSION['role'] !== 'administrador') {
    header("Location: ../../../public/index.php");
    exit();
}

$db = new Banco();
$conexao = $db->getConexao();

$query_users = "SELECT COUNT(*) as total FROM usuarios";
$query_posts = "SELECT COUNT(*) as total FROM post";
$query_comments = "SELECT COUNT(*) as total FROM comentarios";

$total_users = $conexao->query($query_users)->fetchColumn();
$total_posts = $conexao->query($query_posts)->fetchColumn();
$total_comments = $conexao->query($query_comments)->fetchColumn();
?>

<?php include '../../../public/includes/header.php'; ?>

<div class="container mt-5 mb-4">
    <h2 class="text-center">Painel de Administração</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total de Usuários Cadastrados</h5>
                    <p class="card-text"><?php echo $total_users; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total de Posts</h5>
                    <p class="card-text"><?php echo $total_posts; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Total de Comentários</h5>
                    <p class="card-text"><?php echo $total_comments; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3>Lista de Posts</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query_all_posts = "SELECT p.id, p.titulo, u.nome as autor, c.nome as categoria
                                    FROM post p
                                    JOIN usuarios u ON p.autor_id = u.id
                                    LEFT JOIN categorias c ON p.categoria_id = c.id
                                    ORDER BY p.data_criacao DESC";
                $result_all_posts = $conexao->query($query_all_posts);

                $posts = $result_all_posts->fetchAll(PDO::FETCH_ASSOC);

                if (count($posts) > 0) {
                    foreach ($posts as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($row['autor']); ?></td>
                            <td><?php echo htmlspecialchars($row['categoria'] ? $row['categoria'] : 'Sem Categoria'); ?></td>
                            <td>
                                <a href="editar_post.php?id=<?php echo $row['id']; ?>" class="btn btn-editar">Editar</a>
                                <a href="javascript:void(0);" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" class="text-center">Nenhum post encontrado.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(postId) {
        if (confirm("Tem certeza que deseja excluir este post?")) {
            window.location.href = 'deletar_post.php?id=' + postId;
        }
    }
</script>


<?php include '../../../public/includes/footer.php'; ?>
