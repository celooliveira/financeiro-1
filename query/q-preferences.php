<?php

$display1 = 'hide';
$display2 = 'hide';

// Evento salvar preferencias de uso
if(isset($_POST['btnSavePreferences']) && $_POST['btnSavePreferences'] == 1){

	$aviso_vencimento = $_POST['aviso_vencimento'];
	$relatorio_mensal = $_POST['relatorio_mensal'];
	$dias_vencimento = (int)$_POST['dias_vencimento'];

	if(!isset($aviso_vencimento))
		$aviso_vencimento = 'N';

	if(!isset($relatorio_mensal))
		$relatorio_mensal = 'N';

	// Update
	$updatePref = $conectar->prepare("UPDATE preferencias SET aviso_vencimento = ?, relatorio_mensal = ?, dias_vencimento = ? WHERE usuarios_id = ? ");
	$updatePref->bind_param('ssii', $aviso_vencimento, $relatorio_mensal, $dias_vencimento, $usuarioID);
	$updatePref->execute();

	if($updatePref == true){
		$msg1 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong>. Alterações salvas com sucesso.</p>';
		$display1 = 'show'; $alert1 = 'success';
	}

} // fim do if


// Busca as preferencias do usuario
$sqlPreferencias = $conectar->prepare("SELECT dias_vencimento, cartoes_id, aviso_vencimento, relatorio_mensal FROM preferencias WHERE usuarios_id = ? ");
$sqlPreferencias->bind_param('i', $usuarioID);
$sqlPreferencias->execute();
$sqlPreferencias->store_result();
$sqlPreferencias->bind_result($dias_vencimento, $cartao_principal, $aviso_vencimento, $relatorio_mensal);
$sqlPreferencias->fetch();

// Busca cartoes do usuario
$sqlCartoes = $conectar->prepare("
	SELECT id, descricao, codigo, melhor_data, data_vencimento, limite, ativo
	FROM cartoes
	WHERE usuarios_id = ?
	ORDER BY descricao") or die (mysqli_error($conectar));
$sqlCartoes->bind_param('i', $usuarioID);
$sqlCartoes->execute();
$sqlCartoes->store_result();
$sqlCartoes->bind_result($id, $cartao, $codigo, $melhor_data, $data_vencimento, $limite, $ativo);

?>