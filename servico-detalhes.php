<?php
// servico-detalhes.php
// Detalhes de um serviço
$titulo_pagina = 'Detalhes do Serviço';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$servico = pegar_servico($id);

if (!$servico) {
    header('Location: /servicos.php');
    exit;
}
?>

<div class="container">
    <a href="/servicos.php" class="btn">← Voltar</a>
    
    <div class="servico-detalhes">
        <h1><?php echo sanitizar($servico['nome']); ?></h1>
        
        <div class="detalhes-info">
            <p><strong>Descrição:</strong><br>
            <?php echo sanitizar($servico['descricao']); ?></p>
            
            <p><strong>Preço:</strong> R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></p>
            <p><strong>Duração:</strong> <?php echo $servico['duracao_minutos']; ?> minutos</p>
        </div>
        
        <a href="/agendar.php?servico_id=<?php echo $servico['id']; ?>" class="btn btn-primary">Agendar Agora</a>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
