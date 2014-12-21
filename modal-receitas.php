<?php


// Inicia a sessão
session_name('financeiro');
session_start();

// ID do usuario
$usuarioID = $_SESSION['id'];

// Includes
include "conn/DB.class.php";
include "includes/globais.php";
include "conn/check.php";

// Fim - todas as páginas tem que ter o código acima

include "includes/meses.php";
$convert_mes = new Meses;

$array = explode("-", $_POST['id']);
$receita_id = $array[0]; // ID despesa
$opcao = $array[1]; // Opcao do evento

// Busca dados da receita
$sqlReceita = $conectar->prepare("
	SELECT receita_valores_id, mes, ano, tipo_receita, valor, data_receita, obs
	FROM view_receita
	WHERE receita_id = ? AND usuarios_id = ?
	ORDER BY data_receita ASC ");
$sqlReceita->bind_param('ii', $receita_id, $usuarioID);
$sqlReceita->execute();
$sqlReceita->store_result();
$sqlReceita->bind_result($receita_valores_id, $mes, $ano, $tipo_receita, $valor, $data_receita, $obs);

// Titulo modal
switch ($opcao) {
	case 1: $modal_title = 'Editar receita cadastrada';	break;
	case 2: $modal_title = 'Detalhes da receita'; break;
	case 3: $modal_title = 'Remover registro'; break;
} // fim do while

?>


<div class="modal-header">
	<h4 class="modal-title text-success" id="myModalLabel"><?php echo $modal_title ?></h4>
</div>

<form class="form-horizontal" role="form" method="post" action="">

<div class="modal-body">

	<?php
	// Se opcao Visualizar
	if($opcao == 1){
		?>

		<table class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Data Receita</th>
					<th>Valor</th>
					<th>Obs</th>
					<!--<th></th>-->
				</tr>
			</thead>
			<tbody>
				<?php
				// Inicia o contador
				$i = 1;
				while($sqlReceita->fetch()){

					// Data receita
					$array = explode("-", $data_receita);
					$data_receita = "$array[2]/$array[1]/$array[0]";

					// Converte para real o valor
					$valor_exibe = number_format($valor, 2, ',', '.');

					// Soma
					$valor_soma = $valor_soma + $valor;

					?>

					<tr>
						<td><?php echo $i ?></td>
						<td><input name="data_receita[]" id="data_receita" class="form-control input-sm" value="<?php echo $data_receita ?>" ></td>
						<td><input name="valor[]" id="valor" class="form-control input-sm" value="<?php echo $valor_exibe ?>" ></td>
						<td><input name="obs[]" class="form-control input-sm" value="<?php echo $obs ?>" ></td>
						<!--<td><button type="submit" class="btn btn-success" name="btnRemoverReceita" value="<?php echo $receita_valores_id ?>" ><i class="fa fa-trash fa-fw"></i></button></td>-->
					</tr>
					<input type="hidden" name="receita_valores_id[]" value="<?php echo $receita_valores_id ?>" >

					<?php
					// Incrementa o contador
					$i++;
				} // fim do while
				?>
			</tbody>
		</table>




		<?php
	} // fim opcao 1
	?>

	<?php
	// Se opcao Visualizar
	if($opcao == 2){
		?>

		<table class="table table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Mês</th>
					<th>Ano</th>
					<th>Data</th>
					<th>Receita</th>
					<th>Obs</th>
				</tr>
			</thead>
			<tbody>
				<?php
				// Inicia o contador
				$i = 1;
				while($sqlReceita->fetch()){

					// Data receita
					$array = explode("-", $data_receita);
					$data_receita = "$array[2]/$array[1]/$array[0]";

					// Converte para real o valor
					$valor_exibe = ' R$ ' . number_format($valor, 2, ',', '.');

					// Soma
					$valor_soma = $valor_soma + $valor;

					?>

					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $convert_mes->convert_mes($mes) ?></td>
						<td><?php echo $ano ?></td>
						<td><?php echo $data_receita ?></td>
						<td><?php echo $valor_exibe ?></td>
						<td><?php echo $obs ?></td>
					</tr>

					<?php
					// Incrementa o contador
					$i++;
				} // fim do while
				?>
			</tbody>
		</table>

		<h4 class="text-center text-muted">Receita do mês <?php echo ' R$ ' . number_format($valor_soma, 2, ',', '.'); ?></h4>

		<?php
	} // fim opcao 2
	?>


</div> <!-- /.modal-body -->

<div class="modal-footer">

	<?php
	// Se opcao Editar
	if($opcao == 1){
		?>
		<button type="submit" class="btn btn-success" name="btnSalvarAlteracoes" value="1" ><i class="fa fa-save fa-fw"></i> Salvar</button>
		<?php
	} // fim opcao 2
	?>

	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>

</div>

</form>