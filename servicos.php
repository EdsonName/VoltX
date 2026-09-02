<?php
$titulo_pagina = 'Serviços';
$estilos_pagina = ['/assets/css/servicos.css?v=2'];
$scripts_pagina = ['/assets/js/servicos.js'];
require_once __DIR__ . '/includes/functions.php';
$servicos = pegar_servicos();
$whatsapp = '5561981044986';
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
    <header class="services-heading"><span class="eyebrow">Soluções VoltX</span><h1>Nossos Serviços</h1><p>Atendimento rápido em Brasília e Entorno Sul — Valparaíso, Luziânia, Novo Gama e região.</p></header>
    <?php if ($servicos): ?><div class="services-catalog">
    <?php foreach ($servicos as $indice => $servico):
        $emergencia = !empty($servico['destaque_emergencia']) || stripos($servico['nome'], 'emerg') !== false || stripos($servico['nome'], '24h') !== false;
        $imagem = !empty($servico['imagem_url']) ? $servico['imagem_url'] : $imagens[$indice % count($imagens)];
        $beneficios = array_values(array_filter(array_map('trim', preg_split('/\R/', $servico['beneficios'] ?? ''))));
        $mensagem = 'Olá Edson! Vi no site VoltX e gostaria de um orçamento para: ' . $servico['nome'] . '.';
    ?>
        <article class="catalog-card <?php echo $emergencia ? 'is-emergency' : ''; ?>" style="--delay:<?php echo min($indice,8)*70; ?>ms">
            <div class="catalog-image"><img src="<?php echo sanitizar($imagem); ?>" alt="Serviço de <?php echo sanitizar($servico['nome']); ?>" loading="lazy"><span class="catalog-badge"><?php echo sanitizar($servico['selo'] ?: ($emergencia ? 'Atendimento urgente' : 'Profissional')); ?></span></div>
            <div class="catalog-body"><div><h2><span class="service-icon">⚡</span><?php echo sanitizar($servico['nome']); ?></h2><div class="catalog-price">A partir de R$ <?php echo number_format($servico['preco'],2,',','.'); ?></div><p><?php echo sanitizar($servico['descricao']); ?></p><ul class="service-facts"><?php if ($beneficios): foreach (array_slice($beneficios,0,3) as $beneficio): ?><li><span>✓</span> <?php echo sanitizar($beneficio); ?></li><?php endforeach; else: ?><li><span>✓</span> Duração estimada: <?php echo (int)$servico['duracao_minutos']; ?> minutos</li><li><span>✓</span> Atendimento com segurança e garantia</li><?php endif; ?></ul></div>
                <div class="catalog-actions"><a class="whatsapp-button" href="https://wa.me/<?php echo $whatsapp; ?>?text=<?php echo rawurlencode($mensagem); ?>" target="_blank" rel="noopener noreferrer"><span aria-hidden="true">◉</span> Pedir no WhatsApp</a><a class="details-link" href="/servico-detalhes.php?id=<?php echo (int)$servico['id']; ?>">Ver detalhes →</a></div>
            </div>
        </article>
    <?php endforeach; ?></div>
    <?php else: ?><div class="services-empty"><span>⚡</span><h2>Novos serviços em breve</h2><p>Fale conosco para verificar como podemos ajudar.</p><a href="/contato.php" class="btn">Entrar em contato</a></div><?php endif; ?>

    <div class="service-tools">
        <section class="tool-card"><div class="tool-title"><span>⌁</span><div><h2>Estimador rápido</h2><p>Envie os dados iniciais e receba uma orientação.</p></div></div><div class="tool-grid"><div class="form-group"><label for="calc-cidade">Sua região</label><select id="calc-cidade"><option>Valparaíso de Goiás</option><option>Luziânia / Ocidental / Novo Gama</option><option>Brasília / Plano Piloto</option><option>Ceilândia / Taguatinga / Samambaia</option><option>Outra região DF/GO</option></select></div><div class="form-group"><label for="calc-servico">Serviço desejado</label><select id="calc-servico"><?php foreach ($servicos as $servico): ?><option><?php echo sanitizar($servico['nome']); ?></option><?php endforeach; ?><option>Outro serviço</option></select></div></div><button type="button" class="whatsapp-button" data-whatsapp-estimate data-phone="<?php echo $whatsapp; ?>">◉ Simular pelo WhatsApp</button></section>
        <section class="tool-card emergency-tool"><div class="tool-title"><span>!</span><div><h2>Diagnóstico de urgência</h2><p>Identifique rapidamente um possível risco elétrico.</p></div></div><div class="form-group"><label for="diag-sintoma">Qual é o sintoma?</label><select id="diag-sintoma"><option>Disjuntor desarmando sem parar</option><option>Cheiro de fumaça ou queimado</option><option>Parte do imóvel sem energia</option><option>Tomada faiscando ou esquentando</option></select></div><button type="button" class="urgent-button" data-whatsapp-urgent data-phone="<?php echo $whatsapp; ?>">⚠ Enviar alerta urgente</button></section>
    </div>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
