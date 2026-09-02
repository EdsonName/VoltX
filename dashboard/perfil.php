<?php
// dashboard/perfil.php
// Perfil do cliente
$titulo_pagina = 'Meu Perfil';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAutenticacao();

$usuario = pegar_usuario($_SESSION['usuario_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erro = null;
    $nome = normalizar_nome($_POST['nome'] ?? '');
    $email = normalizar_email($_POST['email'] ?? '');
    $telefone = normalizar_telefone_br($_POST['telefone'] ?? '');
    $foto = $usuario['foto_perfil'] ?? '';
    if (!validar_csrf($_POST['csrf_token'] ?? '')) $erro='A sessão expirou.';
    elseif (!validar_email($email) || !in_array(strlen($telefone),[10,11],true)) $erro='Confira o e-mail e o telefone.';
    if (!$erro && !empty($_FILES['foto_perfil']['name'])) {
        $mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['foto_perfil']['tmp_name']);$ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
        if (!isset($ext[$mime])) $erro='Envie uma foto JPG, PNG ou WebP.'; else {$dir=__DIR__.'/../assets/uploads/profiles';if(!is_dir($dir))mkdir($dir,0755,true);$arquivo=bin2hex(random_bytes(16)).'.'.$ext[$mime];if(move_uploaded_file($_FILES['foto_perfil']['tmp_name'],$dir.'/'.$arquivo))$foto='/assets/uploads/profiles/'.$arquivo;else $erro='Não foi possível salvar a foto.';}
    }
    
    $sql = 'UPDATE usuarios SET nome = ?, email = ?, telefone = ?, foto_perfil = ? WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssssi', $nome, $email, $telefone, $foto, $_SESSION['usuario_id']);
    
    if (!$erro && $stmt->execute()) {
        mensagem_sucesso('Perfil atualizado com sucesso!');
        $usuario = pegar_usuario($_SESSION['usuario_id']);
    } elseif (!$erro) {
        $erro = 'Erro ao atualizar perfil.';
    }
}
?>

<div class="container">
    <div class="form-container">
        <h1>Meu Perfil</h1>
        
        <?php if (isset($erro)): ?>
            <div class="alerta alerta-erro"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php exibir_mensagens(); ?>
        
        <form method="POST" action="/dashboard/perfil.php" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?php echo token_csrf(); ?>">
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo sanitizar($usuario['nome']); ?>" required>
            </div>
            <div class="form-group"><label>CPF:</label><input value="<?php echo sanitizar($usuario['cpf'] ? mascarar_contato($usuario['cpf'],3) : 'Pendente — procure o suporte'); ?>" disabled><small>O CPF é protegido e não pode ser alterado.</small></div><div class="form-group"><label for="foto_perfil">Foto de perfil</label><?php if($usuario['foto_perfil']): ?><img src="<?php echo sanitizar($usuario['foto_perfil']); ?>" alt="Foto atual" style="width:90px;height:90px;object-fit:cover;border-radius:14px;display:block;margin-bottom:10px"><?php endif; ?><input id="foto_perfil" name="foto_perfil" type="file" accept="image/jpeg,image/png,image/webp"></div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo sanitizar($usuario['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo sanitizar($usuario['telefone']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
