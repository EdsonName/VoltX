<?php
// cadastro.php
// Página de cadastro
$titulo_pagina = 'Cadastro';
$estilos_pagina = ['/assets/css/cadastro.css?v=1'];
$scripts_pagina = ['/assets/js/cadastro.js?v=1','/assets/js/cpf.js?v=1','/assets/js/company-signup.js?v=1'];
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$redirect = url_interna_segura($_POST['redirect'] ?? $_GET['redirect'] ?? '/dashboard/', '/dashboard/');
$tipo_inicial = ($_POST['tipo'] ?? $_GET['tipo'] ?? 'cliente') === 'profissional' ? 'profissional' : 'cliente';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = normalizar_nome($_POST['nome'] ?? '');
    $email = normalizar_email($_POST['email'] ?? '');
    $telefone = normalizar_telefone_br($_POST['telefone'] ?? '');
    $cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
    $cnpj = mb_strtoupper(preg_replace('/[^A-Z0-9]/i','',$_POST['cnpj'] ?? '')); $razao_social=trim($_POST['razao_social']??''); $nome_fantasia=trim($_POST['nome_fantasia']??'');
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $tipo = in_array($_POST['tipo'] ?? '', ['profissional','empresa'],true) ? $_POST['tipo'] : 'cliente';
    
    $erros = [];
    
    if (strlen($nome) < 3) {
        $erros[] = 'Nome deve ter no mínimo 3 caracteres';
    }
    
    if (!validar_email($email)) {
        $erros[] = 'E-mail inválido ou incompatível com as regras do provedor.';
    }
    if (!in_array(strlen($telefone), [10, 11], true)) $erros[] = 'Informe um telefone brasileiro com DDD.';
    if ($tipo!=='empresa' && !validar_cpf($cpf)) $erros[] = 'Informe um CPF válido.';
    if ($tipo!=='empresa' && validar_cpf($cpf) && limite_contas_cpf_atingido($cpf)) $erros[]='Este CPF já possui duas contas vinculadas.';
    if ($tipo==='empresa' && (!validar_cnpj($cnpj)||mb_strlen($razao_social)<3||mb_strlen($nome_fantasia)<2)) $erros[]='Informe CNPJ válido, razão social e nome fantasia.';
    if ($tipo==='empresa' && validar_cnpj($cnpj)) {$check=$mysqli->prepare('SELECT id FROM empresas WHERE cnpj=?');$check->bind_param('s',$cnpj);$check->execute();if($check->get_result()->num_rows)$erros[]='CNPJ já cadastrado.';}
    
    if (!senha_forte($senha)) {
        $erros[] = 'A senha deve ter 8 caracteres, letra maiúscula, minúscula, número e caractere especial.';
    }
    
    if ($senha !== $confirmar_senha) {
        $erros[] = 'As senhas não conferem';
    }
    
    if (empty($erros)) {
        $sql = 'SELECT id FROM usuarios WHERE email = ?';
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $erros[] = 'Email já cadastrado';
        } else {
            $senha_hash = hash_senha($senha);
            $sql = 'INSERT INTO usuarios (nome, email, telefone, cpf, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)';
            $stmt = $mysqli->prepare($sql); $cpf_banco=$tipo==='empresa'?null:$cpf;
            $stmt->bind_param('ssssss', $nome, $email, $telefone, $cpf_banco, $senha_hash, $tipo);
            
            if ($stmt->execute()) {
                $novo_usuario=$stmt->insert_id;if($tipo==='empresa'){$empresa=$mysqli->prepare('INSERT INTO empresas(usuario_id,cnpj,razao_social,nome_fantasia) VALUES(?,?,?,?)');$empresa->bind_param('isss',$novo_usuario,$cnpj,$razao_social,$nome_fantasia);$empresa->execute();}session_regenerate_id(true);
                $_SESSION['usuario_id'] = $novo_usuario;
                $_SESSION['usuario_nome'] = $nome;
                $_SESSION['tipo_usuario'] = $tipo;
                $_SESSION['login_em'] = time();
                mensagem_sucesso('Conta criada com sucesso! Seus dados já foram preenchidos.');
                redirecionar($tipo === 'profissional' ? '/dashboard/profissional.php' : ($tipo==='empresa'?'/empresa/painel.php':$redirect));
            } else {
                $erros[] = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h1>Cadastro</h1>
        
        <?php if (isset($erros) && !empty($erros)): ?>
            <div class="alerta alerta-erro">
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/cadastro.php">
            <input type="hidden" name="redirect" value="<?php echo sanitizar($redirect); ?>">
            <div class="form-group">
                <label>Como você deseja usar a VoltX?</label>
                <div class="account-type-options">
                    <label><input type="radio" name="tipo" value="cliente" <?php echo $tipo_inicial === 'cliente' ? 'checked' : ''; ?>><span><strong>Quero contratar</strong><small>Encontrar profissionais e serviços.</small></span></label>
                    <label><input type="radio" name="tipo" value="profissional" <?php echo $tipo_inicial === 'profissional' ? 'checked' : ''; ?>><span><strong>Quero trabalhar</strong><small>Criar meu perfil e oferecer serviços.</small></span></label><label><input type="radio" name="tipo" value="empresa"><span><strong>Sou empresa</strong><small>Publicar vagas e encontrar talentos.</small></span></label>
                </div>
            </div>
            <div class="form-group">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?php echo sanitizar($_POST['nome'] ?? ''); ?>" autocomplete="name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo sanitizar($_POST['email'] ?? ''); ?>" autocomplete="email" required><small id="email-feedback"></small>
            </div>
            
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="tel" id="telefone" name="telefone" value="<?php echo sanitizar($_POST['telefone'] ?? ''); ?>" inputmode="tel" autocomplete="tel" required>
            </div>
            <div class="form-group"><label for="cpf">CPF:</label><input type="text" id="cpf" name="cpf" value="<?php echo sanitizar($_POST['cpf'] ?? ''); ?>" inputmode="numeric" pattern="[0-9]{11}" maxlength="11" autocomplete="off" required><small>Somente números. Não poderá ser alterado após o cadastro.</small></div>
            <div id="company-fields" hidden><div class="form-group"><label for="cnpj">CNPJ numérico ou alfanumérico</label><input id="cnpj" name="cnpj" maxlength="14"></div><div class="form-group"><label for="razao_social">Razão social</label><input id="razao_social" name="razao_social"></div><div class="form-group"><label for="nome_fantasia">Nome fantasia</label><input id="nome_fantasia" name="nome_fantasia"></div></div>
            
            <div class="form-group">
                <label for="senha">Senha:</label>
                <div class="password-input"><input type="password" id="senha" name="senha" autocomplete="new-password" required><button type="button" data-toggle-password="senha">Ver</button></div><ul class="password-rules"><li data-rule="length">8 caracteres</li><li data-rule="upper">Uma maiúscula</li><li data-rule="lower">Uma minúscula</li><li data-rule="number">Um número</li><li data-rule="special">Um caractere especial</li></ul>
            </div>
            
            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha:</label>
                <div class="password-input"><input type="password" id="confirmar_senha" name="confirmar_senha" autocomplete="new-password" required><button type="button" data-toggle-password="confirmar_senha">Ver</button></div><small id="password-match">As senhas ainda não coincidem.</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
        
        <p>Já tem conta? <a href="/login.php">Faça login aqui</a></p>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
