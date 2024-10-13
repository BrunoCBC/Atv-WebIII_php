<?php
session_start();
include '../../config/Banco.php';

if (!isset($_SESSION['usuario_logado']) || $_SESSION['role'] !== 'administrador') {
    header("Location: ../../../public/index.php");
    exit();
}

$db = new Banco();
$conexao = $db->getConexao();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $conteudo = trim($_POST['conteudo']);
    $imagem = $_FILES['imagem']['name'];
    $imagem_temp = $_FILES['imagem']['tmp_name'];
    $categoria_id = $_POST['categoria_id']; 
    
    $imagem_nova = uniqid() . '_' . basename($imagem);
    $diretorio_imagens = '../../../public/images/'; 
    
    if (move_uploaded_file($imagem_temp, $diretorio_imagens . $imagem_nova)) {
        $query = "INSERT INTO post (titulo, conteudo, imagem, autor_id, categoria_id) VALUES (:titulo, :conteudo, :imagem, :autor_id, :categoria_id)";
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':titulo', $titulo);
        $stmt->bindValue(':conteudo', $conteudo);
        $stmt->bindValue(':imagem', $imagem_nova);
        $stmt->bindValue(':autor_id', $_SESSION['usuario_logado']);
        $stmt->bindValue(':categoria_id', $categoria_id); 
        
        if ($stmt->execute()) {
            echo "<script>alert('Post criado com sucesso!'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erro ao criar o post. Tente novamente.');</script>";
        }
    } else {
        echo "<script>alert('Erro ao fazer upload da imagem.');</script>";
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
    <h2 class="text-center">Criar Novo Post</h2>
    <form action="criar_post.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="conteudo">Conteúdo:</label>
            <div id="editor" style="height: auto;"></div>
            <input type="hidden" name="conteudo" id="conteudo" required>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select class="form-control" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                <?php
                $query_categorias = "SELECT * FROM categorias";
                $result_categorias = $conexao->query($query_categorias);
                while ($categoria = $result_categorias->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nome']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="imagem">Imagem:</label>
            <input type="file" class="form-control-file" name="imagem" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar Post</button>
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

    // Ajusta a altura do editor
    function resizeEditor() {
        const editorContainer = document.querySelector('.ql-editor');
        editorContainer.style.height = 'auto';
        editorContainer.style.height = (editorContainer.scrollHeight) + 'px';
    }

    quill.on('text-change', resizeEditor);
    resizeEditor(); // Ajusta a altura inicial

    document.querySelector('form').onsubmit = function() {
        const contenido = quill.root.innerHTML;
        document.querySelector('#conteudo').value = contenido;
    };
</script>



<?php include '../../../public/includes/footer.php'; ?>