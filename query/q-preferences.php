<?php

$display1 = 'hide';
$display2 = 'hide';
$tab = 1;

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

	$tab = 1;

} // fim do if


// Evento alterar cartao principal
if(isset($_POST['btnAlteraCartaoPrincipal']) && $_POST['btnAlteraCartaoPrincipal'] == 1){
	
	$cartao_principal = (int)$_POST['cartao_principal'];

	// Update
	$update = $conectar->prepare("UPDATE preferencias SET cartoes_id = ? WHERE usuarios_id = ? ");
	$update->bind_param('ii', $cartao_principal, $usuarioID);
	$update->execute();

	if($update == true){
		$msg2 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong>. Cartão principal foi alterado com sucesso.</p>';
		$display2 = 'show'; $alert2 = 'success';
	}

	$tab = 2;
} // fim do if

// Evento add novo cartao
if(isset($_POST['btnAddNovoCartao']) && $_POST['btnAddNovoCartao'] == 1){

	echo $cartao = $_POST['cartao'];

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
$sqlCartoes->bind_result($id, $descricao, $codigo, $melhor_data, $data_vencimento, $limite, $ativo);

?>