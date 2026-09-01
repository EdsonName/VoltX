<?php
// dashboard/perfil.php
// Perfil do cliente
$titulo_pagina = 'Meu Perfil';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAutenticacao();

$usuario = pegar_usuario($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizar($_POST['nome']);
    $email = sanitizar($_POST['email']);
    $telefone = sanitizar($_POST['telefone']);
    
    $sql = 'UPDATE usuarios SET nome = ?, email = ?, telefone = ? WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sssi', $nome, $email, $telefone, $_SESSION['usuario_id']);
    
    if ($stmt->execute()) {
        mensagem_sucesso('Perfil atualizado com sucesso!');
        $usuario = pegar_usuario($_SESSION['usuario_id']);
    } else {
        $erro = 'Erro ao atualizar perfil.';
    }
}
?>

<div class="container">
    <div class="form-container">
        <h1>Meu Perfil</h1>
        
        <?php if (isset($erro)): ?>
            <div class="alerta alerta-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php exibir_mensagens(); ?>
        
        <form method="POST" action="/dashboard/perfil.php">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo sanitizar($usuario['nome']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo sanitizar($usuario['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo sanitizar($usuario['telefone']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
