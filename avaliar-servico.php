<?php
require_once __DIR__.'/includes/auth.php';require_once __DIR__.'/includes/functions.php';verificarLogin();
if($_SERVER['REQUEST_METHOD']!=='POST'||!validar_csrf($_POST['csrf_token']??'')){http_response_code(400);die('Requisição inválida.');}
if(($_SESSION['tipo_usuario']??'')!=='cliente'){mensagem_erro('Somente clientes podem avaliar serviços.');redirecionar('/servicos.php');}
$oferta=(int)($_POST['oferta_id']??0);$nota=(float)str_replace(',','.',$_POST['nota']??'');
if($nota<0||$nota>10){mensagem_erro('A nota deve ficar entre 0 e 10.');redirecionar('/servicos.php');}
$stmt=$mysqli->prepare('SELECT id FROM ofertas_profissionais WHERE id=? AND ativo=1');$stmt->bind_param('i',$oferta);$stmt->execute();if(!$stmt->get_result()->fetch_assoc()){mensagem_erro('Serviço indisponível.');redirecionar('/servicos.php');}
$stmt=$mysqli->prepare('INSERT INTO avaliacoes_servicos_profissionais(oferta_id,cliente_id,nota) VALUES(?,?,?) ON DUPLICATE KEY UPDATE nota=VALUES(nota)');$stmt->bind_param('iid',$oferta,$_SESSION['usuario_id'],$nota);$stmt->execute();
$stmt=$mysqli->prepare('UPDATE ofertas_profissionais o SET nota_media=(SELECT AVG(a.nota) FROM avaliacoes_servicos_profissionais a WHERE a.oferta_id=o.id),total_avaliacoes=(SELECT COUNT(*) FROM avaliacoes_servicos_profissionais a WHERE a.oferta_id=o.id) WHERE o.id=?');$stmt->bind_param('i',$oferta);$stmt->execute();mensagem_sucesso('Sua avaliação foi registrada.');redirecionar('/servicos.php');
