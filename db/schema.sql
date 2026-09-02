# VoltX Database Schema

## Tabela: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo ENUM('cliente', 'admin') DEFAULT 'cliente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

## Tabela: servicos
CREATE TABLE servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao LONGTEXT,
    preco DECIMAL(10, 2) NOT NULL,
    duracao_minutos INT,
    imagem_url VARCHAR(1000),
    selo VARCHAR(80),
    beneficios TEXT,
    destaque_emergencia BOOLEAN DEFAULT FALSE,
    pausado BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

## Tabela: agendamentos
CREATE TABLE agendamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    servico_id INT NOT NULL,
    data_horario DATETIME NOT NULL,
    status ENUM('pendente', 'confirmado', 'cancelado', 'realizado') DEFAULT 'pendente',
    observacoes TEXT,
    cep VARCHAR(10),
    bairro_cidade VARCHAR(255),
    endereco VARCHAR(500),
    localizacao_gps VARCHAR(500),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (servico_id) REFERENCES servicos(id)
);

## Tabela: orcamentos
CREATE TABLE orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao LONGTEXT,
    valor_estimado DECIMAL(10, 2),
    status ENUM('pendente', 'aprovado', 'rejeitado', 'completo') DEFAULT 'pendente',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

## Tabela: posts_blog
CREATE TABLE posts_blog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    conteudo LONGTEXT NOT NULL,
    categoria VARCHAR(100),
    autor_id INT,
    publicado BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES usuarios(id)
);

-- Administrador inicial de desenvolvimento.
-- Login: admin@volx.com | Senha temporária: Admin@123
-- Troque a senha antes de publicar o sistema.
INSERT INTO usuarios (nome, email, senha, telefone, tipo)
VALUES ('Administrador VoltX', 'admin@volx.com', '$2y$10$gs/E/5Xvt9Ydeble4WY9DeOuTXh8DkblZhCtWvmKh2xU1P3VWY4bO', '(11) 9999-9999', 'admin')
ON DUPLICATE KEY UPDATE tipo = 'admin';
