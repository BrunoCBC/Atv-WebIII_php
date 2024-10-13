<?php
session_start();
include '../../config/Banco.php';

if (!isset($_SESSION['usuario_logado']) || $_SESSION['role'] !== 'administrador') {
    header("Location: ../../../public/index.php");
    exit();
}

$db = new Banco();
$conexao = $db->getConexao();

// Verifica se o ID do post foi passado na URL
if (!isset($_GET['id'])) {
    header("Location: dashboard.php"); // Redireciona se não houver ID
    exit();
}

$post_id = intval($_GET['id']);

// Consulta para buscar o post a ser editado
$query = "SELECT p.titulo, p.conteudo, p.imagem, p.categoria_id FROM post p WHERE p.id = :post_id";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
$stmt->execute();
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o post existe
if (!$post) {
    header("Location: dashboard.php"); // Redireciona se o post não for encontrado
    exit();
}

// Consulta para buscar todas as categorias
$query_categorias = "SELECT id, nome FROM categorias";
$stmt_categorias = $conexao->prepare($query_categorias);
$stmt_categorias->execute();
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $categoria_id = $_POST['categoria_id'];
    
    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem = $_FILES['imagem']['name'];
        $imagem_temp = $_FILES['imagem']['tmp_name'];
        $imagem_nova = uniqid() . '_' . basename($imagem);
        $diretorio_imagens = '../../../public/images/';

        // Faz upload da nova imagem
        if (move_uploaded_file($imagem_temp, $diretorio_imagens . $imagem_nova)) {
            $query_update = "UPDATE post SET titulo = :titulo, conteudo = :conteudo, imagem = :imagem, categoria_id = :categoria_id WHERE id = :post_id";
            $stmt_update = $conexao->prepare($query_update);
            $stmt_update->bindParam(':imagem', $imagem_nova);
        } else {
            echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
        }
    } else {
        // Se nenhuma nova imagem foi enviada, mantém a imagem atual
        $query_update = "UPDATE post SET titulo = :titulo, conteudo = :conteudo, categoria_id = :categoria_id WHERE id = :post_id";
        $stmt_update = $conexao->prepare($query_update);
    }

    // Atualiza os dados do post
    $stmt_update->bindParam(':titulo', $titulo);
    $stmt_update->bindParam(':conteudo', $conteudo);
    $stmt_update->bindParam(':categoria_id', $categoria_id);
    $stmt_update->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        echo "<script>alert('Post atualizado com sucesso!'); window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Erro ao atualizar o post. Tente novamente.');</script>";
    }
}
?>

<?php include '../../../public/includes/header.php'; ?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<style>
    #editor {
        background-color: white; /* Define o fundo do editor como branco */
    }
    .ql-align-center {
        text-align: center; /* Centraliza o texto */
    }
    .ql-align-right {
        text-align: right; /* Alinha o texto à direita */
    }
    .ql-align-left {
        text-align: left; /* Alinha o texto à esquerda */
    }
</style>

<div class="container mt-5 mb-4">
    <h2 class="text-center">Editar Post</h2>
    <form action="editar_post.php?id=<?php echo $post_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" value="<?php echo htmlspecialchars($post['titulo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="conteudo">Conteúdo:</label>
            <div id="editor" style="height: auto;"></div>
            <input type="hidden" name="conteudo" id="conteudo" value="<?php echo htmlspecialchars($post['conteudo']); ?>" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select class="form-control" name="categoria_id" required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] == $post['categoria_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categoria['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="imagem">Nova Imagem (opcional):</label>
            <input type="file" class="form-control-file" name="imagem" accept="image/*">
            <small class="form-text text-muted">Deixe em branco se não quiser alterar a imagem atual.</small>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Post</button>
    </form>
</div>

<script>
    // Inicializa o Quill
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{'size': ['small', false, 'large', 'huge']}],
                [{ 'align': [] }],
                ['link', 'image'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['clean'],
            ]
        }
    });

    // Preenche o conteúdo no editor com o conteúdo atual do post
    quill.root.innerHTML = `<?php echo addslashes($post['conteudo']); ?>`;

    document.querySelector('form').onsubmit = function() {
        const contenido = quill.root.innerHTML;
        document.querySelector('#conteudo').value = contenido;
    };
</script>

<?php include '../../../public/includes/footer.php'; ?>
