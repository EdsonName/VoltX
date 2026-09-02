<?php
$titulo_pagina = 'Sobre';
$estilos_pagina = ['/assets/css/sobre.css?v=1'];
require_once __DIR__ . '/includes/header.php';
$fotos_sobre = json_decode(config_site('fotos_sobre', '[]'), true);
if (!is_array($fotos_sobre)) $fotos_sobre = [];
?>
<div class="container about-page">
    <div class="about-heading"><span>CONHEÇA A VOLTX</span><h1>Sobre VoltX</h1><p>Experiência, segurança e soluções elétricas pensadas para cada cliente.</p></div>
    <?php if ($fotos_sobre): ?><section class="about-gallery" aria-label="Fotos da VoltX"><?php foreach ($fotos_sobre as $indice => $foto): ?><figure class="<?php echo $indice === 0 ? 'about-gallery-featured' : ''; ?>"><img src="<?php echo sanitizar($foto); ?>" alt="VoltX em atividade" loading="<?php echo $indice === 0 ? 'eager' : 'lazy'; ?>"></figure><?php endforeach; ?></section><?php endif; ?>
    <div class="about-layout">
        <article class="about-content">
            <section><p class="about-kicker">NOSSA HISTÓRIA</p><h2>Quem Somos</h2><div class="markdown-content"><?php echo renderizar_markdown(config_site('texto_sobre')); ?></div></section>
            <section><p class="about-kicker">O QUE NOS MOVE</p><h2>Nossa Missão</h2><div class="markdown-content"><?php echo renderizar_markdown(config_site('missao')); ?></div></section>
            <section><p class="about-kicker">NOSSOS DIFERENCIAIS</p><h2>Por que escolher a VoltX?</h2><div class="markdown-content about-reasons"><?php echo renderizar_markdown(config_site('porque_escolher', "- Equipe de profissionais qualificados\n- Atendimento rápido e eficiente\n- Orçamentos sem compromisso\n- Garantia em todos os serviços")); ?></div></section>
        </article>
        <aside class="about-facts"><strong><?php echo (int)config_site('experiencia_anos', '0'); ?>+</strong><span>anos de experiência</span><hr><strong>VoltX</strong><span>energia segura e atendimento ágil</span></aside>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
