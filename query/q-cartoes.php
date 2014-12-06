<?php

// Inclui eventos modal
include "q-modal.php";

// Evento pagar cartao
if(isset($_POST['btnPagar']) && $_POST['btnPagar'] == 1){

	$array = explode('/', $_POST['data_pagamento']);
	$data_pagamento = "$array[2]-$array[1]-$array[0]";
	$cartoes_id = (int)$_POST['cartoes_id'];
	$ano = (int)$_POST['ano'];
	$mes = (int)$_POST['mes'];

	$sqlPagar = $conectar->prepare("
		SELECT parcelas_id, copag_id
		FROM view_copag
		WHERE cartoes_id IS NOT NULL
		AND mes = ?
		AND ano = ?
		AND cartoes_id = ?
		AND usuarios_id = ?
		") or die (mysqli_error($conectar));
	$sqlPagar->bind_param('iiii', $mes, $ano, $cartoes_id, $usuarioID);
	$sqlPagar->execute();
	$sqlPagar->store_result();
	$sqlPagar->bind_result($parcelas_id, $copag_id);

	while($sqlPagar->fetch()){
		// Update tabela parcelas... conta paga
		$update = $conectar->prepare("UPDATE parcelas SET conta_paga = 'S', data_pagamento = ? WHERE ano = ? AND mes = ? AND copag_id = ? AND id = ? ") or die (mysqli_error($conectar));
		$update->bind_param('siiii', $data_pagamento, $ano, $mes, $copag_id, $parcelas_id);
		$update->execute();
	}

} // fim do if

// Busca as preferencias do usuario
$sqlPreferencias = $conectar->prepare("SELECT cartoes_id FROM preferencias WHERE usuarios_id = ? ");
$sqlPreferencias->bind_param('i', $usuarioID);
$sqlPreferencias->execute();
$sqlPreferencias->store_result();
$sqlPreferencias->bind_result($cartao_principal);
$sqlPreferencias->fetch();

if(isset($_GET['slogan']) && isset($_GET['ano']) && isset($_GET['mes'])){
	$mes = (int)$_GET['mes'];
	$ano = (int)$_GET['ano'];
	$slogan = explode("-", $_GET['slogan']);
	$cartoes_id = $slogan[0];
	$slogan_cartao = $slogan[1];
} else{
	$mes = date("m");
	$ano = date("Y");
	$cartoes_id = $cartao_principal;
}

// Query busca despesas de cartões 
$sqlCartoes = $conectar->prepare("
	SELECT parcelas_id, copag_id, data_despesa, data_vencimento, descricao, valor, numero_parcela, total_parcela, parcelado
	FROM view_copag
	WHERE cartoes_id IS NOT NULL
	AND mes = ?
	AND ano = ?
	AND cartoes_id = ?
	AND usuarios_id = ?
	ORDER BY data_despesa
	") or die (mysqli_error($conectar));
$sqlCartoes->bind_param('iiii', $mes, $ano, $cartoes_id, $usuarioID);
$sqlCartoes->execute();
$sqlCartoes->store_result();
$sqlCartoes->bind_result($parcelas_id, $copag_id, $data_despesa, $data_vencimento, $descricao, $valor, $numero_parcela, $total_parcela, $parcelado);

// Busca todos os cartões
$sqlCartao = $conectar->prepare("SELECT id, descricao, slogan FROM cartoes WHERE usuarios_id = ? AND ativo = 1 ORDER BY descricao ");
$sqlCartao->bind_param('i', $usuarioID);
$sqlCartao->execute();
$sqlCartao->store_result();
$sqlCartao->bind_result($id, $descricao, $slogan);

// Busca informações do cartão
$sqlInfoCartao = $conectar->prepare("SELECT descricao, limite, data_vencimento, melhor_data, slogan FROM cartoes WHERE id = ? AND usuarios_id = ? ");
$sqlInfoCartao->bind_param('ii', $cartoes_id, $usuarioID);
$sqlInfoCartao->execute();
$sqlInfoCartao->store_result();
$sqlInfoCartao->bind_result($nome_descricao, $limite, $dia_vencimento, $melhor_data, $slogan_cartao);
$sqlInfoCartao->fetch();

// Soma o valor disponivel para compra
$sqlDisponivel = $conectar->prepare("
	SELECT SUM(valor)
	FROM view_copag
	WHERE cartoes_id = ?
	AND usuarios_id = ?
	AND conta_paga = 'N'
	");
$sqlDisponivel->bind_param('ii', $cartoes_id, $usuarioID);
$sqlDisponivel->execute();
$sqlDisponivel->store_result();
$sqlDisponivel->bind_result($soma_cartao);
$sqlDisponivel->fetch();

// Paginacao - faturas
$data = $ano."-".$mes;
$array = explode('-', strftime('%Y-%m', strtotime('-1 month' , strtotime($data))));
$fat_ano_ant = $array[0]; $fat_mes_ant = $array[1];
$array = explode('-', strftime('%Y-%m', strtotime('+1 month' , strtotime($data))));
$fat_ano_pro = $array[0]; $fat_mes_pro = $array[1];

// Verifica se o cartão já foi pago
$sqlCartaoPago = $conectar->prepare("
	SELECT COUNT(conta_paga)
	FROM view_copag
	WHERE cartoes_id IS NOT NULL
	AND mes = ?
	AND ano = ?
	AND cartoes_id = ?
	AND usuarios_id = ?
	AND conta_paga = 'S'
	") or die (mysqli_error($conectar));
$sqlCartaoPago->bind_param('iiii', $mes, $ano, $cartoes_id, $usuarioID);
$sqlCartaoPago->execute();
$sqlCartaoPago->store_result();
$sqlCartaoPago->bind_result($conta_paga_count);
$sqlCartaoPago->fetch();

?>