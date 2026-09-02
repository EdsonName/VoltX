<?php
$titulo_pagina = 'Serviços';
$estilos_pagina = ['/assets/css/servicos.css?v=8'];
$scripts_pagina = ['/assets/js/servicos.js?v=5'];
require_once __DIR__ . '/includes/functions.php';
$servicos = pegar_servicos();
$ofertas_profissionais = [];
$resultado_ofertas = $mysqli->query("SELECT o.*,COALESCE(NULLIF(o.imagem_url,''),'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=700&q=82') AS imagem_url,p.id AS perfil_id,p.marca,p.mei,u.nome AS profissional_nome FROM ofertas_profissionais o JOIN profissionais p ON p.id=o.profissional_id AND p.ativo=1 JOIN usuarios u ON u.id=p.usuario_id WHERE o.ativo=1 ORDER BY RAND() LIMIT 12");
if ($resultado_ofertas) $ofertas_profissionais = $resultado_ofertas->fetch_all(MYSQLI_ASSOC);
$servicos_disponiveis = array_values(array_filter($servicos, fn($servico) => empty($servico['pausado'])));
$whatsapp = preg_replace('/\D/', '', config_site('whatsapp', '5561981044986'));
if ($whatsapp && !str_starts_with($whatsapp, '55')) $whatsapp = '55' . $whatsapp;
$responsavel = config_site('responsavel', 'Equipe VoltX');
$imagens = [
    'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=700&q=82',
    'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=700&q=82',
    'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=700&q=82',
    'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?auto=format&fit=crop&w=700&q=82',
    'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=700&q=82',
    'https://images.unsplash.com/photo-1621905252507-b35492cc74b4?auto=format&fit=crop&w=700&q=82',
];
require_once __DIR__ . '/includes/header.php';
?>
<section class="services-page"><div class="container">
    <header class="services-heading"><span class="eyebrow">Soluções VoltX</span><h1>Nossos Serviços</h1><p>Atendimento em <?php echo sanitizar(config_site('regiao_atendimento', 'Brasília e Entorno')); ?>.</p></header>
    <?php if ($ofertas_profissionais): ?><section class="professional-offers"><div class="marketplace-section-title"><span class="eyebrow">Recomendados para você</span><h2>Serviços da comunidade VoltX</h2><p>A ordem é renovada a cada visita para dar espaço a diferentes profissionais.</p></div><div class="services-catalog">
    <?php foreach($ofertas_profissionais as $oferta): $identidade=$oferta['marca']?:$oferta['profissional_nome']; ?>
        <article class="catalog-card professional-offer-card"><div class="catalog-image"><img src="<?php echo sanitizar($oferta['imagem_url']); ?>" alt="<?php echo sanitizar($oferta['nome']); ?>" loading="lazy"><span class="catalog-badge"><?php echo sanitizar($oferta['categoria']); ?></span></div><div class="catalog-body"><div><div class="provider-line"><span><?php echo sanitizar($identidade); ?><?php echo $oferta['mei']?' (MEI)':''; ?></span><strong>★ <?php echo number_format((float)$oferta['nota_media'],1,',','.'); ?>/10</strong></div><h2><?php echo sanitizar($oferta['nome']); ?></h2><p><?php echo sanitizar($oferta['descricao']); ?></p></div><div class="catalog-actions"><div class="catalog-price">R$ <?php echo number_format((float)$oferta['preco_inicial'],2,',','.'); ?> <small><?php echo sanitizar($oferta['unidade_preco']); ?></small></div><a class="details-link" href="/profissional.php?id=<?php echo (int)$oferta['perfil_id']; ?>">Ver profissional →</a></div></div></article>
    <?php endforeach; ?></div></section><?php endif; ?>
    <?php if($servicos): ?><div class="marketplace-section-title institutional-title"><span class="eyebrow">Serviços VoltX</span><h2>Soluções institucionais</h2></div><?php endif; ?>
    <?php if ($servicos): ?><div class="services-catalog">
    <?php foreach ($servicos as $indice => $servico):
        $emergencia = !empty($servico['destaque_emergencia']) || stripos($servico['nome'], 'emerg') !== false || stripos($servico['nome'], '24h') !== false;
        $pausado = !empty($servico['pausado']);
        $imagem = !empty($servico['imagem_url']) ? $servico['imagem_url'] : $imagens[$indice % count($imagens)];
        $beneficios = array_values(array_filter(array_map('trim', preg_split('/\R/', $servico['beneficios'] ?? ''))));
        $mensagem = 'Olá ' . $responsavel . '! Vi no site VoltX e gostaria de um orçamento para: ' . $servico['nome'] . '.';
    ?>
        <div class="service-flip-card <?php echo $pausado ? 'is-paused' : ''; ?>" data-service-id="<?php echo (int)$servico['id']; ?>" style="--delay:<?php echo min($indice,8)*70; ?>ms">
            <div class="service-flip-inner">
                <article class="catalog-card service-card-front <?php echo $emergencia ? 'is-emergency' : ''; ?> <?php echo $pausado ? 'is-paused' : ''; ?>" <?php echo $pausado ? 'aria-disabled="true"' : ''; ?>>
                    <div class="catalog-image"><img src="<?php echo sanitizar($imagem); ?>" alt="Serviço de <?php echo sanitizar($servico['nome']); ?>" loading="lazy"><span class="catalog-badge"><?php echo $pausado ? 'Temporariamente pausado' : sanitizar($servico['selo'] ?: ($emergencia ? 'Atendimento urgente' : 'Profissional')); ?></span></div>
                    <div class="catalog-body"><div><h2><span class="service-icon">⚡</span><?php echo sanitizar($servico['nome']); ?></h2><div class="price-row"><div class="catalog-price">A partir de R$ <?php echo number_format($servico['preco'],2,',','.'); ?></div><span class="estimated-price-badge" title="Este é um valor médio. O preço final pode variar conforme a avaliação do serviço."><i></i> Valor estimado</span></div><p><?php echo sanitizar($servico['descricao']); ?></p><ul class="service-facts"><?php if ($beneficios): foreach (array_slice($beneficios,0,3) as $beneficio): ?><li><span>✓</span> <?php echo sanitizar($beneficio); ?></li><?php endforeach; else: ?><li><span>✓</span> Duração estimada: <?php echo (int)$servico['duracao_minutos']; ?> minutos</li><li><span>✓</span> Atendimento com segurança e garantia</li><?php endif; ?></ul></div>
                        <div class="catalog-actions"><?php if ($pausado): ?><span class="paused-message">Serviço indisponível no momento</span><?php else: ?><a class="whatsapp-button" href="https://wa.me/<?php echo $whatsapp; ?>?text=<?php echo rawurlencode($mensagem); ?>" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">◉</span> Pedir no WhatsApp</a><button class="details-link flip-trigger" type="button" aria-expanded="false">Ver detalhes ↻</button><?php endif; ?></div>
                    </div>
                </article>
                <article class="catalog-card service-card-back" aria-hidden="true">
                    <div class="card-back-header"><button class="flip-back" type="button" aria-label="Voltar para a frente do card">← Voltar</button><span>Detalhes</span></div>
                    <div class="card-back-content"><span class="eyebrow">Serviço VoltX</span><h2><?php echo sanitizar($servico['nome']); ?></h2><p><?php echo nl2br(sanitizar($servico['descricao'])); ?></p><div class="detail-metrics"><div><small>Valor médio</small><strong>R$ <?php echo number_format($servico['preco'],2,',','.'); ?></strong></div><div><small>Duração estimada</small><strong><?php echo (int)$servico['duracao_minutos']; ?> min</strong></div></div><h3>O que está incluído</h3><ul class="service-facts"><?php if ($beneficios): foreach ($beneficios as $beneficio): ?><li><span>✓</span><?php echo sanitizar($beneficio); ?></li><?php endforeach; else: ?><li><span>✓</span>Atendimento profissional</li><li><span>✓</span>Serviço com garantia</li><?php endif; ?></ul></div>
                    <div class="card-back-actions"><a class="whatsapp-button" href="https://wa.me/<?php echo $whatsapp; ?>?text=<?php echo rawurlencode($mensagem); ?>" target="_blank" rel="noopener noreferrer">◉ Pedir orçamento</a><?php if (usuario_autenticado()): ?><a class="schedule-link" href="/agendar.php?servico_id=<?php echo (int)$servico['id']; ?>">Agendar serviço</a><?php else: $destino_agendamento = '/agendar.php?servico_id=' . (int)$servico['id']; ?><a class="schedule-link" href="/cadastro.php?redirect=<?php echo rawurlencode($destino_agendamento); ?>">Criar conta para agendar</a><a class="login-schedule" href="/login.php?redirect=<?php echo rawurlencode($destino_agendamento); ?>">Já tenho conta</a><?php endif; ?></div>
                </article>
            </div>
        </div>
    <?php endforeach; ?></div>
    <?php else: ?><div class="services-empty"><span>⚡</span><h2>Novos serviços em breve</h2><p>Fale conosco para verificar como podemos ajudar.</p><a href="/contato.php" class="btn">Entrar em contato</a></div><?php endif; ?>

    <div class="service-tools">
        <section class="tool-card"><div class="tool-title"><span>⌁</span><div><h2>Estimador rápido</h2><p>Envie os dados iniciais e receba uma orientação.</p></div></div><div class="tool-grid"><div class="form-group"><label for="calc-cidade">Sua região</label><select id="calc-cidade"><option><?php echo sanitizar(config_site('regiao_atendimento', 'DF e Entorno')); ?></option><option>Outra região</option></select></div><div class="form-group"><label for="calc-servico">Serviço desejado</label><select id="calc-servico"><?php foreach ($servicos_disponiveis as $servico): ?><option><?php echo sanitizar($servico['nome']); ?></option><?php endforeach; ?><option>Outro serviço</option></select></div></div><button type="button" class="whatsapp-button" data-whatsapp-estimate data-phone="<?php echo $whatsapp; ?>" data-contact-name="<?php echo sanitizar($responsavel); ?>">◉ Simular pelo WhatsApp</button></section>
        <section class="tool-card emergency-tool"><div class="tool-title"><span>!</span><div><h2>Diagnóstico de urgência</h2><p>Identifique rapidamente um possível risco elétrico.</p></div></div><div class="form-group"><label for="diag-sintoma">Qual é o sintoma?</label><select id="diag-sintoma"><option>Disjuntor desarmando sem parar</option><option>Cheiro de fumaça ou queimado</option><option>Parte do imóvel sem energia</option><option>Tomada faiscando ou esquentando</option></select></div><button type="button" class="urgent-button" data-whatsapp-urgent data-phone="<?php echo $whatsapp; ?>" data-contact-name="<?php echo sanitizar($responsavel); ?>">⚠ Enviar alerta urgente</button></section>
    </div>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
