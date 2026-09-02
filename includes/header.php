<?php
// includes/header.php
// Cabeçalho do site
require_once __DIR__ . '/../includes/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#090a0c">
    <title><?php echo isset($titulo_pagina) ? $titulo_pagina . ' - VoltX' : 'VoltX - Seu Serviço de Eletricidade'; ?></title>
    <link rel="icon" href="/assets/img/favicon.svg?v=1" type="image/svg+xml">
    <link rel="alternate icon" href="/assets/img/favicon.svg?v=1">
    <link rel="mask-icon" href="/assets/img/favicon-mask.svg?v=1" color="#ffd400">
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php foreach (($estilos_pagina ?? []) as $estilo): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($estilo, ENT_QUOTES, 'UTF-8'); ?>">
    <?php endforeach; ?>
    <?php if (usuario_eh_admin()): ?>
        <link rel="stylesheet" href="/assets/css/admin.css?v=2">
    <?php elseif (usuario_autenticado()): ?>
        <link rel="stylesheet" href="/assets/css/dashboard.css">
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="container">
            <a href="/" class="logo" aria-label="Voltar para a página inicial">
                <img src="/assets/img/logo.svg" alt="VoltX">
                <h1>Volt<span>X</span></h1>
            </a>
            <button class="menu-toggle" type="button" aria-label="Abrir menu" aria-expanded="false">☰</button>
            <nav aria-label="Navegação principal">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/servicos.php">Serviços</a></li>
                    <li><a href="/blog/">Blog</a></li>
                    <li><a href="/contato.php">Contato</a></li>
                    
                    <?php if (usuario_eh_admin()): ?>
                        <li><a href="/admin/">Painel Admin</a></li>
                        <li><a href="/logout.php">Sair</a></li>
                    <?php elseif (usuario_autenticado()): ?>
                        <li><a href="/dashboard/">Minha Conta</a></li>
                        <li><a href="/logout.php">Sair</a></li>
                    <?php else: ?>
                        <li><a href="/login.php">Entrar</a></li>
                        <li><a href="/cadastro.php" class="nav-cta">Criar conta</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
    <?php
    $caminho_atual = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if ($caminho_atual !== '/' && $caminho_atual !== '/index.php'):
        if (str_starts_with($caminho_atual, '/admin/') && !in_array($caminho_atual, ['/admin/', '/admin/index.php'], true)) {
            $destino_voltar = '/admin/';
        } elseif (str_starts_with($caminho_atual, '/dashboard/') && !in_array($caminho_atual, ['/dashboard/', '/dashboard/index.php'], true)) {
            $destino_voltar = '/dashboard/';
        } else {
            $destino_voltar = '/';
        }
    ?>
        <div class="container back-bar"><a href="<?php echo $destino_voltar; ?>" class="back-button" data-back-fallback="<?php echo $destino_voltar; ?>">← <span>Voltar</span></a></div>
    <?php endif; ?>
