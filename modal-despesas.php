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
$parcelas_id = $array[0]; // ID despesa
$opcao = $array[1]; // Opcao do evento
/*
1 - Editar
2 - Visualizar
3 - Remover
*/

// Busca a despesa conforme ID
$sqlDespesa = $conectar->prepare("
	SELECT copag_id, data_despesa, data_vencimento, mes, ano, descricao, valor, parcelado, numero_parcela, total_parcela, conta_fixa, obs, conta_paga, data_pagamento, tipo_despesa_id, desc_tipo_despesa, forma_pagamento_id, desc_forma_pagamento, cartoes_id, desc_cartao
	FROM view_copag 
	WHERE parcelas_id = ?
	AND usuarios_id = ? ") or die (mysqli_error($conectar));
$sqlDespesa->bind_param('ii', $parcelas_id, $usuarioID);
$sqlDespesa->execute();
$sqlDespesa->store_result();
$sqlDespesa->bind_result($copag_id, $data_despesa, $data_vencimento, $mes, $ano, $descricao, $valor, $parcelado, $numero_parcela, $total_parcela, $conta_fixa, $obs, $conta_paga, $data_pagamento, $tipo_despesa_id, $desc_despesa, $forma_pagamento_id, $desc_forma_pagamento, $cartoes_id, $desc_cartao);
$sqlDespesa->fetch();

// Busca o valor total da despesa
$sqlSomaDespesa = $conectar->prepare("
	SELECT SUM(valor) as valor
	FROM view_copag 
	WHERE copag_id = ?
	AND usuarios_id = ?") or die (mysqli_error($conectar));
$sqlSomaDespesa->bind_param('ii', $copag_id, $usuarioID);
$sqlSomaDespesa->execute();
$sqlSomaDespesa->store_result();
$sqlSomaDespesa->bind_result($valor_total);
$sqlSomaDespesa->fetch();

// Converte as datas
$array = explode("-", $data_despesa);				
$data_despesa = "$array[2]/$array[1]/$array[0]";

if($data_despesa == '00/00/0000')
	$data_despesa = 'Não consta';

$array = explode("-", $data_pagamento);				
$data_pagamento = "$array[2]/$array[1]/$array[0]";

if($data_pagamento == '00/00/0000')
	$data_pagamento = '';

$array = explode("-", $data_vencimento);				
$data_vencimento = "$array[2]/$array[1]/$array[0]";

// Converte o valor da despesa
$valor_cifrao = ' R$ ' . number_format($valor, 2, ',', '.');
$valor_total = ' R$ ' . number_format($valor_total, 2, ',', '.');

// Conta parcelada
if($parcelado == 'S'){
	$lb_parcelado = 'Sim';
} else{
	$lb_parcelado = 'Não';
}

// Conta fixa
if($conta_fixa == 'S'){
	$lb_conta_fixa = 'Sim';
	$referente = $convert_mes->convert_mes($mes) . " / " . $ano;
} else{
	if($parcelado == 'S'){
		$referente = $numero_parcela . "ª parcela";
	} else{
		$referente = $convert_mes->convert_mes($mes) . " / " . $ano;
	}
	$lb_conta_fixa = 'Não';
}

// Conta paga
if($conta_paga == 'S')
	$lb_conta_paga = 'Sim';
else
	$lb_conta_paga = 'Não';


// Select - tipo de receita
$sqlTipoReceita = $conectar->prepare("
	SELECT tipo_despesa_id, descricao 
	FROM view_tipo_despesa 
	WHERE usuarios_id = ? 
	ORDER BY descricao ")  or die (mysqli_error($conectar));
$sqlTipoReceita->bind_param('i', $usuarioID);
$sqlTipoReceita->execute();
$sqlTipoReceita->store_result();
$sqlTipoReceita->bind_result($id, $desc_despesa);

if($forma_pagamento_id != 1){
	$sql = ' AND forma_pagamento_id != 1 ';
} else{
	$sql = ' ';
	$desc_cartao = " - ". $desc_cartao;
}

// Select - forma de pagamento
$sqlFormaPgto = $conectar->prepare("
	SELECT forma_pagamento_id, descricao 
	FROM view_forma_pagamento 
	WHERE usuarios_id = ? 
	".$sql."
	ORDER BY descricao ")  or die (mysqli_error($conectar));
$sqlFormaPgto->bind_param('i', $usuarioID);
$sqlFormaPgto->execute();
$sqlFormaPgto->store_result();
$sqlFormaPgto->bind_result($id, $desc_forma_pagamento);

switch ($opcao) {
	case 1: $modal_title = 'Editar despesa cadastrada';	break;
	case 2: $modal_title = 'Detalhes da despesa'; break;
	case 3: $modal_title = 'Remover registro'; break;
} // fim do while

?>

<div class="modal-header">
	<h4 class="modal-title text-success" id="myModalLabel"><?php echo $modal_title ?></h4>
</div>

<form class="form-horizontal" role="form" method="post" action="">

<div class="modal-body">

	<!-- Evento Editar -->
	<?php if($opcao == 1){ ?>

		<div class="form-group">
			<label class="col-md-4 control-label">Referente</label>
			<div class="col-md-5">
				<p class="form-control-static"><?php echo $referente ?></p>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Tipo de despesa</label>
			<div class="col-md-5">
				<?php if($numero_parcela <= 1){ ?>
					<select class="form-control input-sm" name="tipo_despesa_id">
						<?php while($sqlTipoReceita->fetch()){ ?>
							<option value="<?php echo $id ?>" <?php if($tipo_despesa_id == $id){ ?> selected <?php } ?> ><?php echo $desc_despesa ?></option>
						<?php } ?>
					</select>
				<?php } else{ ?>
					<p class="form-control-static"><?php echo $desc_despesa ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Data da despesa</label>
			<div class="col-md-5">
				<?php if($numero_parcela <= 1){ ?>
					<input type="text" class="form-control input-sm" value="<?php echo $data_despesa ?>" name="data_despesa" id="data_despesa" >
				<?php } else{ ?>
					<p class="form-control-static"><?php echo $data_despesa ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Vencimento</label>
			<div class="col-md-5">
				<?php if($numero_parcela <= 1){ ?>		
					<input type="text" class="form-control input-sm" value="<?php echo $data_vencimento ?>" name="data_vencimento" id="data_vencimento" >
				<?php } else{ ?>
					<p class="form-control-static"><?php echo $data_vencimento ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Descrição</label>
			<div class="col-md-6">
				<?php if($numero_parcela <= 1){ ?>
					<input type="text" class="form-control input-sm" value="<?php echo $descricao ?>" name="descricao" >
				<?php } else{ ?>
					<p class="form-control-static"><?php echo $descricao ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Conta parcelada</label>
			<div class="col-md-5">
				<p class="form-control-static"><?php echo $lb_parcelado ?></p>
			</div>
		</div>

		<?php if($parcelado == 'S'){ ?>
		<div class="form-group">
			<label class="col-md-4 control-label">Número de parcelas</label>
			<div class="col-md-5">
				<p class="form-control-static"><?php echo $total_parcela ?></p>
			</div>
		</div>
		<?php } ?>

		<?php
		if($parcelado == 'S')
			$label_valor = 'Valor parcelado';
		else
			$label_valor = 'Valor';
		
		?>

		<div class="form-group">
			<label class="col-md-4 control-label"><?php echo $label_valor ?></label>
			<div class="col-md-3">
				<input type="text" class="form-control input-sm" value="<?php echo number_format($valor, 2, ',', '.') ?>" name="valor" id="valor" >
			</div>
			<?php if($numero_parcela == 1 && $conta_fixa == 'N'){ ?>
			<div class="col-md-5">
				<input type="checkbox" name="altera_valor" checked value="1" > Alterar apenas para este mês.
			</div>
			<?php } ?>
		</div>

		<?php if($parcelado == 'S'){ ?>
			<div class="form-group">
				<label class="col-md-4 control-label">Valor total</label>
				<div class="col-md-5">
					<p class="form-control-static"><?php echo $valor_total ?></p>
				</div>
			</div>
		<?php }	else{ ?>
			<div class="form-group">
				<label class="col-md-4 control-label">Conta fixa</label>
				<div class="col-md-5">
					<p class="form-control-static"><?php echo $lb_conta_fixa ?></p>
				</div>
			</div>
		<?php } ?>

		<div class="form-group">
			<label class="col-md-4 control-label">Conta paga</label>
			<div class="col-md-7">
				<input type="checkbox" name="conta_paga" <?php if($conta_paga == 'S'){ ?> checked <?php } ?> value="S" > Marque caso a conta tenha sido paga.
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Data pagamento</label>
			<div class="col-md-5">
				<input type="text" class="form-control input-sm" value="<?php echo $data_pagamento ?>" name="data_pagamento" id="data_pagamento" >
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Forma de pagamento</label>
			<div class="col-md-5">
			<?php if($numero_parcela <= 1  && $forma_pagamento_id != 1){ ?>
				<select class="form-control" name="forma_pagamento_id">
					<?php while($sqlFormaPgto->fetch()){ ?>
					<option value="<?php echo $id ?>" <?php if($forma_pagamento_id == $id){ ?> selected <?php } ?> ><?php echo $desc_forma_pagamento ?></option>
					<?php } ?>
				</select>
			<?php } else{ ?>
				<p class="form-control-static"><?php echo $desc_forma_pagamento . $desc_cartao ?></p>
			<?php } ?>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-4 control-label">Observações</label>
			<div class="col-md-6">
				<input type="text" class="form-control input-sm" value="<?php echo $obs ?>" name="obs" >
			</div>
		</div>

	<?php } ?>

	<!-- Evento Visualizar -->
	<?php if($opcao == 2){ ?>

	<div class="row">
		<div class="col-md-4">
			<p><strong>Referente</strong></p>
			<p><strong>Tipo de receita</strong></p>
			<p><strong>Data da despesa</strong></p>
			<p><strong>Vencimento</strong></p>
			<p><strong>Descrição</strong></p>
			<p><strong>Conta parcelada</strong></p>
			<?php if($parcelado == 'S'){ ?>
			<p><strong>Nº de parcelas</strong></p>
			<?php } ?>

			<?php
			if($parcelado == 'S') 
				$label_valor = "Valor mensal";
			else
				$label_valor = "Valor";
			?>

			<p><strong><?php echo $label_valor ?></strong></p>

			<?php if($parcelado == 'S'){ ?>
				<p><strong>Valor total</strong></p>
			<?php } ?>

			<p><strong>Conta fixa</strong></p>
			<p><strong>Conta paga</strong></p>
			<p><strong>Data pagamento</strong></p>
			<p><strong>Forma de pagamento</strong></p>
			<p><strong>Observação</strong></p>

		</div>

		<div class="col-md-8">
			<p><?php echo $referente ?></p>
			<p><?php echo $desc_despesa ?></p>
			<p><?php echo $data_despesa ?></p>
			<p><?php echo $data_vencimento ?></p>
			<p><?php echo $descricao ?></p>
			<p><?php echo $lb_parcelado ?></p>
			<?php if($parcelado == 'S'){ ?>
			<p><?php echo $total_parcela ?></p>
			<?php } ?>

			<?php
			if($parcelado == 'S') 
				$label_valor = "Valor mensal";
			else
				$label_valor = "Valor";

			if($data_pagamento == '')
				$data_pagamento = "Pendente";
			?>

			<p><?php echo $valor_cifrao ?></p>

			<?php if($parcelado == 'S'){ ?>
				<p><?php echo $valor_total ?></p>
			<?php } ?>

			<p><?php echo $lb_conta_fixa ?></p>
			<p><?php echo $lb_conta_paga ?></p>
			<p><?php echo $data_pagamento ?></p>
			<p><?php echo $desc_forma_pagamento . $desc_cartao ?></p>
			<p><?php echo $obs ?></p>
		</div>
	</div>

	<?php } ?>

	<!-- Evento Remover -->
	<?php if($opcao == 3){ ?>

		<p class="text-danger">Tem certeza que deseja remover essa despesa?</p>

		<?php if($parcelado == 'S'){ ?>

			<p><strong><?php echo $descricao ?></strong> no valor de <?php echo $valor_cifrao ?></p>
			<p>Essa despesa foi parcelada em <?php echo $total_parcela ?> vezes.</p>
			<p>Esta ação pode ser perigosa, pois irá remover todas as parcelas.</p>

		<?php } elseif($conta_fixa == 'S'){ ?>	

			<p><strong><?php echo $descricao ?></strong> no valor de <?php echo $valor_cifrao ?></p>
			<p>Essa despesa é fixa e está cadastrada para os próximos meses.</p>
			<p>Você deseja remove-la apenas para o mês de <strong><?php echo $convert_mes->convert_mes($mes) ?></strong> ou para todos os meses (até os meses anteriores)?</p>

		<?php } else{ ?>

			<p><strong><?php echo $descricao ?></strong> no valor de <?php echo $valor_cifrao ?></p>

		<?php } ?>

	<?php } ?>

</div> <!-- /.modal-body -->

<div class="modal-footer">
	<?php if($opcao == 1){ ?>
	<button type="submit" class="btn btn-success" name="btnSalvarModal" value="1" ><i class="fa fa-save fa-fw"></i> Salvar</button>
	<input type="hidden" name="copag_id" value="<?php echo $copag_id ?>" >
	<input type="hidden" name="parcelas_id" value="<?php echo $parcelas_id ?>" >
	<input type="hidden" name="parcelado" value="<?php echo $parcelado ?>" >
	<input type="hidden" name="numero_parcela" value="<?php echo $numero_parcela ?>" >
	<?php } ?>
	<?php if($opcao == 3){ ?>
		<?php if($parcelado == 'S'){ ?>
		<button type="submit" class="btn btn-success" name="btnRemoverModal" value="1" ><i class="fa fa-check fa-fw"></i> Sim, eu tenho!</button>
		<?php } elseif($conta_fixa == 'S'){ ?>
		<button type="submit" class="btn btn-success" name="btnRemoverModal" value="2" ><i class="fa fa-check fa-fw"></i> Mês de <?php echo $convert_mes->convert_mes($mes) ?>!</button>
		<button type="submit" class="btn btn-success" name="btnRemoverModal" value="3" ><i class="fa fa-check fa-fw"></i> Todos os meses!</button>
		<?php } else{ ?>
		<button type="submit" class="btn btn-success" name="btnRemoverModal" value="4" ><i class="fa fa-check fa-fw"></i> Sim, eu tenho!</button>
		<?php } ?>
		<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Acho melhor não!</button>
		<input type="hidden" name="copag_id" value="<?php echo $copag_id ?>" >
		<input type="hidden" name="parcelas_id" value="<?php echo $parcelas_id ?>" >
	<?php } else{ ?>
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
	<?php } ?>
</div>

</form>


<script type="text/javascript">
	// Datas - Datepicker
    $(document).ready(function () {
        
        $('#data_despesa').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }); 

        $('#data_vencimento').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        });

        $('#data_pagamento').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        }); 

        // Maskaras
		$(function() {
			$("#valor").maskMoney({symbol:'R$ ', showSymbol:false, thousands:'.', decimal:',', symbolStay: true});
        });
    
    });

</script>
	
