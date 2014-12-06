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

// Pega apenas o primeiro numero do ID
$forma_pagamento_id = (int)$_GET['id'];

// Cartão de crédito = 1
if($forma_pagamento_id == 1){

	// Query busca os cartoes
	$sqlCartao = $conectar->prepare("SELECT id, descricao, codigo FROM cartoes WHERE usuarios_id = ? AND ativo = '1' ORDER BY descricao") or die (mysqli_error($conectar));
	$sqlCartao->bind_param('i', $usuarioID);
	$sqlCartao->execute();
	$sqlCartao->store_result();
	$sqlCartao->bind_result($id, $descricao, $codigo);

	?>

	<div class="form-group">
		<label class="col-md-3 control-label">* Cartão de Crédito</label>
		<div class="col-md-4">
			<select class="form-control input-sm" name="cartoes_id" id="selectDataCartaoCredito" required>
				<option value="">Selecione</option>
				<?php while($sqlCartao->fetch()){ ?>
					<option value="<?php echo $id ?>"><?php echo $descricao . " (XXXX-XXXX-XXXX-" . $codigo . ")" ?></option>
				<?php }	?>
			</select>
		</div>
	</div>

	<div id="ShowDataCartaoCredito"></div>

	<?php
} // fim do if
else{
	?>

	<!-- data vencimento -->
	<div class="form-group">
		<label class="col-md-3 control-label">* Vencimento</label>
		<div class="col-md-4">
			<input type="text" class="form-control input-sm" name="data_vencimento" autocomplete="off" id="data_vencimento" required >
		</div>
	</div>
	<?php
} // fim do else
?>

<script type="text/javascript">
	// Campos seleciona a data de vencimento do cartao de credito
    $('#selectDataCartaoCredito').change(function(){
		$('#ShowDataCartaoCredito').load('<?php echo $home ?>/query/q-select_data_cartao.php?id='+$('#selectDataCartaoCredito').val() );
	});

	$('#data_vencimento').datepicker({
	    format: "dd/mm/yyyy",
	    autoclose: true
	});

</script>