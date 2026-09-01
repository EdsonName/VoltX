<?php
// dashboard/agendamentos.php
// Agendamentos do cliente
$titulo_pagina = 'Meus Agendamentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAutenticacao();

$sql = 'SELECT a.*, s.nome as servico_nome FROM agendamentos a 
        JOIN servicos s ON a.servico_id = s.id 
        WHERE a.usuario_id = ? 
        ORDER BY a.data_horario DESC';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$resultado = $stmt->get_result();
$agendamentos = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Meus Agendamentos</h1>
    
    <a href="/agendar.php" class="btn btn-primary">Novo Agendamento</a>
    
    <?php if (count($agendamentos) > 0): ?>
        <table class="tabela">
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Data/Hora</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agendamentos as $agendamento): ?>
                    <tr>
                        <td><?php echo sanitizar($agendamento['servico_nome']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($agendamento['data_horario'])); ?></td>
                        <td><?php echo sanitizar($agendamento['status']); ?></td>
                        <td>
                            <a href="#" class="btn-small">Editar</a>
                            <a href="#" class="btn-small btn-danger">Cancelar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Você não tem agendamentos. <a href="/agendar.php">Agendar agora</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
