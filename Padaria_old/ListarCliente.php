<?php $title = "Lista de Clientes - Padaria"; include 'header.php'; ?>

<div class="container mt-5 container-lista-clientes">
    <h2 class="text-center title-lista">Lista de Clientes</h2>
    <table class="table table-bordered table-striped table-clientes">
        <thead class="thead thead-cor">
            <tr class="header-table">
                <th>ID</th>
                <th>Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody class="tbody-radius">
            <?php
            require 'Banco.php';
            require 'Cliente.php';

            $banco = new Banco();
            $db = $banco->getConexao();
            $cliente = new Cliente($db);
            $stmt = $cliente->read();
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($clientes as $cliente) { ?>
                <tr class="row-cliente">
                    <td><?php echo $cliente['id']; ?></td>
                    <td><?php echo $cliente['nome']; ?></td>
                    <td><?php echo $cliente['telefone']; ?></td>
                    <td><?php echo $cliente['email']; ?></td>
                    <td><?php echo $cliente['cpf']; ?></td>
                    <td class="acoes-cliente">
                        <a href="form_atualizaCliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-warning btn-sm btn-editar">Editar</a>
                        <a href="deletaCliente.php?id=<?php echo $cliente['id']; ?>" class="btn btn-danger btn-sm btn-excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <a href="form_cadastroCliente.php" class="btn btn-primary btn-cadastrar">Cadastrar Novo Cliente</a>
</div>

<?php include 'footer.php'; ?>
