<?php
// includes/auth.php
// Sistema de autenticação

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function verificarAutenticacao() {
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['erro'] = 'Faça login para acessar esta página.';
        header('Location: /login.php');
        exit;
    }
}

function verificarAdmin() {
    verificarAutenticacao();
    
    if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'admin') {
        $_SESSION['erro'] = 'Você não tem permissão para acessar o painel administrativo.';
        header('Location: /');
        exit;
    }
}

function logout() {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: /login.php');
    exit;
}

function usuario_autenticado() {
    return isset($_SESSION['usuario_id']);
}

function usuario_eh_admin() {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'admin';
}
?>
