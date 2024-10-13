<?php include 'includes/header.php'; ?>

<div class="container index">
    <img class="card-img-top gnome-img img-fluid" src="images/gnome.jpg" alt="Gnome">
    <div>
        <h2 class="title-home">Segredos Gnomáticos</h2>
        <p class="mb-3 text-justify">Bem-vindo ao fascinante universo dos gnomos! Aqui, desbravamos as lendas e mistérios que cercam essas criaturas encantadoras, revelando suas histórias esquecidas e os segredos que habitam os recantos mais profundos da floresta. Este espaço é um santuário para todos os que buscam entender o misticismo e a magia que permeiam o nosso mundo.</p> 
        <p class="mb-3 text-justify">Prepare-se para uma jornada mágica! Junte-se a nós na exploração das aventuras dos gnomos e descubra como eles moldaram a cultura popular ao longo dos séculos. De contos ancestrais a relatos contemporâneos, cada página deste blog traz à luz os encantos e os enigmas que tornam os gnomos tão fascinantes.</p>
    </div>
</div>

<div class="container mt-5 mb-4">
    <h2 class="title-home text-center">Últimos Posts</h2>
    <div class="row justify-content-center">
        <?php
        require_once '../app/config/banco.php';
        require_once '../app/models/Post.php';

        $banco = new Banco();
        $db = $banco->getConexao();

        $post = new Post($db);
        $latestPosts = $post->readLatestPosts(3);
        
        if ($latestPosts) {
            foreach ($latestPosts as $item) {
                echo '<div class="col-md-4 mb-4">';
                echo '    <div class="card card-home">';
                echo '        <img src="/gnomos/public/images/' . htmlspecialchars($item['imagem']) . '" class="card-img-top img-card" alt="' . htmlspecialchars($item['titulo']) . '">';
                echo '        <div class="card-body">';
                echo '            <h5 class="card-title">' . htmlspecialchars($item['titulo']) . '</h5>';
                
                $primeiroParagrafo = '';
                if (preg_match('/(<p.*?>.*?<\/p>)/s', $item['conteudo'], $matches)) {
                    $primeiroParagrafo = $matches[0];

                    $primeiroParagrafo = strip_tags($primeiroParagrafo);
                    if (strlen($primeiroParagrafo) > 100) {
                        $primeiroParagrafo = substr($primeiroParagrafo, 0, 100) . '...';
                    }
                }

                echo '<div class="card-text">' . $primeiroParagrafo . '</div>';
                echo '            <a href="../app/views/show_post.php?id=' . htmlspecialchars($item['id']) . '" class="btn btn-primary">Leia mais</a>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-12 text-center"><p>Nenhum post encontrado.</p></div>'; // Mensagem centralizada
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
