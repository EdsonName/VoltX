<?php
// sobre.php
// Página sobre
$titulo_pagina = 'Sobre';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <h1>Sobre VoltX</h1>
    <div class="content">
        <h2>Quem Somos</h2>
        <p><?php echo sanitizar(config_site('texto_sobre')); ?> São mais de <?php echo (int)config_site('experiencia_anos', '10'); ?> anos de experiência no mercado.</p>
        
        <h2>Nossa Missão</h2>
        <p><?php echo sanitizar(config_site('missao')); ?></p>
        
        <h2>Por que escolher a VoltX?</h2>
        <ul>
            <li>Equipe de profissionais qualificados</li>
            <li>Atendimento rápido e eficiente</li>
            <li>Orçamentos sem compromisso</li>
            <li>Garantia em todos os serviços</li>
        </ul>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
