<?php
// cadastro.php
// Página de cadastro
$titulo_pagina = 'Cadastro';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$redirect = url_interna_segura($_POST['redirect'] ?? $_GET['redirect'] ?? '/dashboard/', '/dashboard/');
$tipo_inicial = ($_POST['tipo'] ?? $_GET['tipo'] ?? 'cliente') === 'profissional' ? 'profissional' : 'cliente';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = sanitizar($_POST['nome']);
    $email = sanitizar($_POST['email']);
    $telefone = sanitizar($_POST['telefone']);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $tipo = ($_POST['tipo'] ?? 'cliente') === 'profissional' ? 'profissional' : 'cliente';
    
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
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssss', $nome, $email, $telefone, $senha_hash, $tipo);
            
            if ($stmt->execute()) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $stmt->insert_id;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['tipo_usuario'] = $tipo;
                $_SESSION['login_em'] = time();
                mensagem_sucesso('Conta criada com sucesso! Seus dados já foram preenchidos.');
                redirecionar($tipo === 'profissional' ? '/dashboard/profissional.php' : $redirect);
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
            <input type="hidden" name="redirect" value="<?php echo sanitizar($redirect); ?>">
            <div class="form-group">
                <label>Como você deseja usar a VoltX?</label>
                <div class="account-type-options">
                    <label><input type="radio" name="tipo" value="cliente" <?php echo $tipo_inicial === 'cliente' ? 'checked' : ''; ?>><span><strong>Quero contratar</strong><small>Encontrar profissionais e serviços.</small></span></label>
                    <label><input type="radio" name="tipo" value="profissional" <?php echo $tipo_inicial === 'profissional' ? 'checked' : ''; ?>><span><strong>Quero trabalhar</strong><small>Criar meu perfil e oferecer serviços.</small></span></label>
                </div>
            </div>
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
