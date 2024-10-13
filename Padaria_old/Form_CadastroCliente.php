<?php $title = "Cadastro de Cliente - Padaria"; include 'header.php'; ?>

<div class="container mt-5 container-cadastro">
    <h3 class="text-center title-cadastro">Cadastro de Cliente</h3>
    <form method="POST" action="cadastroCliente.php" class="form-cadastro">
        <div class="form-group form-group-cadastro">
            <label for="nome" class="label-cadastro">Nome:</label>
            <input type="text" class="form-control input-cadastro" id="nome" name="nome" required>
        </div>
        <div class="form-group form-group-cadastro">
            <label for="telefone" class="label-cadastro">Telefone:</label>
            <input type="text" class="form-control input-cadastro" id="telefone" name="telefone" required>
        </div>
        <div class="form-group form-group-cadastro">
            <label for="email" class="label-cadastro">Email:</label>
            <input type="email" class="form-control input-cadastro" id="email" name="email" required>
        </div>
        <div class="form-group form-group-cadastro">
            <label for="cpf" class="label-cadastro">CPF:</label>
            <input type="text" class="form-control input-cadastro" id="cpf" name="cpf" required>
        </div>
        <button type="submit" class="btn btn-success btn-block btn-submit">Cadastrar</button>
    </form>
</div>

<?php include 'footer.php'; ?>
