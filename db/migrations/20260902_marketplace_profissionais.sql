ALTER TABLE usuarios MODIFY tipo ENUM('cliente','profissional','admin') NOT NULL DEFAULT 'cliente';

CREATE TABLE IF NOT EXISTS profissionais (
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

CREATE TABLE IF NOT EXISTS ofertas_profissionais (
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
