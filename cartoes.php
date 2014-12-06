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

include "query/q-cartoes.php";
include "includes/meses.php";
$convert_mes = new Meses;
$mes_extenso = $convert_mes->convert_mes($mes);

?>

	<!-- Head -->
	<?php include "includes/head.php"; ?>

	<!-- Datepicker -->
	<link rel="stylesheet" href="<?php echo $home ?>/css/datepicker.css">

</head>
	
<body>
	
	<!-- Navbar -->
	<?php include "includes/navbar.php"; ?>

	<!-- Mensagem ao usuário -->
	<?php include "includes/bem-vindo.php"; ?>

	<!-- Container corpo -->
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h3 class="page-header text-success"><?php echo $nome_descricao . " - " . $mes_extenso . " " . $ano ?></h3>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8 text-right">

				<?php
				// Conta paga
				if($conta_paga_count >= 1){
					?>
					<p class="text-info"><i class="fa fa-check fa-fw"></i> Essa fatura já foi paga.</p>
					<?php
				} else{
					?>
					<form class="form-inline" role="form" method="post" action="">
						<div class="form-group">
							<div class="input-group col-md-6">
								<div class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></div>
								<input class="form-control" type="text" name="data_pagamento" id="data_pagamento" placeholder="Data pagamento" autocomplete="off">
								<input type="hidden" name="cartoes_id" value="<?php echo $cartoes_id ?>" >
								<input type="hidden" name="ano" value="<?php echo $ano ?>" >
								<input type="hidden" name="mes" value="<?php echo $mes ?>" >
							</div>
							<div class="input-group">
								<button type="submit" class="btn btn-success" name="btnPagar" value="1"><i class="fa fa-check fa-fw"></i> Marcar como pago</button>
							</div>
						</div>
					</form>
					<?php
				} // fim do else
				?>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
			
				<div class="table-responsive">

					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Data despesa</th>
								<th>Descrição</th>
								<th>Valor</th>
								<th class="text-center">Ações</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Inicia o contador
							$i = 1;
							while($sqlCartoes->fetch()){

								// Data despesa
								$array = explode("-", $data_despesa);
								$data_despesa = "$array[2]/$array[1]/$array[0]";

								// Data vencimento
								$array = explode("-", $data_vencimento);
								$data_vencimento = "$array[2]/$array[1]/$array[0]";

								$valor_total = $valor_total + $valor;
								// Converte para real o valor
								$valor_exibe = ' R$ ' . number_format($valor, 2, ',', '.');


								if($parcelado == 'S')
									$numero_parcela = $numero_parcela. "/" .$total_parcela;
								else
									$numero_parcela = '';
								
								?>

								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo $data_despesa ?></td>
									<td><?php echo $descricao . " " . $numero_parcela ?></td>
									<td><?php echo $valor_exibe ?></td>
									<td class="text-center">
										<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'1' ?>" title="Editar registro" alt="Editar registro"><i class="fa fa-pencil fa-fw"></i></a>
										<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'2' ?>" title="Visualizar registro" alt="Visualizar registro"><i class="fa fa-list-alt fa-fw"></i></a>
										<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'3' ?>" title="Remover registro" alt="Remover registro"><i class="fa fa-trash fa-fw"></i></a>
									</td>
								</tr>

								<?php
								// Incrementa o contador
								$i++;
							} // fim do while
							?>
						</tbody>
					</table>

					<ul class="pager">
						<li><a href="<?php echo $home ?>/cartao-credito/<?php echo $cartoes_id."-".$slogan_cartao ?>/<?php echo $fat_ano_ant ?>/<?php echo $fat_mes_ant ?>/">Fatura anterior</a></li>
						<li><a href="<?php echo $home ?>/cartao-credito/<?php echo $cartoes_id."-".$slogan_cartao ?>/<?php echo $fat_ano_pro ?>/<?php echo $fat_mes_pro ?>/">Próxima fatura</a></li>
					</ul>

					<!-- Modal -->
					<div class="modal fade" id="modal_despesas" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content" id="modal_corpo">
							</div>
						</div>
					</div>

					<h4 class="text-success text-center">Total de despesas <?php echo ' R$ ' . number_format($valor_total, 2, ',', '.'); ?></h4>
					<h4 class="text-success text-center">Vencimento <?php echo $data_vencimento ?></h4>

				</div> <!-- /.table responsive -->

			</div> <!-- /.col-md-8 -->

			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><strong><i class="fa fa-thumb-tack fa-fw"></i> Informações gerais</strong></div>
					<div class="panel-body">
						<label>Escolha o cartão</label>
						<select class="form-control" onchange="window.open(this.value,'_self');" >
							<option>Selecionar</option>
							<?php
							while($sqlCartao->fetch()){
								?>
								<option value="<?php echo $home ?>/cartao-credito/<?php echo $id . "-" . $slogan ?>/<?php echo $ano ?>/<?php echo $mes ?>/"><?php echo $descricao ?></option>
								<?php
							} // fim do while
							?>
						</select>
						<hr>
						<p>Dia de vencimento <span class="pull-right"><?php echo $dia_vencimento ?></span></p>
						<p>Melhor data para compra <span class="pull-right"><?php echo $melhor_data ?></span></p>
						<p>Limite disponível <span class="pull-right"><?php echo ' R$ ' . number_format($limite, 2, ',', '.') ?></span></p>
						<p>Disponível para compra <span class="pull-right"><?php echo ' R$ ' . number_format(($limite - $soma_cartao), 2, ',', '.') ?></span></p>
					</div>
				</div>
			</div> <!-- /.col-md-4 -->

		</div> <!-- /.row -->
	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "includes/footer.php"; ?>

	<!-- Datepicker -->
	<script src="<?php echo $home ?>/js/bootstrap-datepicker.js" type="text/javascript"></script>

	<!-- Arquivos JS Maskaras -->
    <script src="<?php echo $home ?>/js/jquery.maskMoney.js"></script>

	<script type="text/javascript">
		// jquery para chamar a modal
		$("table").on('click',"#modal_popup", function(){
			var id = $(this).attr('data-id'); // pega o id do botão
			$.post('<?php echo $home ?>/modal-despesas.php', {id: id}, function(retorno){
				$("#modal_despesas").modal({ backdrop: 'static' });
				$("#modal_corpo").html(retorno);
			});
		});

		// Datas - Datepicker
        $(document).ready(function () {
            $('#data_pagamento').datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            });
        });

    </script>

</body>
</html>