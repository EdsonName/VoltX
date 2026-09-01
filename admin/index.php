<?php
// admin/index.php
// Painel administrativo
$titulo_pagina = 'Painel Admin';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';

verificarAdmin();
?>

<div class="container">
    <h1>Painel Administrativo</h1>
    
    <div class="admin-menu">
        <a href="/admin/servicos.php" class="card">
            <h3>Gerenciar Serviços</h3>
            <p>Adicionar, editar ou remover serviços</p>
        </a>
        
        <a href="/admin/agendamentos.php" class="card">
            <h3>Gerenciar Agendamentos</h3>
            <p>Visualizar e controlar agendamentos</p>
        </a>
        
        <a href="/admin/orcamentos.php" class="card">
            <h3>Gerenciar Orçamentos</h3>
            <p>Controlar orçamentos solicitados</p>
        </a>
        
        <a href="/admin/postagens.php" class="card">
            <h3>Gerenciar Postagens</h3>
            <p>Criar e editar posts do blog</p>
        </a>
        
        <a href="/admin/clientes.php" class="card">
            <h3>Gerenciar Clientes</h3>
            <p>Visualizar e gerenciar clientes</p>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
