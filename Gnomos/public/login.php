<!-- public/login.php -->
<?php
session_start();
include 'includes/header.php';
include '../app/config/Banco.php';

$db = new Banco();
$conexao = $db->getConexao();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_logado'] = $usuario['id'];
            $_SESSION['role'] = $usuario['role'];
            echo "<div class='alert alert-success'>Login realizado com sucesso!</div>";
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Senha incorreta.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Usuário não encontrado.</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
