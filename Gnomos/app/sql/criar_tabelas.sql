SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS likes;
DROP TABLE IF EXISTS comentarios;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS categorias;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categorias (nome, descricao) VALUES
('Histórias de Gnomos', 'Contos e lendas sobre gnomos.'),
('Lendas e Mitos', 'Mitos associados a gnomos em diferentes culturas.'),
('Jardinagem com Gnomos', 'Dicas e truques para usar gnomos na jardinagem.'),
('Gnomos na Cultura Popular', 'Como os gnomos são retratados em filmes e livros.'),
('Artesanato de Gnomos', 'Tutoriais e ideias de artesanato de gnomos.'),
('Receitas de Gnomos', 'Receitas mágicas e fantásticas.'),
('Ilustrações de Gnomos', 'Arte e ilustrações relacionadas a gnomos.'),
('Gnomos em Filmes e Livros', 'Explorações de gnomos em mídias populares.'),
('Dicas de Decoração com Gnomos', 'Como decorar com gnomos.'),
('Exploração de Locais Mágicos', 'Locais mágicos onde gnomos são frequentemente mencionados.');

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role ENUM('usuario', 'administrador') DEFAULT 'usuario'
);

CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    autor_id INT,
    categoria_id INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    imagem VARCHAR(255),
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT,
    usuario_id INT,
    comentario TEXT NOT NULL,
    parent_id INT DEFAULT NULL,
    status ENUM('aprovado', 'pendente') DEFAULT 'pendente',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES post(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comentarios(id) ON DELETE CASCADE
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comentario_id INT,
    usuario_id INT,
    FOREIGN KEY (comentario_id) REFERENCES comentarios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE (comentario_id, usuario_id)
);
