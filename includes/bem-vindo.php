<?php

if($_SESSION['numero_acesso'] == 0){
	$msg_bem_vindo = 'Seja bem vindo(a) ' . $_SESSION['nome'] . ', esse é seu primeiro acesso.';
} else{
	$array = explode(" ", $_SESSION['acesso_atual']);
	$data = explode("-", $array[0]);
	$ultimo_acesso = "$data[2]/$data[1]/$data[0]" . " às " . substr($array[1], 0, 5);
	$msg_bem_vindo = 'Bom dia ' . $_SESSION['nome'] . ', seu último acesso foi em ' . $ultimo_acesso;
}

?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<p class="welcome text-muted"><?php echo $msg_bem_vindo ?></p>
		</div>
	</div>
</div>