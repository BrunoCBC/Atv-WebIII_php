<!-- public/index.php -->
<?php $title = "Home - Jornal/Blog Gnomos"; include 'includes/header.php'; ?>

<div class="container mt-5 container-home">
    <h1 class="text-center title-home">Bem-vindo ao Jornal/Blog Gnomos!</h1>
    <p class="text-center subtitle-home">Aqui, exploramos o fascinante mundo dos gnomos, compartilhando histórias e lendas.</p>

    <div class="row mt-4 row-home">
        <div class="col-md-4 col-card">
            <div class="card card-home">
                <img src="images/gnome.jpg" class="card-img-top img-card-home" alt="Gnome">
                <div class="card-body">
                    <h5 class="card-title title-card">O Mundo dos Gnomos</h5>
                    <p class="card-text text-card">Descubra as lendas e tradições que cercam estas criaturas mágicas.</p>
                    <a href="sobre.php" class="btn custom-btn">Saiba Mais</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-card">
            <div class="card card-home">
                <img src="images/gnome_story.jpg" class="card-img-top img-card-home" alt="Histórias de Gnomos">
                <div class="card-body">
                    <h5 class="card-title title-card">Histórias Encantadoras</h5>
                    <p class="card-text text-card">Leia histórias fascinantes sobre gnomos e suas aventuras.</p>
                    <a href="posts.php" class="btn custom-btn">Ver Posts</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-card">
            <div class="card card-home">
                <img src="images/gnome_culture.jpg" class="card-img-top img-card-home" alt="Cultura dos Gnomos">
                <div class="card-body">
                    <h5 class="card-title title-card">Cultura Popular</h5>
                    <p class="card-text text-card">Entenda como os gnomos influenciaram a cultura ao longo dos anos.</p>
                    <a href="cultura.php" class="btn custom-btn">Explorar Cultura</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
