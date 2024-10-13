<?php 
require 'Banco.php';
require 'Cliente.php';

$banco = new Banco();
$conexao = $banco->getConexao();
$cliente = new Cliente($conexao);

$cliente->setId($_GET['id']);
$stmt = $cliente->consultar();
$clienteSelecionado = $stmt->fetch(PDO::FETCH_ASSOC); // Apenas para uma linha
?>

<?php $title = "Atualização de Cliente - Padaria"; include 'header.php'; ?>

<div class="container mt-5 container-atualizacao">
    <h3 class="text-center title-atualizacao">Atualização de Cliente</h3>
    <form method="POST" action="atualizaCliente.php" class="form-atualizacao">
        <input type="hidden" name="id" value="<?php echo $clienteSelecionado['id']; ?>">
        <div class="form-group form-group-atualizacao">
            <label for="nome" class="label-atualizacao">Nome:</label>
            <input type="text" class="form-control input-atualizacao" id="nome" name="nome" value="<?php echo htmlspecialchars($clienteSelecionado['nome']); ?>" required>
        </div>
        <div class="form-group form-group-atualizacao">
            <label for="telefone" class="label-atualizacao">Telefone:</label>
            <input type="text" class="form-control input-atualizacao" id="telefone" name="telefone" value="<?php echo htmlspecialchars($clienteSelecionado['telefone']); ?>" required>
        </div>
        <div class="form-group form-group-atualizacao">
            <label for="email" class="label-atualizacao">Email:</label>
            <input type="email" class="form-control input-atualizacao" id="email" name="email" value="<?php echo htmlspecialchars($clienteSelecionado['email']); ?>" required>
        </div>
        <div class="form-group form-group-atualizacao">
            <label for="cpf" class="label-atualizacao">CPF:</label>
            <input type="text" class="form-control input-atualizacao" id="cpf" name="cpf" value="<?php echo htmlspecialchars($clienteSelecionado['cpf']); ?>" required>
        </div>
        <button type="submit" class="btn btn-success btn-block btn-atualizar">Atualizar</button>
    </form>
</div>

<?php include 'footer.php'; ?>
