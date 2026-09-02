<?php
$titulo_pagina = 'Gerenciar Serviços';
$scripts_pagina = ['/assets/js/admin-servicos.js'];
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
verificarAdmin();

$erros = [];
$servico_edicao = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf($_POST['csrf_token'] ?? '')) {
        $erros[] = 'A sessão do formulário expirou. Atualize a página e tente novamente.';
    } else {
        $acao = $_POST['acao'] ?? '';
        $id = (int) ($_POST['id'] ?? 0);

        if ($acao === 'salvar') {
            $nome = trim($_POST['nome'] ?? '');
            $descricao = trim($_POST['descricao'] ?? '');
            $preco = filter_var(str_replace(',', '.', $_POST['preco'] ?? ''), FILTER_VALIDATE_FLOAT);
            $duracao = filter_var($_POST['duracao_minutos'] ?? '', FILTER_VALIDATE_INT);
            $imagem_url = trim($_POST['imagem_url'] ?? '');
            $selo = trim($_POST['selo'] ?? '');
            $beneficios = trim($_POST['beneficios'] ?? '');
            $destaque_emergencia = isset($_POST['destaque_emergencia']) ? 1 : 0;
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            if (mb_strlen($nome) < 3) $erros[] = 'Informe um nome com pelo menos 3 caracteres.';
            if (mb_strlen($descricao) < 10) $erros[] = 'Informe uma descrição com pelo menos 10 caracteres.';
            if ($preco === false || $preco < 0) $erros[] = 'Informe um preço válido.';
            if ($duracao === false || $duracao < 1) $erros[] = 'Informe uma duração válida.';
            if ($imagem_url !== '' && !filter_var($imagem_url, FILTER_VALIDATE_URL)) $erros[] = 'Informe uma URL de imagem válida.';
            if (mb_strlen($selo) > 80) $erros[] = 'O selo deve ter no máximo 80 caracteres.';

            if (!$erros) {
                global $mysqli;
                if ($id > 0) {
                    $stmt = $mysqli->prepare('UPDATE servicos SET nome=?, descricao=?, preco=?, duracao_minutos=?, imagem_url=?, selo=?, beneficios=?, destaque_emergencia=?, ativo=? WHERE id=?');
                    $stmt->bind_param('ssdisssiii', $nome, $descricao, $preco, $duracao, $imagem_url, $selo, $beneficios, $destaque_emergencia, $ativo, $id);
                    $mensagem = 'Serviço atualizado com sucesso.';
                } else {
                    $stmt = $mysqli->prepare('INSERT INTO servicos (nome, descricao, preco, duracao_minutos, imagem_url, selo, beneficios, destaque_emergencia, ativo) VALUES (?,?,?,?,?,?,?,?,?)');
                    $stmt->bind_param('ssdisssii', $nome, $descricao, $preco, $duracao, $imagem_url, $selo, $beneficios, $destaque_emergencia, $ativo);
                    $mensagem = 'Serviço cadastrado e publicado com sucesso.';
                }
                $stmt->execute();
                mensagem_sucesso($mensagem);
                redirecionar('/admin/servicos.php');
            }
            $servico_edicao = compact('id','nome','descricao','preco','imagem_url','selo','beneficios','destaque_emergencia','ativo');
            $servico_edicao['duracao_minutos'] = $duracao;
        } elseif ($acao === 'alternar' && $id > 0) {
            global $mysqli;
            $stmt = $mysqli->prepare('UPDATE servicos SET ativo=NOT ativo WHERE id=?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            mensagem_sucesso('Visibilidade do serviço atualizada.');
            redirecionar('/admin/servicos.php');
        }
    }
}

if (!$servico_edicao && isset($_GET['editar'])) {
    global $mysqli;
    $id_edicao = (int) $_GET['editar'];
    $stmt = $mysqli->prepare('SELECT * FROM servicos WHERE id=?');
    $stmt->bind_param('i', $id_edicao);
    $stmt->execute();
    $servico_edicao = $stmt->get_result()->fetch_assoc();
}

$mostrar_formulario = isset($_GET['novo']) || $servico_edicao;
$servicos = pegar_todos_servicos();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <div class="page-heading">
        <div><h1>Gerenciar Serviços</h1><p>Cadastre e controle os serviços exibidos no site.</p></div>
        <?php if (!$mostrar_formulario): ?><a href="?novo=1" class="btn">＋ Novo Serviço</a><?php endif; ?>
    </div>
    <?php exibir_mensagens(); ?>
    <?php if ($erros): ?><div class="alerta alerta-erro"><ul><?php foreach ($erros as $erro): ?><li><?php echo sanitizar($erro); ?></li><?php endforeach; ?></ul></div><?php endif; ?>

    <?php if ($mostrar_formulario): ?>
    <div class="service-editor"><form method="POST" class="service-form">
        <input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>"><input type="hidden" name="acao" value="salvar"><input type="hidden" name="id" value="<?php echo (int)($servico_edicao['id'] ?? 0); ?>">
        <div class="form-grid">
            <div class="form-group form-span"><label for="nome">Nome do serviço</label><input id="nome" name="nome" maxlength="255" value="<?php echo sanitizar($servico_edicao['nome'] ?? ''); ?>" required></div>
            <div class="form-group form-span"><label for="descricao">Descrição</label><textarea id="descricao" name="descricao" rows="4" required><?php echo sanitizar($servico_edicao['descricao'] ?? ''); ?></textarea></div>
            <div class="form-group form-span"><label for="imagem_url">URL da imagem</label><input id="imagem_url" name="imagem_url" type="url" placeholder="https://exemplo.com/imagem.jpg" value="<?php echo sanitizar($servico_edicao['imagem_url'] ?? ''); ?>"><small>Use uma imagem horizontal, preferencialmente 700 × 400 px.</small></div>
            <div class="form-group"><label for="preco">Preço (R$)</label><input id="preco" name="preco" type="number" min="0" step="0.01" value="<?php echo sanitizar($servico_edicao['preco'] ?? ''); ?>" required></div>
            <div class="form-group"><label for="duracao">Duração (minutos)</label><input id="duracao" name="duracao_minutos" type="number" min="1" value="<?php echo sanitizar($servico_edicao['duracao_minutos'] ?? ''); ?>" required></div>
            <div class="form-group form-span"><label for="selo">Selo do card</label><input id="selo" name="selo" maxlength="80" placeholder="Ex.: Mais pedido, Segurança, Atendimento 24h" value="<?php echo sanitizar($servico_edicao['selo'] ?? ''); ?>"></div>
            <div class="form-group form-span"><label for="beneficios">Benefícios</label><textarea id="beneficios" name="beneficios" rows="3" placeholder="Digite um benefício por linha"><?php echo sanitizar($servico_edicao['beneficios'] ?? ''); ?></textarea></div>
            <label class="toggle-field form-span"><input type="checkbox" name="destaque_emergencia" value="1" <?php echo !empty($servico_edicao['destaque_emergencia']) ? 'checked' : ''; ?>><span>Destacar como atendimento de emergência</span></label>
            <label class="toggle-field form-span"><input type="checkbox" name="ativo" value="1" <?php echo !isset($servico_edicao['ativo']) || $servico_edicao['ativo'] ? 'checked' : ''; ?>><span>Exibir este serviço na página pública</span></label>
        </div>
        <div class="form-actions"><button class="btn" type="submit">Salvar serviço</button><a class="btn btn-secondary" href="/admin/servicos.php">Cancelar</a></div>
    </form><aside class="service-preview"><span class="preview-label">Prévia do card</span><div class="preview-card"><div class="preview-image"><img src="<?php echo sanitizar($servico_edicao['imagem_url'] ?? ''); ?>" alt=""><span class="preview-placeholder">Imagem do serviço</span><b><?php echo sanitizar($servico_edicao['selo'] ?? 'Profissional'); ?></b></div><div class="preview-body"><h3>⚡ <span><?php echo sanitizar($servico_edicao['nome'] ?? 'Nome do serviço'); ?></span></h3><strong>A partir de R$ <span><?php echo isset($servico_edicao['preco']) && $servico_edicao['preco'] !== '' ? number_format((float)$servico_edicao['preco'],2,',','.') : '0,00'; ?></span></strong><p><?php echo sanitizar($servico_edicao['descricao'] ?? 'A descrição do serviço aparecerá aqui.'); ?></p><ul><li>✓ Benefício do serviço</li></ul><button type="button">Pedir no WhatsApp</button></div></div></aside></div>
    <?php endif; ?>

    <?php if ($servicos): ?>
    <table class="tabela"><thead><tr><th>Nome</th><th>Preço</th><th>Duração</th><th>Status</th><th>Ações</th></tr></thead><tbody>
    <?php foreach ($servicos as $servico): ?><tr>
        <td><strong><?php echo sanitizar($servico['nome']); ?></strong></td><td>R$ <?php echo number_format($servico['preco'],2,',','.'); ?></td><td><?php echo (int)$servico['duracao_minutos']; ?> min</td>
        <td><span class="status <?php echo $servico['ativo'] ? 'status-confirmado' : 'status-cancelado'; ?>"><?php echo $servico['ativo'] ? 'Publicado' : 'Oculto'; ?></span></td>
        <td class="table-actions"><a href="?editar=<?php echo $servico['id']; ?>" class="btn-small">Editar</a><form method="POST"><input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>"><input type="hidden" name="acao" value="alternar"><input type="hidden" name="id" value="<?php echo $servico['id']; ?>"><button class="btn-small btn-secondary" type="submit"><?php echo $servico['ativo'] ? 'Ocultar' : 'Publicar'; ?></button></form></td>
    </tr><?php endforeach; ?></tbody></table>
    <?php else: ?><div class="empty-state"><strong>Nenhum serviço cadastrado.</strong><p>Crie o primeiro serviço para exibi-lo no site.</p></div><?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
