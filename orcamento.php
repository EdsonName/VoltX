<?php
// orcamento.php
// Solicitação de orçamento
$titulo_pagina = 'Solicitar Orçamento';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/functions.php';

$usuario_id = usuario_autenticado() ? $_SESSION['usuario_id'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $mysqli;
    
    $titulo = sanitizar($_POST['titulo']);
    $descricao = sanitizar($_POST['descricao']);
    
    $sql = 'INSERT INTO orcamentos (usuario_id, titulo, descricao) VALUES (?, ?, ?)';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('iss', $usuario_id, $titulo, $descricao);
    
    if ($stmt->execute()) {
        mensagem_sucesso('Orçamento solicitado com sucesso! Entraremos em contato em breve.');
        if ($usuario_id) {
            redirecionar('/dashboard/orcamentos.php');
        } else {
            redirecionar('/');
        }
    } else {
        $erro = 'Erro ao solicitar orçamento. Tente novamente.';
    }
}
?>

<div class="container">
    <div class="form-container">
        <h1>Solicitar Orçamento</h1>
        
        <?php if (isset($erro)): ?>
            <div class="alerta alerta-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/orcamento.php">
            <div class="form-group">
                <label for="titulo">Título do Projeto:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="6" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Solicitar Orçamento</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
