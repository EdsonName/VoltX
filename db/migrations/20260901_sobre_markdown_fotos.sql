INSERT INTO configuracoes_site (chave, valor) VALUES
('porque_escolher', '- Equipe de profissionais qualificados\n- Atendimento rápido e eficiente\n- Orçamentos sem compromisso\n- Garantia em todos os serviços'),
('fotos_sobre', '[]')
ON DUPLICATE KEY UPDATE chave = VALUES(chave);
