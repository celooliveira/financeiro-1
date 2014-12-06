<?php

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

}

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
	SELECT mes, ano, SUM(valor)
	FROM view_receita
	WHERE usuarios_id = ?
	GROUP BY mes, ano
	ORDER BY ano DESC, mes DESC
	LIMIT 13 ") or die (mysqli_error($conectar));
$sqlDetalhes->bind_param('i', $usuarioID);
$sqlDetalhes->execute();
$sqlDetalhes->store_result();
$sqlDetalhes->bind_result($mes, $ano, $valor);



?>