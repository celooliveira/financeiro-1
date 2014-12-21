<?php

$tab = 1;

// Evento cadastrar receita
if(isset($_POST['btnCadastrar']) && $_POST['btnCadastrar'] == 1) {

	$mes = (int)$_POST['mes'];
	$ano = (int)$_POST['ano'];
	$tipo_receita = $_POST['tipo_receita'];
	$valor = $_POST['valor'];
	$obs = $_POST['obs'];

	$array = explode("/", $_POST['data_receita']);
	$data_receita = "$array[2]-$array[1]-$array[0]";

	// Pega o valor remove os pontos e substitui a virgula pelo ponto
	$source = array('.', ','); 
	$replace = array('', '.');
	$valor = str_replace($source, $replace, $valor);

	// Verifica se existe uma receita para o mes e ano
	$sqlVerifica = $conectar->prepare("SELECT COUNT(id) AS cont_id, id FROM receita WHERE mes = ? AND ano = ? AND usuarios_id = ? ") or die (mysqli_error($conectar));
	$sqlVerifica->bind_param('iii', $mes, $ano, $usuarioID);
	$sqlVerifica->execute();
	$sqlVerifica->store_result();
	$sqlVerifica->bind_result($cont_id, $id);
	$sqlVerifica->fetch();

	// Existe receita
	if($cont_id == 1){

		// Insert
		$insert2 = $conectar->prepare("INSERT INTO receita_valores VALUES ('',?,?,?,?,?)") or die (mysqli_error($conectar));
		$insert2->bind_param('issss', $id, $tipo_receita, $valor, $data_receita, $obs);
		$insert2->execute();

	} else{ // Nao existe

		// Insert
		$insert = $conectar->prepare("INSERT INTO receita VALUES ('',?,?,?)") or die (mysqli_error($conectar));
		$insert->bind_param('iii', $usuarioID, $mes, $ano);
		$insert->execute();

		// Pega ultimo ID inserido
		$ultimo_id = $insert->insert_id;

		// Insert
		$insert2 = $conectar->prepare("INSERT INTO receita_valores VALUES ('',?,?,?,?,?)") or die (mysqli_error($conectar));
		$insert2->bind_param('issss', $ultimo_id, $tipo_receita, $valor, $data_receita, $obs);
		$insert2->execute();

	} // fim do else

} // fim do if cadastrar

// Evento alterar receitas - modal
if(isset($_POST['btnSalvarAlteracoes']) && $_POST['btnSalvarAlteracoes']){

	$cont = count($_POST['data_receita']);
 
	for($i = 0; $i < $cont; $i++){

		$array = explode("/", $_POST['data_receita'][$i]);
		$data_receita = "$array[2]-$array[1]-$array[0]";
		$valor = $_POST['valor'][$i];
		$obs = $_POST['obs'][$i];
		$receita_valores_id = $_POST['receita_valores_id'][$i];

		// Pega o valor remove os pontos e substitui a virgula pelo ponto
		$source = array('.', ','); 
		$replace = array('', '.');
		$valor = str_replace($source, $replace, $valor);

		// Insert
		$insert = $conectar->prepare("UPDATE receita_valores SET valor = ?, data_receita = ?, obs = ? WHERE id = ? ") or die (mysqli_error($conectar));
		$insert->bind_param('sssi', $valor, $data_receita, $obs, $receita_valores_id);
		$insert->execute();

		//$tab = 2;

	} // fim do for

} // fim do if

// Evento remover receita
if(isset($_POST['btnRemoverReceita'])){

	$receita_valores_id = (int)$_POST['btnRemoverReceita'];

	// Remove
	$delete = $conectar->prepare("DELETE FROM receita_valores WHERE id = ? ");
	$delete->bind_param('i', $receita_valores_id);
	$delete->execute();

} // fim do if

// Busca dados para o grafico
$sqlGraReceita = $conectar->prepare("
	SELECT mes, ano, SUM(valor)
	FROM receita AS r
	LEFT JOIN receita_valores AS rv
	ON r.id = rv.receita_id
	WHERE r.usuarios_id = ?
	GROUP BY ano, mes
	ORDER BY ano DESC, mes DESC
	LIMIT 7 ") or die (mysqli_error($conectar));
$sqlGraReceita->bind_param('i', $usuarioID);
$sqlGraReceita->execute();
$sqlGraReceita->store_result();
$sqlGraReceita->bind_result($mes, $ano, $valor);

// Busca dados mostrar detalhes
$sqlDetalhes = $conectar->prepare("
	SELECT receita_valores_id, receita_id, mes, ano, SUM(valor)
	FROM view_receita
	WHERE usuarios_id = ?
	GROUP BY mes, ano
	ORDER BY ano DESC, mes DESC
	LIMIT 13 ") or die (mysqli_error($conectar));
$sqlDetalhes->bind_param('i', $usuarioID);
$sqlDetalhes->execute();
$sqlDetalhes->store_result();
$sqlDetalhes->bind_result($receita_valores_id, $receita_id, $mes, $ano, $valor);



?>