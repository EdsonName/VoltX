<?php
$titulo_pagina = 'Agendar Serviço';
$estilos_pagina = ['/assets/css/agendar.css?v=1', '/assets/css/agendar-whatsapp.css?v=1'];
$scripts_pagina = ['/assets/js/agendar.js?v=1'];
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
verificarAutenticacao();

$servico_id = isset($_GET['servico_id']) ? (int)$_GET['servico_id'] : (int)($_POST['servico_id'] ?? 0);
$servico = pegar_servico($servico_id);
if (!$servico) {
    mensagem_erro('Este serviço não está disponível para agendamento.');
    redirecionar('/servicos.php');
}
$usuario = pegar_usuario($_SESSION['usuario_id']);
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf($_POST['csrf_token'] ?? '')) {
        $erros[] = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    }
    $data = trim($_POST['data'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $cep = trim($_POST['cep'] ?? '');
    $bairro_cidade = trim($_POST['bairro_cidade'] ?? '');
    $endereco = trim($_POST['endereco'] ?? '');
    $localizacao_gps = trim($_POST['localizacao_gps'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    $telefone_whatsapp = isset($_POST['telefone_whatsapp']) ? 1 : 0;
    $data_horario = DateTime::createFromFormat('Y-m-d H:i', $data . ' ' . $hora);
    $agora = new DateTime();

    if (!$data_horario || $data_horario->format('Y-m-d H:i') !== $data . ' ' . $hora) $erros[] = 'Selecione uma data e um horário válidos.';
    elseif ($data_horario <= $agora) $erros[] = 'Escolha uma data e horário futuros.';
    if (!preg_match('/^\d{5}-?\d{3}$/', $cep)) $erros[] = 'Informe um CEP válido.';
    if (mb_strlen($bairro_cidade) < 3) $erros[] = 'Informe o bairro e a cidade.';
    if (mb_strlen($endereco) < 5) $erros[] = 'Informe o endereço completo do atendimento.';

    if (!$erros) {
        global $mysqli;
        $data_sql = $data_horario->format('Y-m-d H:i:s');
        $stmt = $mysqli->prepare('INSERT INTO agendamentos (usuario_id, servico_id, data_horario, observacoes, cep, bairro_cidade, endereco, localizacao_gps, telefone_whatsapp) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('iissssssi', $_SESSION['usuario_id'], $servico_id, $data_sql, $observacoes, $cep, $bairro_cidade, $endereco, $localizacao_gps, $telefone_whatsapp);
        if ($stmt->execute()) {
            mensagem_sucesso('Agendamento solicitado com sucesso! Aguarde nossa confirmação.');
            redirecionar('/dashboard/agendamentos.php');
        }
        $erros[] = 'Não foi possível concluir o agendamento. Tente novamente.';
    }
}

$imagem = $servico['imagem_url'] ?: 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=700&q=82';
require_once __DIR__ . '/includes/header.php';
?>
<section class="booking-page"><div class="container">
    <div class="booking-heading"><span class="eyebrow">Agendamento VoltX</span><h1>Reserve seu atendimento</h1><p>Escolha o melhor horário e informe onde o serviço será realizado.</p></div>
    <?php if ($erros): ?><div class="alerta alerta-erro"><ul><?php foreach ($erros as $erro): ?><li><?php echo sanitizar($erro); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="POST" class="booking-grid" id="booking-form">
        <input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>"><input type="hidden" name="servico_id" value="<?php echo $servico_id; ?>"><input type="hidden" name="localizacao_gps" id="coords-gps" value="<?php echo sanitizar($_POST['localizacao_gps'] ?? ''); ?>">
        <div class="booking-form-card">
            <section class="booking-section"><h2><span>1</span> Escolha data e hora</h2><div class="booking-row"><div class="form-group"><label for="data-agendamento">Data preferencial</label><input type="date" id="data-agendamento" name="data" value="<?php echo sanitizar($_POST['data'] ?? ''); ?>" required></div><div class="form-group"><label for="hora-agendamento">Horário</label><input type="time" id="hora-agendamento" name="hora" value="<?php echo sanitizar($_POST['hora'] ?? '08:00'); ?>" required></div></div><div class="form-group"><label>Horários sugeridos</label><div class="time-slots"><?php foreach (['08:00','10:00','14:00','16:00'] as $hora_slot): ?><button type="button" class="time-slot <?php echo ($_POST['hora'] ?? '08:00') === $hora_slot ? 'active' : ''; ?>" data-time="<?php echo $hora_slot; ?>"><?php echo $hora_slot; ?></button><?php endforeach; ?></div></div></section>
            <section class="booking-section"><h2><span>2</span> Seus dados</h2><div class="booking-row"><div class="form-group"><label>Nome completo</label><input value="<?php echo sanitizar($usuario['nome']); ?>" readonly></div><div class="form-group"><label>Telefone cadastrado</label><input value="<?php echo sanitizar($usuario['telefone']); ?>" readonly></div></div><label class="whatsapp-confirm"><input type="checkbox" name="telefone_whatsapp" value="1" <?php echo !isset($_POST['telefone_whatsapp']) || !empty($_POST['telefone_whatsapp']) ? 'checked' : ''; ?>><span><strong>Este número também é WhatsApp</strong><small>Autorizo a VoltX a entrar em contato sobre este agendamento.</small></span></label><a class="profile-help" href="/dashboard/perfil.php">O número está incorreto? Atualizar meu perfil →</a></section>
            <section class="booking-section"><h2><span>3</span> Endereço do atendimento</h2><div class="booking-row address-row"><div class="form-group cep-field"><label for="cep">CEP</label><input id="cep" name="cep" maxlength="9" inputmode="numeric" placeholder="72870-000" value="<?php echo sanitizar($_POST['cep'] ?? ''); ?>" required><small id="cep-status"></small></div><div class="form-group"><label for="bairro-cidade">Bairro / cidade</label><input id="bairro-cidade" name="bairro_cidade" placeholder="Bairro — Cidade/UF" value="<?php echo sanitizar($_POST['bairro_cidade'] ?? ''); ?>" required></div></div><div class="form-group"><label for="endereco">Rua, número e complemento</label><input id="endereco" name="endereco" placeholder="Rua, número, quadra, lote ou apartamento" value="<?php echo sanitizar($_POST['endereco'] ?? ''); ?>" required></div><button type="button" class="location-button" id="location-button">⌖ Usar minha localização atual</button><div id="location-status" class="location-status" aria-live="polite"></div></section>
            <section class="booking-section"><h2><span>4</span> Informações adicionais</h2><div class="form-group"><label for="observacoes">Observações sobre o serviço</label><textarea id="observacoes" name="observacoes" rows="4" placeholder="Conte detalhes que possam ajudar no atendimento."><?php echo sanitizar($_POST['observacoes'] ?? ''); ?></textarea></div></section>
        </div>
        <aside class="booking-summary"><span class="summary-label">Resumo da solicitação</span><div class="summary-image"><img src="<?php echo sanitizar($imagem); ?>" alt="<?php echo sanitizar($servico['nome']); ?>"></div><div class="summary-service"><span>⚡</span><div><h2><?php echo sanitizar($servico['nome']); ?></h2><p><?php echo sanitizar($servico['selo'] ?: 'Serviço elétrico profissional'); ?></p></div></div><div class="summary-details"><div><span>Valor médio</span><strong>R$ <?php echo number_format($servico['preco'],2,',','.'); ?></strong></div><div><span>Duração estimada</span><strong><?php echo (int)$servico['duracao_minutos']; ?> minutos</strong></div><div><span>Atendimento</span><strong>DF e Entorno</strong></div></div><p class="price-disclaimer">O valor é uma estimativa e pode variar após a avaliação técnica.</p><button type="submit" class="confirm-booking">Confirmar agendamento ✓</button><a href="/servicos.php" class="change-service">Escolher outro serviço</a></aside>
    </form>
</div></section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
