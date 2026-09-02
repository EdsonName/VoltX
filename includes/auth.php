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
    $rota = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (($_SESSION['tipo_usuario'] ?? '') !== 'admin' && !in_array($rota, ['/completar-cpf.php','/logout.php'], true)) {
        require_once __DIR__ . '/../config/database.php';
        global $mysqli;
        $stmt = $mysqli->prepare('SELECT u.cpf,u.tipo,e.cnpj FROM usuarios u LEFT JOIN empresas e ON e.usuario_id=u.id WHERE u.id=?');
        $stmt->bind_param('i', $_SESSION['usuario_id']);
        $stmt->execute();
        $identidade=$stmt->get_result()->fetch_assoc();
        if ($identidade['tipo']==='empresa' && $rota==='/amizade.php') { header('Location: /'); exit; }
        if (($identidade['tipo']==='empresa' && empty($identidade['cnpj'])) || ($identidade['tipo']!=='empresa' && empty($identidade['cpf']))) {
            $_SESSION['cpf_retorno'] = $rota;
            header('Location: /completar-cpf.php');
            exit;
        }
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

function usuario_eh_profissional() {
    return isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] === 'profissional';
}

function verificarProfissional() {
    verificarAutenticacao();
    if (!usuario_eh_profissional()) {
        $_SESSION['erro'] = 'Esta área é exclusiva para profissionais.';
        header('Location: /dashboard/');
        exit;
    }
}
?>
