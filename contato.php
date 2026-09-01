<?php
// contato.php
// Página de contato
$titulo_pagina = 'Contato';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $email = htmlspecialchars($_POST['email']);
    $assunto = htmlspecialchars($_POST['assunto']);
    $mensagem = htmlspecialchars($_POST['mensagem']);
    
    // Aqui você pode implementar o envio de email
    // mail('contato@volx.com', $assunto, $mensagem, "From: $email");
    
    $sucesso = 'Mensagem enviada com sucesso! Responderemos em breve.';
}
?>

<div class="container">
    <div class="form-container">
        <h1>Fale Conosco</h1>
        
        <?php if (isset($sucesso)): ?>
            <div class="alerta alerta-sucesso"><?php echo $sucesso; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/contato.php">
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" name="assunto" required>
            </div>
            
            <div class="form-group">
                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" rows="6" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
