<?php

$ano = date("Y");

// Busca as preferencias do usuario
$sqlPreferencias = $conectar->prepare("SELECT cartoes_id FROM preferencias WHERE usuarios_id = ? ");
$sqlPreferencias->bind_param('i', $usuarioID);
$sqlPreferencias->execute();
$sqlPreferencias->store_result();
$sqlPreferencias->bind_result($cartao_principal);
$sqlPreferencias->fetch();

// Busca dados para o grafico - anual
$sqlGraDespesa = $conectar->prepare("
	SELECT SUM(valor) as valor, mes
	FROM view_copag
	WHERE ano = ?
	AND usuarios_id = ?
	GROUP BY mes ") or die (mysqli_error($conectar));
$sqlGraDespesa->bind_param('ii', $ano, $usuarioID);
$sqlGraDespesa->execute();
$sqlGraDespesa->store_result();
$sqlGraDespesa->bind_result($valor, $mes);

// Busca dados para o grafico - cartao de credito
$sqlGraCartao = $conectar->prepare("
	SELECT SUM(valor) as valor, mes
	FROM view_copag
	WHERE ano = ?
	AND cartoes_id = ?
	AND usuarios_id = ?
	GROUP BY mes ") or die (mysqli_error($conectar));
$sqlGraCartao->bind_param('iii', $ano, $cartao_principal, $usuarioID);
$sqlGraCartao->execute();
$sqlGraCartao->store_result();
$sqlGraCartao->bind_result($valor, $mes);

?>