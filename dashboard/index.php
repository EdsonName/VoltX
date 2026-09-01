<?php
// dashboard/index.php
// Dashboard principal do cliente
$titulo_pagina = 'Minha Conta';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

verificarAutenticacao();

$usuario = pegar_usuario($_SESSION['usuario_id']);
?>

<div class="container">
    <h1>Bem-vindo, <?php echo sanitizar($usuario['nome']); ?>!</h1>
    
    <div class="dashboard-menu">
        <a href="/dashboard/agendamentos.php" class="card">
            <h3>Meus Agendamentos</h3>
            <p>Gerenciar seus agendamentos</p>
        </a>
        
        <a href="/dashboard/orcamentos.php" class="card">
            <h3>Meus Orçamentos</h3>
            <p>Histórico de orçamentos</p>
        </a>
        
        <a href="/dashboard/perfil.php" class="card">
            <h3>Meu Perfil</h3>
            <p>Editar dados pessoais</p>
        </a>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
