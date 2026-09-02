<?php
// includes/functions.php
// Funções utilitárias

require_once __DIR__ . '/../config/database.php';

function sanitizar($dados) {
    return htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
}

function validar_email($email) {
    $email = mb_strtolower(trim($email));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    [$local, $dominio] = explode('@', $email, 2);
    $regras = [
        'gmail.com' => '/^[a-z0-9.]+$/',
        'googlemail.com' => '/^[a-z0-9.]+$/',
        'outlook.com' => '/^[a-z0-9._-]+$/',
        'hotmail.com' => '/^[a-z0-9._-]+$/',
        'yahoo.com' => '/^[a-z0-9._-]+$/',
        'yahoo.com.br' => '/^[a-z0-9._-]+$/',
    ];
    return !isset($regras[$dominio]) || (bool)preg_match($regras[$dominio], $local);
}

function normalizar_email($email) { return mb_strtolower(trim((string)$email)); }

function normalizar_nome($nome) {
    $nome = preg_replace('/\s+/u', ' ', trim((string)$nome));
    $conectivos = ['da','das','de','do','dos','e'];
    return implode(' ', array_map(function($parte) use ($conectivos) {
        $minuscula = mb_strtolower($parte, 'UTF-8');
        return in_array($minuscula, $conectivos, true) ? $minuscula : mb_convert_case($minuscula, MB_CASE_TITLE, 'UTF-8');
    }, explode(' ', $nome)));
}

function normalizar_telefone_br($telefone) {
    $digitos = preg_replace('/\D/', '', (string)$telefone);
    if (str_starts_with($digitos, '55') && strlen($digitos) >= 12) $digitos = substr($digitos, 2);
    if (strlen($digitos) >= 11 && $digitos[0] === '0') $digitos = substr($digitos, 1);
    return substr($digitos, 0, 11);
}

function formatar_telefone_formulario($telefone) {
    $n = normalizar_telefone_br($telefone);
    if (strlen($n) === 11) return sprintf('(%s) %s-%s', substr($n,0,2), substr($n,2,5), substr($n,7));
    if (strlen($n) === 10) return sprintf('(%s) %s-%s', substr($n,0,2), substr($n,2,4), substr($n,6));
    return $n;
}

function senha_forte($senha) {
    return strlen($senha) >= 8 && preg_match('/[A-Z]/', $senha) && preg_match('/[a-z]/', $senha) && preg_match('/\d/', $senha) && preg_match('/[^A-Za-z0-9]/', $senha);
}

function validar_cpf($cpf) {
    $cpf = preg_replace('/\D/', '', (string)$cpf);
    if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) return false;
    for ($t=9; $t<11; $t++) { $soma=0; for ($i=0; $i<$t; $i++) $soma += (int)$cpf[$i] * (($t+1)-$i); if ((int)$cpf[$t] !== ((10*$soma)%11)%10) return false; }
    return true;
}

function validar_cnpj($cnpj) {
    $cnpj = mb_strtoupper(preg_replace('/[^A-Z0-9]/i','',(string)$cnpj));
    if (strlen($cnpj)!==14 || !preg_match('/^[A-Z0-9]{12}[0-9]{2}$/',$cnpj)) return false;
    $calcular=function($base,$pesos){$soma=0;foreach(str_split($base) as $i=>$char)$soma+=(ord($char)-48)*$pesos[$i];$resto=$soma%11;return $resto<2?0:11-$resto;};
    $d1=$calcular(substr($cnpj,0,12),[5,4,3,2,9,8,7,6,5,4,3,2]);$d2=$calcular(substr($cnpj,0,12).$d1,[6,5,4,3,2,9,8,7,6,5,4,3,2]);return substr($cnpj,-2)===(string)$d1.(string)$d2;
}

function limite_contas_cpf_atingido($cpf) { global $mysqli;$stmt=$mysqli->prepare('SELECT COUNT(*) total FROM usuarios WHERE cpf=?');$stmt->bind_param('s',$cpf);$stmt->execute();return (int)$stmt->get_result()->fetch_assoc()['total']>=2; }

function mascarar_contato($valor, $visiveis=4) { $valor=(string)$valor; return mb_substr($valor,0,$visiveis).str_repeat('•',max(4,mb_strlen($valor)-$visiveis)); }

function hash_senha($senha) {
    return password_hash($senha, PASSWORD_BCRYPT);
}

function verificar_senha($senha, $hash) {
    return password_verify($senha, $hash);
}

function executar_query($sql, $tipos = '', $valores = []) {
    global $mysqli;
    
    if ($tipos && $valores) {
        $stmt = $mysqli->prepare($sql);
        if (!$stmt) {
            return ['erro' => 'Erro na preparação: ' . $mysqli->error];
        }
        $stmt->bind_param($tipos, ...$valores);
        $stmt->execute();
        return $stmt;
    } else {
        return $mysqli->query($sql);
    }
}

function pegar_usuario($id) {
    global $mysqli;
    $sql = 'SELECT id, nome, email, telefone, cpf, foto_perfil, tipo FROM usuarios WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function pegar_servicos() {
    global $mysqli;
    $sql = 'SELECT * FROM servicos WHERE ativo = 1 ORDER BY nome';
    $result = $mysqli->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function pegar_perfil_profissional_por_usuario($usuario_id) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT p.*, u.nome, u.email, u.telefone FROM profissionais p JOIN usuarios u ON u.id=p.usuario_id WHERE p.usuario_id=? LIMIT 1');
    $stmt->bind_param('i', $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function pegar_perfil_profissional($id) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT p.*, u.nome, u.telefone FROM profissionais p JOIN usuarios u ON u.id=p.usuario_id WHERE p.id=? AND p.ativo=1 LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function pegar_conversa_autorizada($conversa_id, $usuario_id) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT c.*,p.usuario_id AS profissional_usuario_id,p.titulo_profissional,u_cliente.nome AS cliente_nome,u_prof.nome AS profissional_nome FROM conversas c JOIN profissionais p ON p.id=c.profissional_id JOIN usuarios u_cliente ON u_cliente.id=c.cliente_id JOIN usuarios u_prof ON u_prof.id=p.usuario_id WHERE c.id=? AND (c.cliente_id=? OR p.usuario_id=?) LIMIT 1');
    $stmt->bind_param('iii', $conversa_id, $usuario_id, $usuario_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function pegar_todos_servicos() {
    global $mysqli;
    $result = $mysqli->query('SELECT * FROM servicos ORDER BY criado_em DESC, nome');
    return $result->fetch_all(MYSQLI_ASSOC);
}

function token_csrf() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function validar_csrf($token) {
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

function url_interna_segura($url, $padrao = '/') {
    if (!is_string($url) || $url === '' || $url[0] !== '/' || str_starts_with($url, '//')) return $padrao;
    return $url;
}

function configuracoes_site() {
    static $configuracoes = null;
    global $mysqli;
    if ($configuracoes !== null) return $configuracoes;
    $configuracoes = [];
    $resultado = $mysqli->query('SELECT chave, valor FROM configuracoes_site');
    if ($resultado) {
        foreach ($resultado->fetch_all(MYSQLI_ASSOC) as $item) $configuracoes[$item['chave']] = $item['valor'];
    }
    return $configuracoes;
}

function config_site($chave, $padrao = '') {
    $configuracoes = configuracoes_site();
    return $configuracoes[$chave] ?? $padrao;
}

function markdown_inline_seguro($texto) {
    $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
    $texto = preg_replace('/`([^`]+)`/', '<code>$1</code>', $texto);
    $texto = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $texto);
    $texto = preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $texto);
    return $texto;
}

function renderizar_markdown($markdown) {
    $linhas = preg_split('/\R/', (string)$markdown);
    $html = '';
    $lista_aberta = false;
    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if (preg_match('/^[-*]\s+(.+)$/', $linha, $item)) {
            if (!$lista_aberta) { $html .= '<ul>'; $lista_aberta = true; }
            $html .= '<li>' . markdown_inline_seguro($item[1]) . '</li>';
            continue;
        }
        if ($lista_aberta) { $html .= '</ul>'; $lista_aberta = false; }
        if ($linha === '') continue;
        if (preg_match('/^(#{1,3})\s+(.+)$/', $linha, $titulo)) {
            $nivel = strlen($titulo[1]) + 1;
            $html .= "<h{$nivel}>" . markdown_inline_seguro($titulo[2]) . "</h{$nivel}>";
        } else {
            $html .= '<p>' . markdown_inline_seguro($linha) . '</p>';
        }
    }
    if ($lista_aberta) $html .= '</ul>';
    return $html;
}

function pegar_servico($id) {
    global $mysqli;
    $sql = 'SELECT * FROM servicos WHERE id = ? AND ativo = 1 AND pausado = 0';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function redirecionar($url) {
    header("Location: $url");
    exit;
}

function mensagem_sucesso($msg) {
    $_SESSION['sucesso'] = $msg;
}

function mensagem_erro($msg) {
    $_SESSION['erro'] = $msg;
}

function exibir_mensagens() {
    if (isset($_SESSION['sucesso'])) {
        echo '<div class="alerta alerta-sucesso">' . sanitizar($_SESSION['sucesso']) . '</div>';
        unset($_SESSION['sucesso']);
    }
    
    if (isset($_SESSION['erro'])) {
        echo '<div class="alerta alerta-erro">' . sanitizar($_SESSION['erro']) . '</div>';
        unset($_SESSION['erro']);
    }
}
?>
