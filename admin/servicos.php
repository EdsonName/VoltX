<?php
// admin/servicos.php
// Gerenciar serviços
$titulo_pagina = 'Gerenciar Serviços';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$servicos = pegar_servicos();
?>

<div class="container">
    <h1>Gerenciar Serviços</h1>
    
    <a href="#" class="btn btn-primary">Novo Serviço</a>
    
    <table class="tabela">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Duração</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?php echo sanitizar($servico['nome']); ?></td>
                    <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $servico['duracao_minutos']; ?> min</td>
                    <td><?php echo $servico['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                    <td>
                        <a href="#" class="btn-small">Editar</a>
                        <a href="#" class="btn-small btn-danger">Deletar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
