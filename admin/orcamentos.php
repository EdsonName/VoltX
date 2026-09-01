<?php
// admin/orcamentos.php
// Gerenciar orçamentos
$titulo_pagina = 'Gerenciar Orçamentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$sql = 'SELECT o.*, u.nome as cliente_nome 
        FROM orcamentos o 
        JOIN usuarios u ON o.usuario_id = u.id 
        ORDER BY o.criado_em DESC';
$resultado = $mysqli->query($sql);
$orcamentos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Gerenciar Orçamentos</h1>
    
    <table class="tabela">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Título</th>
                <th>Data</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orcamentos as $orcamento): ?>
                <tr>
                    <td><?php echo sanitizar($orcamento['cliente_nome']); ?></td>
                    <td><?php echo sanitizar($orcamento['titulo']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($orcamento['criado_em'])); ?></td>
                    <td><?php echo $orcamento['valor_estimado'] ? 'R$ ' . number_format($orcamento['valor_estimado'], 2, ',', '.') : '-'; ?></td>
                    <td><?php echo sanitizar($orcamento['status']); ?></td>
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
