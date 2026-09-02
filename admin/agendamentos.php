<?php
// admin/agendamentos.php
// Gerenciar agendamentos
$titulo_pagina = 'Gerenciar Agendamentos';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$sql = 'SELECT a.*, u.nome as cliente_nome, u.telefone as cliente_telefone, u.email as cliente_email, s.nome as servico_nome
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
                <?php foreach ($agendamentos as $agendamento):
                    $numero = preg_replace('/\D/', '', $agendamento['cliente_telefone'] ?? '');
                    if ($numero && !str_starts_with($numero, '55')) $numero = '55' . $numero;
                    $mensagem_whatsapp = "Olá, {$agendamento['cliente_nome']}! Aqui é da VoltX. Estamos entrando em contato sobre seu agendamento de {$agendamento['servico_nome']} para " . date('d/m/Y \à\s H:i', strtotime($agendamento['data_horario'])) . ".\n\nEndereço: {$agendamento['endereco']} — {$agendamento['bairro_cidade']}.";
                ?>
                <tr>
                    <td><?php echo sanitizar($agendamento['cliente_nome']); ?></td>
                    <td><?php echo sanitizar($agendamento['servico_nome']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($agendamento['data_horario'])); ?></td>
                    <td><?php echo sanitizar($agendamento['status']); ?></td>
                        <td>
                            <?php if (!empty($agendamento['telefone_whatsapp']) && $numero): ?><a href="https://wa.me/<?php echo $numero; ?>?text=<?php echo rawurlencode($mensagem_whatsapp); ?>" class="btn-small whatsapp-admin" target="_blank" rel="noopener noreferrer">WhatsApp</a><?php endif; ?>
                            <a href="#" class="btn-small">Editar</a>
                        <a href="#" class="btn-small btn-danger">Deletar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
