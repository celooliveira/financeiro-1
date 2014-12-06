<?php

$display = 'hide';

// Função para somar as parcelas das despesas
function SomarData($data, $dias, $meses, $ano){
    // A data deve estar no formato dd/mm/yyyy
    $data = explode("/", $data);
    $nova_data = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano) );
    return $nova_data;
}

// Evento cadastrar despesa
if(isset($_POST['btncadastrar']) && $_POST['btncadastrar'] == 1){

	//echo "<pre>";
	//print_r($_POST); 
	//break;
			

	$tipo_despesa_id = $_POST['tipo_despesa_id'];				// Tipo de despesa
	
	$array = explode("/", $_POST['data_despesa']);
	$data_despesa = "$array[2]-$array[1]-$array[0]";			// Data de despesa
	
	$array = explode("/", $_POST['data_vencimento']);
	$data_vencimento = "$array[2]-$array[1]-$array[0]"; 		// Data de vencimento
	$mes = $array[1]; $ano = $array[2];							// Mes - Ano
	
	$array = explode("/", $_POST['data_pagamento']);
	$data_pagamento = "$array[2]-$array[1]-$array[0]";			// Data de pagamento
	
	$descricao = addslashes($_POST['descricao']);				// Descricao
	$valor = $_POST['valor'];									// Valor
	$parcelado = $_POST['parcelado'];							// Parcelado
	$total_parcela = (int)$_POST['total_parcela'];				// Numero total de parcela
	$conta_fixa = $_POST['conta_fixa'];							// Parcela fixa
	$conta_paga = $_POST['conta_paga'];							// Conta paga
	$forma_pagamento_id = $_POST['forma_pagamento_id'];			// Forma de pagamento
	$cartoes_id = (int)$_POST['cartoes_id'];					// Cartao de credito/debito
	$obs = addslashes($_POST['obs']);							// Observações



	// Caso escolha pago não e data pgto sim, zera o conteudo da data pgto
	if($conta_paga == 'N')
		$data_pagamento = '';

	// Caso usuario escolha parcelado e conta fixa vai prevalecer a conta fixa
	if($conta_fixa == 'S')
		$parcelado = 'N';

	if(($descricao == "") || ($valor == "")){
		$alert = "danger"; $icon = "times";
		$msg = "<strong>Erro!</strong> Existem campos obrigatórios a serem preenchidos.";
	}
	else{
		// Pega o valor remove os pontos e substitui a virgula pelo ponto
		$source = array('.', ','); 
		$replace = array('', '.');
		$valor = str_replace($source, $replace, $valor);

		// Conta fixa
		if($conta_fixa == 'S'){

			// Seta as variaveis igual a zero...
			$total_parcela = 0;
			$numero_parcela = 0;

			// Insere na tabela copag
			$insert = $conectar->prepare("INSERT INTO copag VALUES ('',?,?,?,?,?,?,?,?,now())") or die (mysqli_error($conectar));
			$insert->bind_param('iiisssss', $usuarioID, $tipo_despesa_id, $forma_pagamento_id, $data_despesa, $descricao, $parcelado, $total_parcela, $conta_fixa);
			$insert->execute();

			// Pega o ultimo ID inserido
			$ultimo_id = $insert->insert_id;

			// Cadastra 12 vezes a despesa
			for($x = 1; $x <= 12; $x++){

				if($x == 1){
					// Data de vencimento original
					$nova_data = explode("/", $_POST['data_vencimento']);
				} else{
					// Calcula a nova data do vencimento
					$nova_data = explode("/", SomarData($_POST['data_vencimento'], 0, $x - 1, 0));
				}

				$nova_data_vencimento = "$nova_data[2]-$nova_data[1]-$nova_data[0]";
				$ano = "$nova_data[2]";
				$mes = "$nova_data[1]";

				// Insere na tabela parcelas
				$insert = $conectar->prepare("INSERT INTO parcelas VALUES ('',?,?,?,?,?,?,?,?,?)") or die (mysqli_error($conectar));
				$insert->bind_param('issssssss', $ultimo_id, $nova_data_vencimento, $ano, $mes, $valor, $numero_parcela, $obs, $conta_paga, $data_pagamento);
				$insert->execute();

				if($cartoes_id != ''){

					$insertCartao = $conectar->prepare("INSERT INTO cartoes_has_copag VALUES (?,?)") or die (mysqli_error($conectar));
					$insertCartao->bind_param('ii', $cartoes_id, $ultimo_id);
					$insertCartao->execute();
				}
		
			} // fim do for

			if($insert == true){
				$alert = "success"; $icon = "check";
				$msg = "<strong>Feito!</strong> Cadastro realizado com sucesso.";
			} else{
				$alert = "danger"; $icon = "times";
				$msg = "<strong>Ops!</strong> Houve um erro para realizar o cadastro.";
			}

		} // fim do if
		// Conta parcelada
		elseif($parcelado == 'S'){

			// Insere na tabela copag
			$insert = $conectar->prepare("INSERT INTO copag VALUES ('',?,?,?,?,?,?,?,?,now())") or die (mysqli_error($conectar));
			$insert->bind_param('iiisssss', $usuarioID, $tipo_despesa_id, $forma_pagamento_id, $data_despesa, $descricao, $parcelado, $total_parcela, $conta_fixa);
			$insert->execute();

			// Pega o ultimo ID inserido
			$ultimo_id = $insert->insert_id;

			// Cadastra conforme o numero de parcelas de entrada
			for($x = 1; $x <= $total_parcela; $x++){

				$numero_parcela = $x;

				if($x == 1){
					// Data de vencimento original
					$nova_data = explode("/", $_POST['data_vencimento']);
				} else{
					// Calcula a nova data do vencimento
					$nova_data = explode("/", SomarData($_POST['data_vencimento'], 0, $x - 1, 0));
				}

				$nova_data_vencimento = "$nova_data[2]-$nova_data[1]-$nova_data[0]";
				$ano = "$nova_data[2]";
				$mes = "$nova_data[1]";

				// Insere na tabela parcelas
				$insert = $conectar->prepare("INSERT INTO parcelas VALUES ('',?,?,?,?,?,?,?,?,?)") or die (mysqli_error($conectar));
				$insert->bind_param('issssssss', $ultimo_id, $nova_data_vencimento, $ano, $mes, $valor, $numero_parcela, $obs, $conta_paga, $data_pagamento);
				$insert->execute();				

				if($cartoes_id != ''){

					$insertCartao = $conectar->prepare("INSERT INTO cartoes_has_copag VALUES (?,?)") or die (mysqli_error($conectar));
					$insertCartao->bind_param('ii', $cartoes_id, $ultimo_id);
					$insertCartao->execute();
				}
		
			} // fim do for	

			if($insert == true){
				$alert = "success"; $icon = "check";
				$msg = "<strong>Feito!</strong> Cadastro realizado com sucesso.";
			} else{
				$alert = "danger"; $icon = "times";
				$msg = "<strong>Ops!</strong> Houve um erro para realizar o cadastro.";
			}

		} // fim do elseif
		// Caso seja uma despesa normal (sem parcelas e não fixa)
		else{

			// Seta as variaveis igual a zero...
			$total_parcela = 0;
			$numero_parcela = 0;

			// Insere na tabela copag
			$insert = $conectar->prepare("INSERT INTO copag VALUES ('',?,?,?,?,?,?,?,?,now())") or die (mysqli_error($conectar));
			$insert->bind_param('iiisssss', $usuarioID, $tipo_despesa_id, $forma_pagamento_id, $data_despesa, $descricao, $parcelado, $total_parcela, $conta_fixa);
			$insert->execute();

			// Pega o ultimo ID inserido
			$ultimo_id = $insert->insert_id;

			// Insere na tabela parcelas
			$insert = $conectar->prepare("INSERT INTO parcelas VALUES ('',?,?,?,?,?,?,?,?,?)") or die (mysqli_error($conectar));
			$insert->bind_param('issssssss', $ultimo_id, $data_vencimento, $ano, $mes, $valor, $numero_parcela, $obs, $conta_paga, $data_pagamento);
			$insert->execute();	

			if($cartoes_id != ''){

				$insertCartao = $conectar->prepare("INSERT INTO cartoes_has_copag VALUES (?,?)") or die (mysqli_error($conectar));
				$insertCartao->bind_param('ii', $cartoes_id, $ultimo_id);
				$insertCartao->execute();
			}

			if($insert == true){
				$alert = "success"; $icon = "check";
				$msg = "<strong>Feito!</strong> Cadastro realizado com sucesso!!.";
			} else{
				$alert = "danger"; $icon = "times";
				$msg = "<strong>Ops!</strong> Houve um erro para realizar o cadastro.";
			}
			
		} // fim do else

	} // fim do else

	$display = 'show';

} // fim do if

// Query busca tipo de despesas
$sqlTipoDespesa = $conectar->prepare("SELECT tipo_despesa_id, descricao FROM view_tipo_despesa WHERE usuarios_id = ? ORDER BY descricao") or die (mysqli_error($conectar));
$sqlTipoDespesa->bind_param('i', $usuarioID);
$sqlTipoDespesa->execute();
$sqlTipoDespesa->store_result();
$sqlTipoDespesa->bind_result($id, $descricao);

// Query busca forma de pagamentos
$sqlPagamentos = $conectar->prepare("SELECT forma_pagamento_id, descricao FROM view_forma_pagamento WHERE usuarios_id = ? ORDER BY descricao") or die (mysqli_error($conectar));
$sqlPagamentos->bind_param('i', $usuarioID);
$sqlPagamentos->execute();
$sqlPagamentos->store_result();
$sqlPagamentos->bind_result($id, $descricao);

// Query busca ultimas despesas cadastradas
$sqlUltimas = $conectar->prepare("
	SELECT descricao, data_vencimento, valor, desc_forma_pagamento, desc_cartao
	FROM view_copag
	WHERE usuarios_id = ?
	GROUP BY copag_id
	ORDER BY data_cadastro DESC
	LIMIT 5
	") or die (mysqli_error($conectar));
$sqlUltimas->bind_param('i', $usuarioID);
$sqlUltimas->execute();
$sqlUltimas->store_result();
$sqlUltimas->bind_result($descricao, $data_vencimento, $valor, $desc_forma_pagamento, $desc_cartao);

?>