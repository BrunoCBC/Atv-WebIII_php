<?php
include 'includes/header.php';
include '../app/config/Banco.php';

$db = new Banco();
$conexao = $db->getConexao();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $role = isset($_POST['is_admin']) ? 'administrador' : 'usuario';

    $query = "INSERT INTO usuarios (nome, email, senha, role) VALUES (:nome, :email, :senha, :role)";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(":nome", $nome);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":senha", $senha);
    $stmt->bindParam(":role", $role);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Usuário cadastrado com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar usuário. Tente novamente.</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Cadastrar</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_admin" name="is_admin">
            <label class="form-check-label" for="is_admin">Administrador</label>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Cadastrar</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
