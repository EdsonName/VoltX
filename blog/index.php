<?php
// blog/index.php
// Lista de posts do blog
$titulo_pagina = 'Blog';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$categoria = isset($_GET['categoria']) ? sanitizar($_GET['categoria']) : null;

$sql = 'SELECT * FROM posts_blog WHERE publicado = 1';
if ($categoria) {
    $sql .= " AND categoria = '" . $mysqli->real_escape_string($categoria) . "'";
}
$sql .= ' ORDER BY criado_em DESC';

$resultado = $mysqli->query($sql);
$posts = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Blog</h1>
    
    <div class="posts-lista">
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post-item">
                    <h2><a href="/blog/post.php?id=<?php echo $post['id']; ?>"><?php echo sanitizar($post['titulo']); ?></a></h2>
                    <p class="meta">
                        <small><?php echo date('d/m/Y', strtotime($post['criado_em'])); ?></small>
                        <?php if ($post['categoria']): ?>
                            | <small><a href="?categoria=<?php echo urlencode($post['categoria']); ?>"><?php echo $post['categoria']; ?></a></small>
                        <?php endif; ?>
                    </p>
                    <p><?php echo sanitizar(substr($post['conteudo'], 0, 200)); ?>...</p>
                    <a href="/blog/post.php?id=<?php echo $post['id']; ?>" class="btn">Ler Mais</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum post encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
