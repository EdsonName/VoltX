<?php
// index.php
// Página inicial
$titulo_pagina = 'Home';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <span class="eyebrow">Especialistas em elétrica</span>
            <h1>Energia segura.<br><span>Resultado potente.</span></h1>
            <p>Instalação, manutenção e reparos elétricos com profissionais qualificados, agilidade e garantia.</p>
            <div class="hero-actions">
                <a href="/orcamento.php" class="btn btn-primary">⚡ Solicitar orçamento</a>
                <a href="/servicos.php" class="btn btn-secondary">Conhecer serviços →</a>
            </div>
            <div class="hero-trust">
                <div class="trust-item"><strong>+10 anos</strong><span>de experiência</span></div>
                <div class="trust-item"><strong>100%</strong><span>serviços garantidos</span></div>
                <div class="trust-item"><strong>Ágil</strong><span>atendimento técnico</span></div>
            </div>
        </div>
    </div>
</section>

<section class="servicos-preview">
    <div class="container">
        <div class="section-heading">
            <div><span class="eyebrow">O que fazemos</span><h2>Soluções para cada necessidade</h2></div>
            <p>Do reparo emergencial ao projeto completo, cuidamos de cada detalhe com segurança e transparência.</p>
        </div>
        <div class="servicos-grid">
            <?php
            require_once __DIR__ . '/includes/functions.php';
            $servicos = pegar_servicos();
            foreach ($servicos as $servico):
            ?>
                <div class="servico-card">
                    <h3><?php echo sanitizar($servico['nome']); ?></h3>
                    <p><?php echo sanitizar(substr($servico['descricao'], 0, 100)); ?>...</p>
                    <p class="preco">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
                    <a href="/servico-detalhes.php?id=<?php echo $servico['id']; ?>" class="btn">Ver detalhes →</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="form-container">
            <span class="eyebrow">Precisa de ajuda?</span>
            <h2>Seu projeto começa com uma boa conversa.</h2>
            <p style="margin:16px 0 24px">Conte o que você precisa e receba uma orientação clara, sem compromisso.</p>
            <a href="/contato.php" class="btn">Falar com a VoltX →</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
