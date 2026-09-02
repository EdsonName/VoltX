CREATE TABLE IF NOT EXISTS configuracoes_site (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT NOT NULL,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO configuracoes_site (chave, valor) VALUES
('email_contato', 'contato@voltx.com'),
('telefone_contato', '(11) 9999-9999'),
('whatsapp', '5561981044986'),
('horario_atendimento', 'Seg–Sex, 8h às 18h'),
('regiao_atendimento', 'Brasília e Entorno Sul — Valparaíso, Luziânia, Novo Gama e região'),
('responsavel', 'Edson'),
('experiencia_anos', '10'),
('texto_sobre', 'A VoltX é uma empresa especializada em soluções de eletricidade, segurança e atendimento profissional.'),
('missao', 'Oferecer serviços elétricos de qualidade, com segurança e profissionalismo.')
ON DUPLICATE KEY UPDATE chave = VALUES(chave);
