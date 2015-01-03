<?php

// Pega a variavel enviada por GET
if(isset($_GET['ano']) && isset($_GET['mes'])){
	$mes = (int)$_GET['mes'];
	$ano = (int)$_GET['ano'];
} else{
	$mes = date("m");
	$ano = date("Y");
}

// Busca as preferencias do usuario
$sqlPreferencias = $conectar->prepare("SELECT dias_vencimento, cartoes_id FROM preferencias WHERE usuarios_id = ? ");
$sqlPreferencias->bind_param('i', $usuarioID);
$sqlPreferencias->execute();
$sqlPreferencias->store_result();
$sqlPreferencias->bind_result($dias_vencimento, $cartao_principal);
$sqlPreferencias->fetch();


// Calculando as depesas a vencer...
$data_atual = date('Y-m-d', mktime(0,0,0,date('m'),date('d') - 5,date('Y')));
$data_vencer = date('Y-m-d', mktime(0,0,0,date('m'),date('d') + $dias_vencimento,date('Y')));

// Busca as depesas mensais
$sqlDespesas = $conectar->prepare("
	SELECT parcelas_id, descricao, data_vencimento, data_pagamento, valor, conta_paga, desc_cartao, parcelado, numero_parcela, total_parcela
	FROM view_copag
	WHERE data_vencimento BETWEEN ? AND ?
	AND conta_paga = 'N'
	AND usuarios_id = ?
	AND cartoes_id IS NULL
	ORDER BY data_vencimento, descricao
	") or die (mysqli_error($conectar));
$sqlDespesas->bind_param('ssi', $data_atual, $data_vencer, $usuarioID);
$sqlDespesas->execute();
$sqlDespesas->store_result();
$sqlDespesas->bind_result($parcelas_id, $descricao, $data_vencimento, $data_pagamento, $valor, $conta_paga, $desc_cartao, $parcelado, $numero_parcela, $total_parcela);

// Query busca as despesas com cartão de credito
$sqlCartoes = $conectar->prepare("			
	SELECT data_vencimento, data_pagamento, SUM(valor), conta_paga, cartoes_id, slogan, desc_cartao
	FROM view_copag
	WHERE data_vencimento BETWEEN ? AND ?
	AND conta_paga = 'N'
	AND usuarios_id = ?
	GROUP BY cartoes_id
	HAVING cartoes_id IS NOT NULL
	ORDER BY data_vencimento") or die (mysqli_error($conectar));
$sqlCartoes->bind_param('ssi', $data_atual, $data_vencer, $usuarioID);
$sqlCartoes->execute();
$sqlCartoes->store_result();
$sqlCartoes->bind_result($data_vencimento, $data_pagamento, $valor, $conta_paga, $cartoes_id, $slogan, $desc_cartao);

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

// Busca nome do cartao principal
$sqlNomeCartao = $conectar->prepare("SELECT descricao FROM cartoes WHERE id = ?");
$sqlNomeCartao->bind_param('i', $cartao_principal);
$sqlNomeCartao->execute();
$sqlNomeCartao->store_result();
$sqlNomeCartao->bind_result($descricao_cartao);
$sqlNomeCartao->fetch();

?>