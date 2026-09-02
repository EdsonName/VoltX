<?php
// includes/functions.php
// Funções utilitárias

require_once __DIR__ . '/../config/database.php';

function sanitizar($dados) {
    return htmlspecialchars($dados, ENT_QUOTES, 'UTF-8');
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

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
    $sql = 'SELECT id, nome, email, telefone FROM usuarios WHERE id = ?';
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
