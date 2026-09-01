<?php
// agendar.php
// Agendar um serviço
$titulo_pagina = 'Agendar Serviço';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

verificarAutenticacao();

$servico_id = isset($_GET['servico_id']) ? (int)$_GET['servico_id'] : 0;
$servico = pegar_servico($servico_id);

if (!$servico) {
    header('Location: /servicos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $mysqli;
    
    $data_horario = sanitizar($_POST['data_horario']);
    $observacoes = sanitizar($_POST['observacoes']);
    
    $sql = 'INSERT INTO agendamentos (usuario_id, servico_id, data_horario, observacoes) VALUES (?, ?, ?, ?)';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('iiss', $_SESSION['usuario_id'], $servico_id, $data_horario, $observacoes);
    
    if ($stmt->execute()) {
        mensagem_sucesso('Agendamento realizado com sucesso!');
        redirecionar('/dashboard/agendamentos.php');
    } else {
        $erro = 'Erro ao agendar. Tente novamente.';
    }
}
?>

<div class="container">
    <h1>Agendar: <?php echo sanitizar($servico['nome']); ?></h1>
    
    <?php if (isset($erro)): ?>
        <div class="alerta alerta-erro"><?php echo $erro; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/agendar.php?servico_id=<?php echo $servico_id; ?>">
        <div class="form-group">
            <label for="data_horario">Data e Hora:</label>
            <input type="datetime-local" id="data_horario" name="data_horario" required>
        </div>
        
        <div class="form-group">
            <label for="observacoes">Observações:</label>
            <textarea id="observacoes" name="observacoes" rows="4"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Confirmar Agendamento</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
