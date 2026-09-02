ALTER TABLE servicos
    ADD COLUMN imagem_url VARCHAR(1000) NULL AFTER duracao_minutos,
    ADD COLUMN selo VARCHAR(80) NULL AFTER imagem_url,
    ADD COLUMN beneficios TEXT NULL AFTER selo,
    ADD COLUMN destaque_emergencia BOOLEAN NOT NULL DEFAULT FALSE AFTER beneficios;
