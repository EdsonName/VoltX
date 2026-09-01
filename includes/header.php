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
    <title><?php echo isset($titulo_pagina) ? $titulo_pagina . ' - VolX' : 'VolX - Seu Serviço de Eletricidade'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <?php if (usuario_eh_admin()): ?>
        <link rel="stylesheet" href="/assets/css/admin.css">
    <?php elseif (usuario_autenticado()): ?>
        <link rel="stylesheet" href="/assets/css/dashboard.css">
    <?php endif; ?>
</head>
<body>
    <header>
        <div class="container">
            <a href="/" class="logo" aria-label="Voltar para a página inicial">
                <img src="/assets/img/logo.svg" alt="VolX">
                <h1>Vol<span>X</span></h1>
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
