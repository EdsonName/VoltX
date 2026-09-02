<?php
$titulo_pagina = 'Home';
$estilos_pagina = ['/assets/css/home.css?v=1'];
require_once __DIR__ . '/includes/functions.php';
$servicos_home = array_values(array_filter(pegar_servicos(), fn($servico) => empty($servico['pausado'])));
$total_profissionais = (int)($mysqli->query('SELECT COUNT(*) total FROM profissionais WHERE ativo=1')->fetch_assoc()['total'] ?? 0);
$whatsapp = preg_replace('/\D/', '', config_site('whatsapp', '5561981044986'));
if ($whatsapp && !str_starts_with($whatsapp, '55')) $whatsapp = '55' . $whatsapp;
$responsavel = config_site('responsavel', 'Equipe VoltX');
$regiao = config_site('regiao_atendimento', 'Distrito Federal e Entorno');
$horario = config_site('horario_atendimento', 'Seg–Sex, 8h às 18h');
$mensagem_whatsapp = rawurlencode('Olá ' . $responsavel . '! Vim pelo site da VoltX e gostaria de falar sobre um serviço elétrico.');
require_once __DIR__ . '/includes/header.php';
?>
<div class="container home-page">
    <section class="home-hero">
        <div class="home-hero-copy"><span class="home-badge"><b>⚡</b> Profissionais e serviços em um só lugar</span><h1>Encontre profissionais.<br><span>Resolva com confiança.</span></h1><p>Busque por especialidade e localização, compare perfis e fale diretamente com quem pode realizar seu serviço.</p><div class="home-actions"><a class="home-whatsapp" href="/profissionais.php"><span>⌕</span> Buscar profissionais</a><a class="home-outline" href="/cadastro.php?tipo=profissional"><span>＋</span> Sou profissional</a></div></div>
        <aside class="home-duty-card"><div class="home-duty-status"><i></i> Novos profissionais podem se cadastrar</div><div class="home-duty-icon">⚡</div><h2>Ofereça seus serviços</h2><p>Crie um perfil público, informe sua região e publique suas próprias ofertas.</p><div class="home-duty-hours"><span>⌖</span><div><small>Área inicial da plataforma</small><strong><?php echo sanitizar($regiao); ?></strong></div></div></aside>
    </section>
    <section class="home-stats" aria-label="Diferenciais da VoltX"><div><strong><?php echo $total_profissionais; ?></strong><span>Profissionais publicados</span></div><div><strong><?php echo count($servicos_home); ?></strong><span>Soluções VoltX</span></div><div><strong>Busca</strong><span>Por categoria e cidade</span></div><div><strong>Direto</strong><span>Contato com o profissional</span></div></section>
    <section class="home-services">
        <header class="home-section-heading"><div><span>NOSSAS ESPECIALIDADES</span><h2>Nossas soluções elétricas</h2></div><p>Serviços planejados e executados com transparência e valores médios claros.</p></header>
        <?php if ($servicos_home): ?><div class="home-services-grid"><?php foreach (array_slice($servicos_home, 0, 6) as $servico): $descricao=trim($servico['descricao'] ?? ''); $destino='/agendar.php?servico_id='.(int)$servico['id']; $link_agendar=usuario_autenticado() ? $destino : '/cadastro.php?redirect='.rawurlencode($destino); ?><article class="home-service-card"><div><span class="home-service-icon">⚡</span><h3><?php echo sanitizar($servico['nome']); ?></h3><p><?php echo sanitizar(mb_strimwidth($descricao,0,145,'…')); ?></p><strong class="home-service-price">A partir de R$ <?php echo number_format($servico['preco'],2,',','.'); ?></strong></div><a href="<?php echo sanitizar($link_agendar); ?>">Agendar agora <span>→</span></a></article><?php endforeach; ?></div><a class="home-all-services" href="/servicos.php">Ver todos os serviços <span>→</span></a><?php else: ?><div class="home-empty"><span>⚡</span><h3>Novos serviços em breve</h3><p>Enquanto isso, fale diretamente com nossa equipe.</p></div><?php endif; ?>
    </section>
    <section class="home-callout"><div><span>ATENDIMENTO DIRETO</span><h2>Precisa de um orçamento personalizado?</h2><p>Envie uma foto ou vídeo do problema pelo WhatsApp e receba uma orientação inicial, sem compromisso.</p></div><a class="home-whatsapp" href="https://wa.me/<?php echo $whatsapp; ?>?text=<?php echo $mensagem_whatsapp; ?>" target="_blank" rel="noopener noreferrer"><span>◉</span> Falar com a VoltX</a></section>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
