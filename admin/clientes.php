<?php
// admin/clientes.php
// Gerenciar clientes
$titulo_pagina = 'Gerenciar Clientes';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

verificarAdmin();

$sql = 'SELECT * FROM usuarios WHERE tipo = "cliente" ORDER BY nome';
$resultado = $mysqli->query($sql);
$clientes = $resultado->fetch_all(MYSQLI_ASSOC);
?>

<div class="container">
    <h1>Gerenciar Clientes</h1>
    
    <table class="tabela">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Cadastrado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo sanitizar($cliente['nome']); ?></td>
                    <td><?php echo sanitizar($cliente['email']); ?></td>
                    <td><?php echo sanitizar($cliente['telefone']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($cliente['criado_em'])); ?></td>
                    <td>
                        <a href="#" class="btn-small">Ver</a>
                        <a href="#" class="btn-small btn-danger">Deletar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
