<?php

$display1 = 'hide';
$display2 = 'hide';
$display3 = 'hide';
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
		$msg1 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong> Alterações salvas com sucesso.</p>';
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
		$msg2 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong> Cartão principal foi alterado com sucesso.</p>';
		$display2 = 'show'; $alert2 = 'success';
	}

	$tab = 2;
} // fim do if

// Evento add novo cartao
if(isset($_POST['btnAddNovoCartao']) && $_POST['btnAddNovoCartao'] == 1){

	$cartao = addslashes($_POST['cartao']);
	$codigo = (int)$_POST['codigo'];
	$melhor_data = (int)$_POST['melhor_data'];
	$data_vencimento = (int)$_POST['data_vencimento'];
	$limite = $_POST['limite'];

	// Limite
	$source = array('.', ','); 
	$replace = array('', '.');
	$limite = str_replace($source, $replace, $limite);

	// Remove os acentos
	$slogan = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $cartao));

	// Prepara o slogan do cartao
	$source = array(' '); 
	$replace = array('-');
	$slogan = str_replace($source, $replace, strtolower($slogan));

	// Seta cartao como ativo
	$ativo = 1;

	// Verifica se ja existe um cartão cadastrado com o mesmo codigo (numero do cartão)
	$sqlVerificaCod = $conectar->prepare("SELECT codigo FROM cartoes WHERE codigo = ? AND usuarios_id = ? ");
	$sqlVerificaCod->bind_param('ii', $codigo, $usuarioID);
	$sqlVerificaCod->execute();
	$sqlVerificaCod->store_result();

	// Se não existir cadastra o novo cartão
	if($sqlVerificaCod->num_rows() == 0){

		$insert = $conectar->prepare("INSERT INTO cartoes VALUES ('',?,?,?,?,?,?,?,?)") or die (mysqli_error($conectar));
		$insert->bind_param('issiiisi', $usuarioID, $cartao, $slogan, $codigo, $melhor_data, $data_vencimento, $limite, $ativo);
		$insert->execute();

		if($insert == true){
			$msg2 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong> Seu novo cartão foi cadastrado com sucesso.</p>';
			$display2 = 'show'; $alert2 = 'success';
		}

	} else{
		$msg2 = '<p><strong><i class="fa fa-check fa-fw"></i> Ops!</strong> Já existe um cartão cadastrado com número XXXX-XXXX-XXXX-'. $codigo .'.</p>';
		$display2 = 'show'; $alert2 = 'danger';
	}

	$tab = 2;

} // fim do if

// Evento editar cartao
if(isset($_POST['btnEditCartao']) && $_POST['btnEditCartao'] == 1){

	$cartoes_id = (int)$_POST['cartoes_id'];
	$cartao = addslashes($_POST['cartao']);
	$codigo = (int)$_POST['codigo'];
	$melhor_data = (int)$_POST['melhor_data'];
	$data_vencimento = (int)$_POST['data_vencimento'];
	$limite = $_POST['limite'];

	// Limite
	$source = array('.', ','); 
	$replace = array('', '.');
	$limite = str_replace($source, $replace, $limite);

	// Remove os acentos
	$slogan = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $cartao));

	// Prepara o slogan do cartao
	$source = array(' '); 
	$replace = array('-');
	$slogan = str_replace($source, $replace, strtolower($slogan));

	// Update
	$update = $conectar->prepare("
		UPDATE cartoes
		SET descricao = ?, slogan = ?, codigo = ?,
		melhor_data = ?, data_vencimento = ?, limite = ?
		WHERE id = ? AND usuarios_id = ?" );
	$update->bind_param('ssiiiiii', $cartao, $slogan, $codigo, $melhor_data, $data_vencimento, $limite, $cartoes_id, $usuarioID);
	$update->execute();

	if($update == true){
		$msg2 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong> As alterações foram salvas com sucesso.</p>';
		$display2 = 'show'; $alert2 = 'success';
	}

	$tab = 2;


} // fim do if

// Evento cadastra tipo de despesa
if(isset($_POST['btnAddTipoDespesa']) && $_POST['btnAddTipoDespesa'] == 1){

	$descricao = addslashes($_POST['descricao']);

	// Insere
	$insert = $conectar->prepare("INSERT INTO tipo_despesa VALUES ('',?)");
	$insert->bind_param('s', $descricao);
	$insert->execute();

	// Pega o ultimo ID inserido
	$tipo_despesa_id = $insert->insert_id;

	$insert = $conectar->prepare("INSERT INTO usuarios_has_tipo_despesa VALUES (?,?)");
	$insert->bind_param('ii', $usuarioID, $tipo_despesa_id);
	$insert->execute();
	
	if($insert == true){
		$msg3 = '<p><strong><i class="fa fa-check fa-fw"></i> Feito!</strong> Cadastro realizado com sucesso.</p>';
		$display3 = 'show'; $alert3 = 'success';
	}

	$tab = 3;

} // fim do if

// Evento editar tipo de despesa
if(isset($_POST['btnEditarTipo']) && $_POST['btnEditarTipo'] == 1){

	$descricao = addslashes($_POST['descricao']);
	$tipo_despesa_id = (int)$_POST['tipo_despesa_id'];

	// Update
	$update = $conectar->prepare("UPDATE tipo_despesa SET descricao = ? WHERE id = ?");
	$update->bind_param('si', $descricao, $tipo_despesa_id);
	$update->execute();

	$tab = 3;

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

// Busca os tipos de despesas
$sqlTipo = $conectar->prepare("SELECT tipo_despesa_id, descricao FROM view_tipo_despesa WHERE usuarios_id = ? ORDER BY descricao ");
$sqlTipo->bind_param('i', $usuarioID);
$sqlTipo->execute();
$sqlTipo->store_result();
$sqlTipo->bind_result($tipo_despesa_id, $descricao);

?>