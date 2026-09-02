<?php
// login.php
// Página de login
$titulo_pagina = 'Login';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$redirect = url_interna_segura($_POST['redirect'] ?? $_GET['redirect'] ?? '', '');

if (usuario_autenticado()) {
    redirecionar(usuario_eh_admin() ? '/admin/' : ($redirect ?: '/dashboard/'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (!validar_email($email)) {
        $erro = 'Email inválido';
    } else {
        $sql = 'SELECT id, nome, email, senha, tipo FROM usuarios WHERE email = ?';
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        
        if ($usuario && verificar_senha($senha, $usuario['senha'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo'];
            $_SESSION['login_em'] = time();
            
            if ($usuario['tipo'] === 'admin') {
                redirecionar('/admin/');
            } else {
                redirecionar($redirect ?: '/dashboard/');
            }
        } else {
            $erro = 'Email ou senha incorretos';
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h1>Login</h1>

        <?php exibir_mensagens(); ?>
        
        <?php if (isset($erro)): ?>
            <div class="alerta alerta-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login.php">
            <input type="hidden" name="redirect" value="<?php echo sanitizar($redirect); ?>">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" autocomplete="email" required>
            </div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" autocomplete="current-password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
        
        <p>Não tem conta? <a href="/cadastro.php">Cadastre-se aqui</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
