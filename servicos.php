<?php
// servicos.php
// Lista de serviços
$titulo_pagina = 'Serviços';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$servicos = pegar_servicos();
?>

<div class="container">
    <h1>Nossos Serviços</h1>
    
    <div class="servicos-grid">
        <?php foreach ($servicos as $servico): ?>
            <div class="servico-card">
                <h3><?php echo sanitizar($servico['nome']); ?></h3>
                <p><?php echo sanitizar($servico['descricao']); ?></p>
                <p class="preco">R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
                <p class="duracao">Duração: <?php echo $servico['duracao_minutos']; ?> minutos</p>
                <a href="/servico-detalhes.php?id=<?php echo $servico['id']; ?>" class="btn btn-primary">Ver Detalhes</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
