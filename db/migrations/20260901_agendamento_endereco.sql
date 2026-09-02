ALTER TABLE agendamentos
    ADD COLUMN cep VARCHAR(10) NULL AFTER observacoes,
    ADD COLUMN bairro_cidade VARCHAR(255) NULL AFTER cep,
    ADD COLUMN endereco VARCHAR(500) NULL AFTER bairro_cidade,
    ADD COLUMN localizacao_gps VARCHAR(500) NULL AFTER endereco;
