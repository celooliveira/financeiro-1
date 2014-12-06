<?php

// Evento remover - janela modal
if(isset($_POST['btnRemoverModal'])){

	$opt = (int)$_POST['btnRemoverModal'];
	$copag_id = (int)$_POST['copag_id'];
	$parcelas_id = (int)$_POST['parcelas_id'];

	switch ($opt) {

		// Conta parcelada
		case 1:
			$remove = $conectar->prepare("DELETE FROM copag WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$remove->bind_param('ii', $copag_id, $usuarioID);
			$remove->execute();	
			break;

		// Conta fixa - para o mes atual
		case 2:
			$remove = $conectar->prepare("DELETE FROM parcelas WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
			$remove->bind_param('ii', $parcelas_id, $copag_id);
			$remove->execute();				
			break;

		// Conta fixa - todos os meses
		case 3:
			$remove = $conectar->prepare("DELETE FROM copag WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$remove->bind_param('ii', $copag_id, $usuarioID);
			$remove->execute();	
			break;

		// Conta normal - não fixa e parcelada
		case 4:
			$remove = $conectar->prepare("DELETE FROM copag WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$remove->bind_param('ii', $copag_id, $usuarioID);
			$remove->execute();	
			break;
		
	} // fim do switch

} // fim evento if

// Evento editar - janela modal
if(isset($_POST['btnSalvarModal']) && $_POST['btnSalvarModal'] == 1){

	//echo "<pre>";
	//print_r($_POST);break;

	$copag_id = (int)$_POST['copag_id'];
	$parcelas_id = (int)$_POST['parcelas_id'];
	$tipo_despesa_id = (int)$_POST['tipo_despesa_id'];
	$data_despesa = $_POST['data_despesa'];
	$data_vencimento = $_POST['data_vencimento'];
	$descricao = addslashes($_POST['descricao']);
	$valor = $_POST['valor'];
	$altera_valor = $_POST['altera_valor'];
	$conta_paga = $_POST['conta_paga'];
	$parcelado = $_POST['parcelado'];
	$numero_parcela = (int)$_POST['numero_parcela'];
	//$data_pagamento = $_POST['data_pagamento'];
	$forma_pagamento_id = (int)$_POST['forma_pagamento_id'];
	$obs = addslashes($_POST['obs']);

	// Converte o valor para salvar no banco
	$source = array('.', ','); 
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $valor);

	// Data de vencimento
	$array = explode("/", $data_vencimento);				
	$data_vencimento = "$array[2]-$array[1]-$array[0]";
	$mes = $array[1];
	$ano = $array[2];

	// Data da despesa
	$array = explode("/", $data_despesa);				
	$data_despesa = "$array[2]-$array[1]-$array[0]";

	// Data do pagamento
	if($_POST['data_pagamento'] != ''){
		$array = explode("/", $_POST['data_pagamento']);				
		$data_pagamento = "$array[2]-$array[1]-$array[0]";
	}

	// Conta paga
	if($conta_paga != 'S'){
		$conta_paga = 'N';
		$data_pagamento = '';
	}

	if($conta_paga == 'S' && $data_pagamento == ''){
		$conta_paga = 'N';
		$data_pagamento = '';
	}

	// Conta parcelada
	if($parcelado == 'S'){

		// Caso parcela seja igual a 1
		if($numero_parcela == 1){

			// Update na tabela copag
			$update = $conectar->prepare("UPDATE copag SET tipo_despesa_id = ?, forma_pagamento_id = ?, data_despesa = ?, descricao = ? WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('iissii', $tipo_despesa_id, $forma_pagamento_id, $data_despesa, $descricao, $copag_id, $usuarioID);
			$update->execute();

			// Update na tabela parcelas
			$update = $conectar->prepare("UPDATE parcelas SET data_vencimento = ?, ano = ?, mes = ?, obs = ?, conta_paga = ?, data_pagamento = ? WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('siisssii', $data_vencimento, $ano, $mes, $obs, $conta_paga, $data_pagamento, $parcelas_id, $copag_id);
			$update->execute();

			// Opção para alterar o valor apenas para o mês atual
			if($altera_valor == 1){				
				// Update na tabela parcelas
				$update = $conectar->prepare("UPDATE parcelas SET valor = ? WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
				$update->bind_param('sii', $valor, $parcelas_id, $copag_id);
				$update->execute();
			} else{
				// Update na tabela parcelas
				$update = $conectar->prepare("UPDATE parcelas SET valor = ? WHERE copag_id = ? ") or die (mysqli_error($conectar));
				$update->bind_param('si', $valor, $copag_id);
				$update->execute();
			}

		} // fim do if
		else{
			// Update na tabela parcelas
			$update = $conectar->prepare("UPDATE parcelas SET valor = ?, conta_paga = ?, data_pagamento = ?, obs = ? WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('ssssii', $valor, $conta_paga, $data_pagamento, $obs, $parcelas_id, $copag_id);
			$update->execute();
		}

	} // fim if parcelado
	else{ // Conta não parcelada e/ou fixa

		//echo "$tipo_despesa_id - $forma_pagamento_id - $data_despesa - $descricao - $copag_id - $usuarioID";break;

		// Caso seja cartão de credito...
		if($forma_pagamento_id == 0){
			// Update na tabela copag
			$update = $conectar->prepare("UPDATE copag SET tipo_despesa_id = ?, data_despesa = ?, descricao = ? WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('issii', $tipo_despesa_id, $data_despesa, $descricao, $copag_id, $usuarioID);
			$update->execute();

			// Update na tabela parcelas
			$update = $conectar->prepare("UPDATE parcelas SET valor = ?, obs = ?, conta_paga = ?, data_pagamento = ? WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('ssssii', $valor, $obs, $conta_paga, $data_pagamento, $parcelas_id, $copag_id);
			$update->execute();	
		} else{
			// Update na tabela copag
			$update = $conectar->prepare("UPDATE copag SET tipo_despesa_id = ?, forma_pagamento_id = ?, data_despesa = ?, descricao = ? WHERE id = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('iissii', $tipo_despesa_id, $forma_pagamento_id, $data_despesa, $descricao, $copag_id, $usuarioID);
			$update->execute();

			// Update na tabela parcelas
			$update = $conectar->prepare("UPDATE parcelas SET valor = ?, data_vencimento = ?, mes = ?, ano = ?, obs = ?, conta_paga = ?, data_pagamento = ? WHERE id = ? AND copag_id = ? ") or die (mysqli_error($conectar));
			$update->bind_param('ssiisssii', $valor, $data_vencimento, $mes, $ano, $obs, $conta_paga, $data_pagamento, $parcelas_id, $copag_id);
			$update->execute();
		}

		

	}
	
} // fim evento btnmodal

?>