<?php
// blog/post.php
// Exibir um post completo
$titulo_pagina = 'Post';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = 'SELECT * FROM posts_blog WHERE id = ? AND publicado = 1';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$resultado = $stmt->get_result();
$post = $resultado->fetch_assoc();

if (!$post) {
    header('Location: /blog/');
    exit;
}
?>

<div class="container">
    <a href="/blog/" class="btn">← Voltar ao Blog</a>
    
    <article class="post-completo">
        <h1><?php echo sanitizar($post['titulo']); ?></h1>
        <p class="meta">
            <?php echo date('d/m/Y \à\s H:i', strtotime($post['criado_em'])); ?>
            <?php if ($post['categoria']): ?>
                | <a href="/blog/?categoria=<?php echo urlencode($post['categoria']); ?>"><?php echo sanitizar($post['categoria']); ?></a>
            <?php endif; ?>
        </p>
        
        <div class="conteudo">
            <?php echo nl2br(sanitizar($post['conteudo'])); ?>
        </div>
    </article>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
