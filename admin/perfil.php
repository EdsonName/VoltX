<?php
$titulo_pagina = 'Minha Conta e Dados da VoltX';
$estilos_pagina = ['/assets/css/admin-profile.css?v=2'];
$scripts_pagina = ['/assets/js/admin-profile.js?v=1'];
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
verificarAdmin();

$usuario = pegar_usuario($_SESSION['usuario_id']);
$campos_site = ['email_contato','telefone_contato','whatsapp','horario_atendimento','regiao_atendimento','responsavel','experiencia_anos','texto_sobre','missao','porque_escolher','fotos_sobre'];
$erros = [];
$config_atual = configuracoes_site();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validar_csrf($_POST['csrf_token'] ?? '')) $erros[] = 'A sessão expirou. Atualize a página e tente novamente.';
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    if (mb_strlen($nome) < 3) $erros[] = 'Informe seu nome completo.';
    if (!validar_email($email)) $erros[] = 'Informe um e-mail de acesso válido.';
    if (mb_strlen(preg_replace('/\D/', '', $telefone)) < 10) $erros[] = 'Informe um telefone pessoal válido.';
    if (!validar_email($_POST['email_contato'] ?? '')) $erros[] = 'Informe um e-mail comercial válido.';
    if (mb_strlen(preg_replace('/\D/', '', $_POST['whatsapp'] ?? '')) < 10) $erros[] = 'Informe um WhatsApp comercial válido.';

    $fotos_sobre = json_decode($config_atual['fotos_sobre'] ?? '[]', true) ?: [];
    $remover_fotos = $_POST['remover_fotos'] ?? [];
    $fotos_sobre = array_values(array_filter($fotos_sobre, fn($foto) => !in_array($foto, $remover_fotos, true)));
    if (!empty($_FILES['fotos_sobre']['name'][0])) {
        $diretorio = __DIR__ . '/../assets/uploads/about';
        if (!is_dir($diretorio) && !mkdir($diretorio, 0755, true)) $erros[] = 'Não foi possível preparar a pasta de fotos.';
        else {
            $extensoes = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
            foreach ($_FILES['fotos_sobre']['tmp_name'] as $indice => $temporario) {
                if ($_FILES['fotos_sobre']['error'][$indice] !== UPLOAD_ERR_OK) { $erros[] = 'Uma das fotos não pôde ser enviada.'; continue; }
                $mime = (new finfo(FILEINFO_MIME_TYPE))->file($temporario);
                if (!isset($extensoes[$mime])) { $erros[] = 'Envie apenas fotos JPG, PNG ou WebP.'; continue; }
                $arquivo = bin2hex(random_bytes(16)) . '.' . $extensoes[$mime];
                if (move_uploaded_file($temporario, $diretorio . '/' . $arquivo)) $fotos_sobre[] = '/assets/uploads/about/' . $arquivo;
                else $erros[] = 'Não foi possível salvar uma das fotos.';
            }
        }
    }
    $_POST['fotos_sobre'] = json_encode(array_values(array_unique($fotos_sobre)), JSON_UNESCAPED_SLASHES);

    if (!$erros) {
        global $mysqli;
        $mysqli->begin_transaction();
        try {
            $stmt = $mysqli->prepare('UPDATE usuarios SET nome=?, email=?, telefone=? WHERE id=?');
            $stmt->bind_param('sssi', $nome, $email, $telefone, $_SESSION['usuario_id']);
            $stmt->execute();
            $stmt_config = $mysqli->prepare('INSERT INTO configuracoes_site (chave, valor) VALUES (?,?) ON DUPLICATE KEY UPDATE valor=VALUES(valor)');
            foreach ($campos_site as $campo) {
                $valor = trim($_POST[$campo] ?? '');
                $stmt_config->bind_param('ss', $campo, $valor);
                $stmt_config->execute();
            }
            $mysqli->commit();
            $_SESSION['usuario_nome'] = $nome;
            mensagem_sucesso('Seus dados e as informações do site foram atualizados em todos os canais.');
            redirecionar('/admin/perfil.php');
        } catch (Throwable $e) {
            $mysqli->rollback();
            $erros[] = 'Não foi possível salvar os dados. Verifique se o e-mail já está em uso.';
        }
    }
}

$config = configuracoes_site();
require_once __DIR__ . '/../includes/header.php';
?>
<div class="container admin-profile-page">
    <div class="page-heading"><div><h1>Minha Conta</h1><p>Uma única fonte para seus dados pessoais e todos os contatos exibidos no site.</p></div></div>
    <?php exibir_mensagens(); ?>
    <?php if ($erros): ?><div class="alerta alerta-erro"><ul><?php foreach ($erros as $erro): ?><li><?php echo sanitizar($erro); ?></li><?php endforeach; ?></ul></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data" class="profile-settings-form"><input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>">
        <section class="settings-section"><div class="settings-title"><span>◉</span><div><h2>Dados de acesso</h2><p>Informações da sua conta administrativa.</p></div></div><div class="settings-grid"><div class="form-group"><label for="nome">Nome completo</label><input id="nome" name="nome" value="<?php echo sanitizar($usuario['nome']); ?>" required></div><div class="form-group"><label for="email">E-mail de acesso</label><input id="email" name="email" type="email" value="<?php echo sanitizar($usuario['email']); ?>" required></div><div class="form-group"><label for="telefone">Telefone pessoal</label><input id="telefone" name="telefone" value="<?php echo sanitizar($usuario['telefone']); ?>" required></div></div></section>
        <section class="settings-section"><div class="settings-title"><span>☎</span><div><h2>Contato da empresa</h2><p>Usado no rodapé, contato, serviços e botões de WhatsApp.</p></div></div><div class="settings-grid"><div class="form-group"><label for="responsavel">Nome do responsável</label><input id="responsavel" name="responsavel" value="<?php echo sanitizar($config['responsavel'] ?? ''); ?>" required></div><div class="form-group"><label for="email_contato">E-mail comercial</label><input id="email_contato" name="email_contato" type="email" value="<?php echo sanitizar($config['email_contato'] ?? ''); ?>" required></div><div class="form-group"><label for="telefone_contato">Telefone exibido</label><input id="telefone_contato" name="telefone_contato" value="<?php echo sanitizar($config['telefone_contato'] ?? ''); ?>" required></div><div class="form-group"><label for="whatsapp">WhatsApp com DDD</label><input id="whatsapp" name="whatsapp" value="<?php echo sanitizar($config['whatsapp'] ?? ''); ?>" required><small>Pode incluir 55, espaços e símbolos.</small></div><div class="form-group"><label for="horario_atendimento">Horário de atendimento</label><input id="horario_atendimento" name="horario_atendimento" value="<?php echo sanitizar($config['horario_atendimento'] ?? ''); ?>" required></div><div class="form-group"><label for="regiao_atendimento">Região atendida</label><input id="regiao_atendimento" name="regiao_atendimento" value="<?php echo sanitizar($config['regiao_atendimento'] ?? ''); ?>" required></div></div></section>
        <section class="settings-section"><div class="settings-title"><span>✦</span><div><h2>Sobre a VoltX</h2><p>Conteúdo em Markdown exibido automaticamente na página Sobre.</p></div></div><div class="settings-grid"><div class="form-group"><label for="experiencia_anos">Anos de experiência</label><input id="experiencia_anos" name="experiencia_anos" type="number" min="0" value="<?php echo sanitizar($config['experiencia_anos'] ?? ''); ?>" required></div><?php foreach (['texto_sobre'=>'Quem somos','missao'=>'Nossa missão','porque_escolher'=>'Por que escolher a VoltX?'] as $campo_markdown=>$rotulo): ?><div class="form-group settings-span markdown-field"><label for="<?php echo $campo_markdown; ?>"><?php echo $rotulo; ?></label><div class="markdown-toolbar"><button type="button" data-md="bold" title="Negrito"><strong>B</strong></button><button type="button" data-md="italic" title="Itálico"><em>I</em></button><button type="button" data-md="heading" title="Título">H2</button><button type="button" data-md="list" title="Lista">• Lista</button></div><div class="markdown-workspace"><textarea id="<?php echo $campo_markdown; ?>" name="<?php echo $campo_markdown; ?>" rows="7" required><?php echo sanitizar($config[$campo_markdown] ?? ''); ?></textarea><div class="markdown-preview" data-preview-for="<?php echo $campo_markdown; ?>"></div></div><small>Use **negrito**, *itálico*, ## títulos e - listas.</small></div><?php endforeach; ?>
        <div class="form-group settings-span about-photos"><label for="fotos_sobre">Fotos da página Sobre</label><label class="photo-upload" for="fotos_sobre"><span>＋</span><div><strong>Adicionar fotos</strong><small>JPG, PNG ou WebP. Você pode selecionar várias.</small></div></label><input id="fotos_sobre" name="fotos_sobre[]" type="file" accept="image/jpeg,image/png,image/webp" multiple><?php $fotos_atuais=json_decode($config['fotos_sobre'] ?? '[]',true) ?: []; if ($fotos_atuais): ?><div class="current-photos"><?php foreach ($fotos_atuais as $foto): ?><label><img src="<?php echo sanitizar($foto); ?>" alt="Foto institucional"><span><input type="checkbox" name="remover_fotos[]" value="<?php echo sanitizar($foto); ?>"> Remover</span></label><?php endforeach; ?></div><?php endif; ?><div class="new-photo-preview"></div></div></div></section>
        <div class="settings-save"><p>As alterações serão refletidas imediatamente em todo o site.</p><button class="btn" type="submit">Salvar todos os dados</button></div>
    </form>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
