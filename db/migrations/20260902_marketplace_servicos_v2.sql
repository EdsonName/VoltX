ALTER TABLE profissionais
    ADD COLUMN marca VARCHAR(180) NULL AFTER bio,
    ADD COLUMN mei BOOLEAN NOT NULL DEFAULT FALSE AFTER marca,
    ADD COLUMN cnpj VARCHAR(14) NULL AFTER mei,
    ADD UNIQUE KEY uq_profissional_marca (marca),
    ADD UNIQUE KEY uq_profissional_cnpj (cnpj);

CREATE TABLE categorias_profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profissional_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_categoria_profissional (profissional_id, nome),
    FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE
);

INSERT IGNORE INTO categorias_profissionais (profissional_id, nome)
SELECT profissional_id, categoria FROM ofertas_profissionais WHERE TRIM(categoria) <> '';

ALTER TABLE ofertas_profissionais
    ADD COLUMN categoria_id INT NULL AFTER categoria,
    ADD COLUMN imagem_url VARCHAR(1000) NULL AFTER descricao,
    ADD COLUMN nota_media DECIMAL(3,1) NOT NULL DEFAULT 10.0 AFTER unidade_preco,
    ADD COLUMN total_avaliacoes INT NOT NULL DEFAULT 0 AFTER nota_media,
    ADD CONSTRAINT fk_oferta_categoria FOREIGN KEY (categoria_id) REFERENCES categorias_profissionais(id) ON DELETE SET NULL;

UPDATE ofertas_profissionais o
JOIN categorias_profissionais c ON c.profissional_id=o.profissional_id AND c.nome=o.categoria
SET o.categoria_id=c.id;

CREATE TABLE avaliacoes_servicos_profissionais (
    id INT AUTO_INCREMENT PRIMARY KEY,
    oferta_id INT NOT NULL,
    cliente_id INT NOT NULL,
    nota DECIMAL(3,1) NOT NULL,
    comentario TEXT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_avaliacao_servico_cliente (oferta_id, cliente_id),
    FOREIGN KEY (oferta_id) REFERENCES ofertas_profissionais(id) ON DELETE CASCADE,
    FOREIGN KEY (cliente_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    CHECK (nota >= 0 AND nota <= 10)
);
