<?php
// Compatibilidade com links antigos: os detalhes agora abrem no verso do card.
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$destino = $id > 0 ? '/servicos.php?detalhes=' . $id : '/servicos.php';
header('Location: ' . $destino, true, 302);
exit;
