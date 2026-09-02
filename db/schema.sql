# VoltX Database Schema

## Tabela: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    tipo ENUM('cliente', 'profissional', 'admin') DEFAULT 'cliente',
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
    telefone_whatsapp BOOLEAN DEFAULT FALSE,
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

## Tabela: profissionais
CREATE TABLE profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNIQUE NOT NULL,
    titulo_profissional VARCHAR(160) NOT NULL,
    bio TEXT,
    categoria_principal VARCHAR(100) NOT NULL,
    cidade VARCHAR(120) NOT NULL,
    uf CHAR(2) NOT NULL,
    foto_url VARCHAR(1000),
    atende_online BOOLEAN DEFAULT FALSE,
    verificado BOOLEAN DEFAULT FALSE,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_profissionais_local (cidade, uf, ativo)
);

## Tabela: ofertas_profissionais
CREATE TABLE ofertas_profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profissional_id INT NOT NULL,
    nome VARCHAR(180) NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    preco_inicial DECIMAL(10,2),
    unidade_preco VARCHAR(60) DEFAULT 'por serviço',
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE,
    INDEX idx_ofertas_busca (categoria, ativo)
);

## Tabelas: chat
CREATE TABLE conversas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    profissional_id INT NOT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY conversa_unica (cliente_id, profissional_id),
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE
);

CREATE TABLE mensagens (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    conversa_id INT NOT NULL,
    remetente_id INT NOT NULL,
    mensagem TEXT NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversa_id) REFERENCES conversas(id) ON DELETE CASCADE,
    FOREIGN KEY (remetente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_mensagens_conversa (conversa_id, id)
);

## Tabelas: feed e reputação
CREATE TABLE publicacoes_profissionais (id INT AUTO_INCREMENT PRIMARY KEY, profissional_id INT NOT NULL, conteudo TEXT NOT NULL, imagem_url VARCHAR(1000), ativo BOOLEAN DEFAULT TRUE, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE);
CREATE TABLE curtidas_publicacoes (publicacao_id INT NOT NULL, usuario_id INT NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(publicacao_id,usuario_id), FOREIGN KEY(publicacao_id) REFERENCES publicacoes_profissionais(id) ON DELETE CASCADE, FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE comentarios_publicacoes (id INT AUTO_INCREMENT PRIMARY KEY, publicacao_id INT NOT NULL, usuario_id INT NOT NULL, comentario VARCHAR(1000) NOT NULL, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(publicacao_id) REFERENCES publicacoes_profissionais(id) ON DELETE CASCADE, FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE avaliacoes_profissionais (id INT AUTO_INCREMENT PRIMARY KEY, profissional_id INT NOT NULL, cliente_id INT NOT NULL, nota TINYINT NOT NULL, comentario VARCHAR(1000), criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, UNIQUE KEY avaliacao_unica(profissional_id,cliente_id), FOREIGN KEY(profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE, FOREIGN KEY(cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE);
CREATE TABLE anuncios_profissionais (id INT AUTO_INCREMENT PRIMARY KEY, profissional_id INT NOT NULL, titulo VARCHAR(180) NOT NULL, texto VARCHAR(500), imagem_url VARCHAR(1000), link_url VARCHAR(1000), status ENUM('rascunho','pendente','aprovado','rejeitado','encerrado') DEFAULT 'pendente', inicio_em DATETIME, fim_em DATETIME, criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY(profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE);

## Tabela: configuracoes_site
CREATE TABLE configuracoes_site (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT NOT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO configuracoes_site (chave, valor) VALUES
('email_contato', 'contato@voltx.com'),
('telefone_contato', '(11) 9999-9999'),
('whatsapp', '5561981044986'),
('horario_atendimento', 'Seg–Sex, 8h às 18h'),
('regiao_atendimento', 'Brasília e Entorno Sul — Valparaíso, Luziânia, Novo Gama e região'),
('responsavel', 'Edson'),
('experiencia_anos', '10'),
('texto_sobre', 'A VoltX é uma empresa especializada em soluções de eletricidade, segurança e atendimento profissional.'),
('missao', 'Oferecer serviços elétricos de qualidade, com segurança e profissionalismo.'),
('porque_escolher', '- Equipe de profissionais qualificados\n- Atendimento rápido e eficiente\n- Orçamentos sem compromisso\n- Garantia em todos os serviços'),
('fotos_sobre', '[]');

-- Administrador inicial de desenvolvimento.
-- Login: admin@volx.com | Senha temporária: Admin@123
-- Troque a senha antes de publicar o sistema.
INSERT INTO usuarios (nome, email, senha, telefone, tipo)
VALUES ('Administrador VoltX', 'admin@volx.com', '$2y$10$gs/E/5Xvt9Ydeble4WY9DeOuTXh8DkblZhCtWvmKh2xU1P3VWY4bO', '(11) 9999-9999', 'admin')
ON DUPLICATE KEY UPDATE tipo = 'admin';
