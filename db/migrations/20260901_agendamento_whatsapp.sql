ALTER TABLE agendamentos
    ADD COLUMN telefone_whatsapp BOOLEAN NOT NULL DEFAULT FALSE AFTER localizacao_gps;
