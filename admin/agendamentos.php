<?php
// admin/agendamentos.php
// Gerenciar agendamentos
$titulo_pagina = 'Gerenciar Agendamentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$sql = 'SELECT a.*, u.nome as cliente_nome, s.nome as servico_nome 
        FROM agendamentos a 
        JOIN usuarios u ON a.usuario_id = u.id 
        JOIN servicos s ON a.servico_id = s.id 
        ORDER BY a.data_horario DESC';
$resultado = $mysqli->query($sql);
$agendamentos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Gerenciar Agendamentos</h1>
    
    <table class="tabela">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Serviço</th>
                <th>Data/Hora</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agendamentos as $agendamento): ?>
                <tr>
                    <td><?php echo sanitizar($agendamento['cliente_nome']); ?></td>
                    <td><?php echo sanitizar($agendamento['servico_nome']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($agendamento['data_horario'])); ?></td>
                    <td><?php echo sanitizar($agendamento['status']); ?></td>
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
