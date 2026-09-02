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
        <a href="/chat.php" class="card"><h3>Mensagens</h3><p>Conversar com clientes e profissionais</p></a>
        <?php if (usuario_eh_profissional()): ?>
        <a href="/dashboard/profissional.php" class="card"><h3>Meu Perfil Profissional</h3><p>Editar apresentação e área de atendimento</p></a>
        <a href="/dashboard/ofertas.php" class="card"><h3>Meus Serviços</h3><p>Cadastrar e gerenciar suas ofertas</p></a>
        <a href="/dashboard/feed.php" class="card"><h3>Minhas Publicações</h3><p>Criar conteúdo para o feed público</p></a>
        <?php endif; ?>
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
