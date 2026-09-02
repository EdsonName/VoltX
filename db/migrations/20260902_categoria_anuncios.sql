ALTER TABLE anuncios_profissionais ADD COLUMN categoria VARCHAR(100) NOT NULL DEFAULT 'Geral' AFTER profissional_id;
