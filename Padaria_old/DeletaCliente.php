<?php
    require 'Banco.php';
    require 'Cliente.php';

    $database = new Banco();
    $db = $database->getConexao();

    $cliente = new Cliente($db);

    $cliente->setId($_GET['id']);

    if ($cliente->delete()) {
        echo "Cliente deletado com sucesso!";
        header("Refresh:3;url=listarCliente.php");
    } else {
        echo "Erro ao deletar o cliente.";
    }
?>
