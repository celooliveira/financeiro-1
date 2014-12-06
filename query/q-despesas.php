<?php

// Inclui eventos modal
include "q-modal.php";

// Pega a variavel enviada por GET
if(isset($_GET['ano']) && isset($_GET['mes'])){
	$mes = (int)$_GET['mes'];
	$ano = (int)$_GET['ano'];
} else{
	$mes = date("m");
	$ano = date("Y");
}

// Busca as depesas mensais
$sqlDespesas = $conectar->prepare("
	SELECT parcelas_id, descricao, data_vencimento, data_pagamento, valor, conta_paga, desc_tipo_despesa, desc_forma_pagamento, desc_cartao, parcelado, numero_parcela, total_parcela
	FROM view_copag
	WHERE mes = ?
	AND ano = ?
	AND usuarios_id = ?
	AND cartoes_id IS NULL
	ORDER BY data_vencimento
	") or die (mysqli_error($conectar));
$sqlDespesas->bind_param('iii', $mes, $ano, $usuarioID);
$sqlDespesas->execute();
$sqlDespesas->store_result();
$sqlDespesas->bind_result($parcelas_id, $descricao, $data_vencimento, $data_pagamento, $valor, $conta_paga, $desc_despesa, $desc_forma_pagamento, $desc_cartao, $parcelado, $numero_parcela, $total_parcela);

// Query busca as despesas com cartão de credito
$sqlCartoes = $conectar->prepare("			
	SELECT data_vencimento, data_pagamento, SUM(valor), conta_paga, cartoes_id, slogan, desc_cartao
	FROM view_copag
	WHERE mes = ?
	AND ano = ?
	AND usuarios_id = ?
	GROUP BY cartoes_id
	HAVING cartoes_id IS NOT NULL
	ORDER BY data_vencimento") or die (mysqli_error($conectar));
$sqlCartoes->bind_param('ssi', $mes, $ano, $usuarioID);
$sqlCartoes->execute();
$sqlCartoes->store_result();
$sqlCartoes->bind_result($data_vencimento, $data_pagamento, $valor, $conta_paga, $cartoes_id, $slogan, $desc_cartao);

// Soma despesas gerais
$sqlDespesasGerais = $conectar->prepare("
	SELECT SUM(valor)
	FROM view_copag
	WHERE mes = ?
	AND ano = ?
	AND usuarios_id = ?
	AND cartoes_id IS NULL
	GROUP BY mes") or die (mysqli_error($conectar));
$sqlDespesasGerais->bind_param('iii', $mes, $ano, $usuarioID);
$sqlDespesasGerais->execute();
$sqlDespesasGerais->store_result();
$sqlDespesasGerais->bind_result($soma_despesas_gerais);
$sqlDespesasGerais->fetch();

// Soma total das despesas com cartões de credito
$sqlDespesasCartao = $conectar->prepare("
	SELECT SUM(valor)
	FROM view_copag
	WHERE mes = ?
	AND ano = ?
	AND usuarios_id = ?
	AND cartoes_id IS NOT NULL
	GROUP BY mes") or die (mysqli_error($conectar));
$sqlDespesasCartao->bind_param('iii', $mes, $ano, $usuarioID);
$sqlDespesasCartao->execute();
$sqlDespesasCartao->store_result();
$sqlDespesasCartao->bind_result($soma_despesas_cartao);
$sqlDespesasCartao->fetch();

// Soma das despesas mensais
$soma_despesa_mensal = $soma_despesas_gerais + $soma_despesas_cartao;

// Calcula o mes e ano anterior
if($mes == 1){
	$mes_ant = 12;
	$ano_ant = $ano - 1;
}
else{
	$mes_ant = $mes - 1;
	$ano_ant = $ano;
}

// Busca valor total de despesas gerais	do mes anterior		
$sqlDespesasAnterior = $conectar->prepare("
	SELECT SUM(valor) as valor
	FROM view_copag AS c
	WHERE c.mes = ?
	AND c.ano = ?
	AND c.usuarios_id = ?
	GROUP BY c.mes") or die (mysqli_error($conectar));
$sqlDespesasAnterior->bind_param('ssi', $mes_ant, $ano_ant, $usuarioID);
$sqlDespesasAnterior->execute();
$sqlDespesasAnterior->store_result();
$sqlDespesasAnterior->bind_result($soma_mes_anterior);
$sqlDespesasAnterior->fetch();

// Resultado da diferença do mes anterior
$soma_mes_anterior = $soma_mes_anterior - $soma_despesa_mensal;

if($soma_mes_anterior < 0){
	$alert = 'danger'; $icon = 'warning';
	$msg = 'Atenção.<br /> Você gastou R$ ' . number_format($soma_mes_anterior, 2, ',', '.'). ' a mais do que o mes anterior.';
} else{
	$alert = 'success'; $icon = 'check';
	$msg = 'Muito bem.<br /> Você economizou R$ ' . number_format($soma_mes_anterior, 2, ',', '.') . ' referente ao mes anterior.';
}

?>