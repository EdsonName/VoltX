CREATE TABLE eventos_anuncios (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    anuncio_id INT NOT NULL,
    usuario_id INT NULL,
    tipo ENUM('impressao','clique','perfil') NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(anuncio_id) REFERENCES anuncios_profissionais(id) ON DELETE CASCADE,
    FOREIGN KEY(usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    INDEX idx_eventos_anuncio_tipo (anuncio_id,tipo,criado_em)
);

CREATE TABLE visitas_perfis (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    profissional_id INT NOT NULL,
    visitante_id INT NOT NULL,
    visitado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE,
    FOREIGN KEY(visitante_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_visitas_profissional (profissional_id,visitado_em)
);
