<?php
// dashboard/orcamentos.php
// Orçamentos do cliente
$titulo_pagina = 'Meus Orçamentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAutenticacao();

$sql = 'SELECT * FROM orcamentos WHERE usuario_id = ? ORDER BY criado_em DESC';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$resultado = $stmt->get_result();
$orcamentos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Meus Orçamentos</h1>
    
    <a href="/orcamento.php" class="btn btn-primary">Solicitar Novo Orçamento</a>
    
    <?php if (count($orcamentos) > 0): ?>
        <table class="tabela">
            <thead>
                <tr>
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
                        <td><?php echo sanitizar($orcamento['titulo']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($orcamento['criado_em'])); ?></td>
                        <td><?php echo $orcamento['valor_estimado'] ? 'R$ ' . number_format($orcamento['valor_estimado'], 2, ',', '.') : '-'; ?></td>
                        <td><?php echo sanitizar($orcamento['status']); ?></td>
                        <td>
                            <a href="#" class="btn-small">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Você não tem orçamentos solicitados. <a href="/orcamento.php">Solicitar orçamento</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
