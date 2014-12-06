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

include "query/q-despesas.php";
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
			<div class="col-md-9">
				<h3 class="page-header text-success">Despesas do mês de <?php echo $mes_extenso . " " . $ano ?></h3>

				<p class="text-muted">Navegue sobre os meses para vizualizar suas despesas</p>
				<ul class="pagination">
					<li <?php if($mes == 1){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/01/">Jan</a></li>
					<li <?php if($mes == 2){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/02/">Fev</a></li>
					<li <?php if($mes == 3){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/03/">Mar</a></li>
					<li <?php if($mes == 4){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/04/">Abr</a></li>
					<li <?php if($mes == 5){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/05/">Mai</a></li>
					<li <?php if($mes == 6){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/06/">Jun</a></li>
					<li <?php if($mes == 7){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/07/">Jul</a></li>
					<li <?php if($mes == 8){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/08/">Ago</a></li>
					<li <?php if($mes == 9){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/09/">Set</a></li>
					<li <?php if($mes == 10){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/10/">Out</a></li>
					<li <?php if($mes == 11){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/11/">Nov</a></li>
					<li <?php if($mes == 12){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/<?php echo $ano ?>/12/">Dez</a></li>
				</ul>

				<ul class="pagination">
					<li><a href="<?php echo $home ?>/despesas/<?php echo $ano - 1 ?>/<?php echo $mes ?>/">&laquo;</a></li>
					<li class="active"><a href=""><?php echo $ano ?></a></li>
					<li><a href="<?php echo $home ?>/despesas/<?php echo $ano + 1 ?>/<?php echo $mes ?>/">&raquo;</a></li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-9">
				
				<h4 class="text-danger">Despesas gerais</h4>

				<div class="table-responsive">

					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Tipo</th>
								<th>Descrição</th>
								<th>Vencimento</th>
								<th>Pagamento</th>
								<th>Valor</th>
								<th class="text-center">Opções</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Inicia o contador
							$i = 1;
							while($sqlDespesas->fetch()){

								// Data de vencimento
								if(date("Y-m-d") == $data_vencimento){
									$data_vencimento = 'Hoje';
								} else{
									$array = explode("-", $data_vencimento);
									$data_vencimento = "$array[2]/$array[1]/$array[0]";
								}

								// Data de pagamento
								if($conta_paga == 'S'){
									$array = explode("-", $data_pagamento);
									$data_pagamento = "$array[2]/$array[1]/$array[0]";
									$str = "text-success";
								} else{
									$data_pagamento = "Pendente";
									$str = "text-danger";
								}

								// Converte para real o valor
								$valor_exibe = ' R$ ' . number_format($valor, 2, ',', '.');

								// Conta com cartão
								if(isset($desc_cartao)){
									$desc_despesa = 'Cartão de crédito';
									$descricao = $desc_cartao;
								}

								// Conta parcelada
								if(!isset($desc_cartao) && $parcelado == 'S')
									$descricao = $descricao . " " . $numero_parcela. "/" .$total_parcela;
								
								
								?>

								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo $desc_despesa ?></td>
									<td><?php echo $descricao ?></td>
									<td><?php echo $data_vencimento ?></td>
									<td class="<?php echo $str ?>"><strong><?php echo $data_pagamento ?></strong></td>
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

					<!-- Modal -->
					<div class="modal fade" id="modal_despesas" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content" id="modal_corpo">
							</div>
						</div>
					</div>

				</div> <!-- /.table-responsive -->

				<h4 class="text-danger">Despesas com cartão de crédito</h4>

				<div class="table-responsive">

					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>#</th>
								<th>Cartão</th>
								<th>Vencimento</th>
								<th>Pagamento</th>
								<th>Valor</th>
								<th class="text-center">Detalhes</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=1;
							while($sqlCartoes->fetch()){
								
								$array = explode("-", $data_vencimento);				
								$data_vencimento = "$array[2]/$array[1]/$array[0]";

								// Data de pagamento
								if($conta_paga == 'S'){
									$array = explode("-", $data_pagamento);
									$data_pagamento = "$array[2]/$array[1]/$array[0]";
									$str = "text-success";
								} else{
									$data_pagamento = "Pendente";
									$str = "text-danger";
								}
								
								$valor_exibe = ' R$ ' . number_format($valor, 2, ',', '.');
								
								?>
								<tr>
									<td><?php echo $i ?></td>
									<td><?php echo $desc_cartao ?></td>
									<td><?php echo $data_vencimento ?></td>
									<td class="<?php echo $str ?>"><strong><?php echo $data_pagamento ?></strong></td>
									<td><?php echo $valor_exibe ?></td>
									<td class="text-center">
										<a href="<?php echo $home ?>/cartao-credito/<?php echo $cartoes_id.'-'.$slogan ?>/<?php echo $ano ?>/<?php echo $mes ?>" class="btn btn-default btn-xs" title="Detalhes" alt="Detalhes"><i class="fa fa-folder-open-o fa-fw"></i></a>
									</td>
								</tr>
								
								<?php
								// Incrementa o contador
								$i++;
							} // fim do while			
							?>
							</tbody>
					</table>

				</div> <!-- /.table-responsive -->

			</div> <!-- /.col-md-9 -->

			<div class="col-md-3">

				<div class="panel panel-default">
					<div class="panel-heading"><strong><i class="fa fa-thumb-tack fa-fw"></i> Resumo mensal</strong></div>
					<div class="panel-body">
						<p>Despesas gerais <span class="pull-right"><?php echo ' R$ ' . number_format($soma_despesas_gerais, 2, ',', '.') ?></span></p>

						<p>Despesas com cartões <span class="pull-right"><?php echo ' R$ ' . number_format($soma_despesas_cartao, 2, ',', '.') ?></span></p>

						<p>Total despesas mês <span class="pull-right"><?php echo ' R$ ' . number_format($soma_despesa_mensal, 2, ',', '.') ?></span></p>

					</div>
				</div>

				<div class="alert alert-<?php echo $alert ?> text-center" role="alert">
					<i class="fa fa-<?php echo $icon ?> fa-fw"></i>
					<?php echo $msg ?>
				</div>

			</div> <!-- /.col-md-3 -->

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

    </script>

</body>
</html>