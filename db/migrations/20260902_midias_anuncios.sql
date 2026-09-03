ALTER TABLE anuncios_profissionais
    ADD COLUMN midias_json LONGTEXT NULL AFTER imagem_url,
    ADD COLUMN video_url VARCHAR(1000) NULL AFTER midias_json;

UPDATE anuncios_profissionais
SET midias_json=JSON_ARRAY(imagem_url)
WHERE imagem_url IS NOT NULL AND imagem_url<>'' AND midias_json IS NULL;
