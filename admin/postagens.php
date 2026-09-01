<?php
// admin/postagens.php
// Gerenciar postagens do blog
$titulo_pagina = 'Gerenciar Postagens';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$sql = 'SELECT * FROM posts_blog ORDER BY criado_em DESC';
$resultado = $mysqli->query($sql);
$posts = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Gerenciar Postagens</h1>
    
    <a href="#" class="btn btn-primary">Nova Postagem</a>
    
    <table class="tabela">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Data</th>
                <th>Publicado</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?php echo sanitizar($post['titulo']); ?></td>
                    <td><?php echo sanitizar($post['categoria'] ?? '-'); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($post['criado_em'])); ?></td>
                    <td><?php echo $post['publicado'] ? 'Sim' : 'Não'; ?></td>
                    <td>
                        <a href="#" class="btn-small">Editar</a>
                        <a href="#" class="btn-small btn-danger">Deletar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
