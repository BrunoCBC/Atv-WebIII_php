<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gnomos - Jornal/Blog</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/Gnomos/public/css/estilo.css">
</head>
<body>

    <!-- Navegação -->
    <nav class="custom-navbar navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand navbar-brand-custom" href="/Gnomos/public/index.php">Segredos Gnomáticos</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/Gnomos/public/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="/Gnomos/app/views/show_categoria.php">Categorias</a>
                    </li>
                    <?php if (!isset($_SESSION['usuario_logado'])): ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="/Gnomos/public/cadastrar.php">Cadastrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="/Gnomos/public/login.php">Login</a>
                        </li>
                    <?php else: ?>
                        <?php if ($_SESSION['role'] === 'administrador'): ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom" href="/Gnomos/app/views/admin/dashboard.php">Painel ADM</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom" href="/Gnomos/app/views/admin/criar_post.php">Cadastrar Post</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-custom" href="/Gnomos/public/sair.php">Sair</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="content-box mt-4 mb-4">