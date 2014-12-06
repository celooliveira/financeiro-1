<?php

// Inicia a sessão
session_name('financeiro');
session_start();

// ID do usuario
$usuarioID = $_SESSION['id'];

// Includes
include "../conn/DB.class.php";
include "../includes/globais.php";
include "../conn/check.php";

// Fim - todas as páginas tem que ter o código acima

// Função para somar as parcelas das despesas
function SomarData($data, $dias, $meses, $ano){
    // A data deve estar no formato dd/mm/yyyy
    $data = explode("/", $data);
    $nova_data = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $ano) );
    return $nova_data;
}

$cartoes_id = (int)$_GET['id'];

// Busca a data de vencimento do cartão selecionado
$sqlDataCartao = $conectar->prepare("
	SELECT data_vencimento, melhor_data
	FROM cartoes
	WHERE usuarios_id = ?
	AND id = ?
	") or die (mysqli_error($conectar));
$sqlDataCartao->bind_param('ii', $usuarioID, $cartoes_id);
$sqlDataCartao->execute();
$sqlDataCartao->store_result();
$sqlDataCartao->bind_result($dia_v, $melhor_data);
$sqlDataCartao->fetch();

//$data_atual = "2014-08-27";
//$melhor_data = "2014-08-28";

$diferenca = $dia_v - $melhor_data;

$data_atual = date("Y-m-d");
$melhor_data = date("Y")."-".date("m")."-".$melhor_data;
$data_vencimento = $dia_v."/".date("m")."/".date("Y");

// Calcula a data de vencimento baseado da melhor data para comprar
if(strtotime($data_atual) >= strtotime($melhor_data)){
	if($diferenca < 0){ // Negativo
		$data_vencimento = SomarData($data_vencimento, 0, 2, 0);
	} else{
		$data_vencimento = SomarData($data_vencimento, 0, 1, 0);
	}
} else{
	if($diferenca < 0){ // Negativo
		$data_vencimento = SomarData($data_vencimento, 0, 1, 0);
	} else{
		$data_vencimento = $dia_v."/".date("m")."/".date("Y");
	}
}

?>

<div class="form-group">
	<label class="col-md-3 control-label">* Vencimento</label>
	<div class="col-md-4">
		<input type="text" class="form-control input-sm" name="data_vencimento" value="<?php echo $data_vencimento ?>" id="data_vencimento" autocomplete="off" required >
	</div>
	<div class="col-md-5">
		<p class="help-block">Verifique se a data de vencimento esta correta.</p>
	</div>
</div>

<script type="text/javascript">
	$('#data_vencimento').datepicker({
	    format: "dd/mm/yyyy",
	    autoclose: true
	});
</script>
