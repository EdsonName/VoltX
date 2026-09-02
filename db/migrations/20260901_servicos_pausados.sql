ALTER TABLE servicos
    ADD COLUMN pausado BOOLEAN NOT NULL DEFAULT FALSE AFTER destaque_emergencia;
