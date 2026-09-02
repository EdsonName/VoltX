<?php
require_once __DIR__.'/includes/auth.php';require_once __DIR__.'/includes/functions.php';header('Content-Type: application/json; charset=utf-8');
if(!usuario_autenticado()){http_response_code(401);echo json_encode(['ok'=>false]);exit;}
$stmt=$mysqli->prepare('UPDATE usuarios SET ultima_atividade=NOW() WHERE id=?');$stmt->bind_param('i',$_SESSION['usuario_id']);$stmt->execute();echo json_encode(['ok'=>true]);
