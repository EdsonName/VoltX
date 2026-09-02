ALTER TABLE usuarios
    ADD COLUMN ultima_atividade DATETIME NULL AFTER tipo,
    ADD COLUMN ultimo_logout DATETIME NULL AFTER ultima_atividade,
    ADD INDEX idx_usuario_presenca (ultima_atividade);

ALTER TABLE anuncios_profissionais
    ADD COLUMN codigo VARCHAR(24) NULL AFTER id,
    ADD UNIQUE KEY uq_anuncio_codigo (codigo);

UPDATE anuncios_profissionais SET codigo=CONCAT('VXAD-',LPAD(id,8,'0')) WHERE codigo IS NULL;
UPDATE anuncios_profissionais SET status='aprovado' WHERE status='pendente';
