<?php
// cadastro.php
// Página de cadastro
$titulo_pagina = 'Cadastro';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/includes/functions.php';
    
    $nome = sanitizar($_POST['nome']);
    $email = sanitizar($_POST['email']);
    $telefone = sanitizar($_POST['telefone']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    
    $erros = [];
    
    if (strlen($nome) < 3) {
        $erros[] = 'Nome deve ter no mínimo 3 caracteres';
    }
    
    if (!validar_email($email)) {
        $erros[] = 'Email inválido';
    }
    
    if (strlen($senha) < 6) {
        $erros[] = 'Senha deve ter no mínimo 6 caracteres';
    }
    
    if ($senha !== $confirmar_senha) {
        $erros[] = 'As senhas não conferem';
    }
    
    if (empty($erros)) {
        $sql = 'SELECT id FROM usuarios WHERE email = ?';
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $erros[] = 'Email já cadastrado';
        } else {
            $senha_hash = hash_senha($senha);
            $sql = 'INSERT INTO usuarios (nome, email, telefone, senha, tipo) VALUES (?, ?, ?, ?, ?)';
            $tipo = 'cliente';
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssss', $nome, $email, $telefone, $senha_hash, $tipo);
            
            if ($stmt->execute()) {
                mensagem_sucesso('Cadastro realizado com sucesso! Faça login agora.');
                redirecionar('/login.php');
            } else {
                $erros[] = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h1>Cadastro</h1>
        
        <?php if (isset($erros) && !empty($erros)): ?>
            <div class="alerta alerta-erro">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/cadastro.php">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
        
        <p>Já tem conta? <a href="/login.php">Faça login aqui</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
