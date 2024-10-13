<!-- includes/header.php -->
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se não houver uma ativa
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Gnomos - Jornal/Blog'; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilo.css"> <!-- Link para o CSS -->
</head>
<body>
<nav class="navbar navbar-expand-lg custom-navbar">
    <a class="navbar-brand navbar-brand-custom" href="index.php">Gnomos</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link nav-link-custom" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-custom" href="#">Sobre</a>
            </li>
            <?php if (!isset($_SESSION['usuario_logado'])): ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="cadastrar.php">Cadastrar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="login.php">Login</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="sair.php">Sair</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<div class="main-content">
