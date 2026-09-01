<?php
$titulo_pagina = 'Gerenciar Serviços';
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
            $ativo = isset($_POST['ativo']) ? 1 : 0;
            if (mb_strlen($nome) < 3) $erros[] = 'Informe um nome com pelo menos 3 caracteres.';
            if (mb_strlen($descricao) < 10) $erros[] = 'Informe uma descrição com pelo menos 10 caracteres.';
            if ($preco === false || $preco < 0) $erros[] = 'Informe um preço válido.';
            if ($duracao === false || $duracao < 1) $erros[] = 'Informe uma duração válida.';

            if (!$erros) {
                global $mysqli;
                if ($id > 0) {
                    $stmt = $mysqli->prepare('UPDATE servicos SET nome=?, descricao=?, preco=?, duracao_minutos=?, ativo=? WHERE id=?');
                    $stmt->bind_param('ssdiii', $nome, $descricao, $preco, $duracao, $ativo, $id);
                    $mensagem = 'Serviço atualizado com sucesso.';
                } else {
                    $stmt = $mysqli->prepare('INSERT INTO servicos (nome, descricao, preco, duracao_minutos, ativo) VALUES (?,?,?,?,?)');
                    $stmt->bind_param('ssdii', $nome, $descricao, $preco, $duracao, $ativo);
                    $mensagem = 'Serviço cadastrado e publicado com sucesso.';
                }
                $stmt->execute();
                mensagem_sucesso($mensagem);
                redirecionar('/admin/servicos.php');
            }
            $servico_edicao = ['id'=>$id, 'nome'=>$nome, 'descricao'=>$descricao, 'preco'=>$preco, 'duracao_minutos'=>$duracao, 'ativo'=>$ativo];
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
    <form method="POST" class="service-form">
        <input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>"><input type="hidden" name="acao" value="salvar"><input type="hidden" name="id" value="<?php echo (int)($servico_edicao['id'] ?? 0); ?>">
        <div class="form-grid">
            <div class="form-group form-span"><label for="nome">Nome do serviço</label><input id="nome" name="nome" maxlength="255" value="<?php echo sanitizar($servico_edicao['nome'] ?? ''); ?>" required></div>
            <div class="form-group form-span"><label for="descricao">Descrição</label><textarea id="descricao" name="descricao" rows="4" required><?php echo sanitizar($servico_edicao['descricao'] ?? ''); ?></textarea></div>
            <div class="form-group"><label for="preco">Preço (R$)</label><input id="preco" name="preco" type="number" min="0" step="0.01" value="<?php echo sanitizar($servico_edicao['preco'] ?? ''); ?>" required></div>
            <div class="form-group"><label for="duracao">Duração (minutos)</label><input id="duracao" name="duracao_minutos" type="number" min="1" value="<?php echo sanitizar($servico_edicao['duracao_minutos'] ?? ''); ?>" required></div>
            <label class="toggle-field form-span"><input type="checkbox" name="ativo" value="1" <?php echo !isset($servico_edicao['ativo']) || $servico_edicao['ativo'] ? 'checked' : ''; ?>><span>Exibir este serviço na página pública</span></label>
        </div>
        <div class="form-actions"><button class="btn" type="submit">Salvar serviço</button><a class="btn btn-secondary" href="/admin/servicos.php">Cancelar</a></div>
    </form>
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
