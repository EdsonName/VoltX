ALTER TABLE ofertas_profissionais
    ADD COLUMN subcategoria VARCHAR(120) NULL AFTER categoria_id,
    ADD COLUMN publico ENUM('residencial','comercial','industrial','geral') NOT NULL DEFAULT 'geral' AFTER subcategoria,
    ADD INDEX idx_ofertas_publico (publico, ativo);

UPDATE categorias_profissionais
SET nome=CONCAT(UPPER(LEFT(TRIM(nome),1)),LOWER(SUBSTRING(TRIM(nome),2)));

UPDATE ofertas_profissionais o
JOIN categorias_profissionais c ON c.id=o.categoria_id
SET o.categoria=c.nome;
