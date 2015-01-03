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

include "query/q-home.php";
include "includes/meses.php";
$convert_mes_abr = new MesesAbr;

?>

	<!-- Head -->
	<?php include "includes/head.php"; ?>

</head>
	
<body>
	
	<!-- Navbar -->
	<?php include "includes/navbar.php"; ?>

	<!-- Mensagem ao usuário -->
	<?php include "includes/bem-vindo.php"; ?>

	<!-- Container corpo -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="page-header text-success">Home</h3>
			</div>
		</div> <!-- /.row -->

		<div class="row">
			<div class="col-md-6">
				<h4 class="text-muted">Despesas próximas a vencer</h4>

				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab1" role="tab" data-toggle="tab"><i class="fa fa-list-alt fa-fw"></i> Despesas gerais</a></li>
					<li role="presentation"><a href="#tab2" role="tab" data-toggle="tab"><i class="fa fa-credit-card fa-fw"></i>  Despesas com cartões</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<!-- TAB 1 -->
					<div role="tabpanel" class="tab-pane active" id="tab1">

						<?php
						// Se não existir contas com vencimento próximas...
						if($sqlDespesas->num_rows() == 0){
							?>
							<p></p>
                            <p class="text-danger"><i class="fa fa-exclamation-triangle fa-fw"></i> No momento não há despesas para serem exibidas.</p>
							<?php
						} // fim do if
						else{

							?>
							
							<div class="table-responsive">

								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>Descrição</th>
											<th>Vencimento</th>
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
												<td><?php echo $descricao ?></td>
												<td><?php echo $data_vencimento ?></td>
												<td><?php echo $valor_exibe ?></td>
												<td class="text-center">
													<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'2' ?>" title="Visualizar registro" alt="Visualizar registro"><i class="fa fa-list-alt fa-fw"></i></a>
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

							<?php
						} // fim do else
						?>

					</div> <!-- /.tab1 -->

					<!-- TAB 2 -->
					<div role="tabpanel" class="tab-pane" id="tab2">
						
						<div class="table-responsive">

							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Cartão</th>
										<th>Vencimento</th>
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
					</div> <!-- /.tab2 -->
				</div> <!-- /.tab-content -->

			</div> <!-- /.col-md-6 -->

			<div class="col-md-6">
				<h4 class="text-muted">Gráficos e Estatísticas</h4>

				<p></p><hr>
				<h4>Cartão de crédito - <?php echo "$descricao_cartao $ano" ?></h4>
				<div id="morris-bar-chart1"></div>

				<h4>Despesa Mensal - <?php echo $ano ?></h4>
				<div id="morris-bar-chart2"></div>

				<p class="text-right"><a href="<?php echo $home ?>/graficos/"><i class="fa fa-level-up fa-fw"></i> Ver mais gráficos</a></p>


			</div> <!-- /.col-md-6 -->

		</div> <!-- /.row -->

	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "includes/footer.php"; ?>

	<link rel="stylesheet" href="<?php echo $home ?>/css/graph/morris-0.4.3.min.css">
	<script src="<?php echo $home ?>/js/graph/raphael-min.js"></script>
	<script src="<?php echo $home ?>/js/graph/morris-0.4.3.min.js"></script>

	<script type="text/javascript">

		Morris.Bar({
            element: 'morris-bar-chart1',
            data: [

                <?php
                while($sqlGraCartao->fetch()){
                    ?>
                    {
                        y: '<?php echo $convert_mes_abr->convert_mes_abr($mes) ?>',
                        a: <?php echo $valor ?>
                    }, 
                    <?php
                } // fim do while
                ?>
            ],

            xkey: 'y',
            ykeys: ['a'],
            labels: ['Valor'],
            hideHover: 'auto',
            resize: true
        });

        Morris.Bar({
            element: 'morris-bar-chart2',
            data: [

                <?php
                while($sqlGraDespesa->fetch()){
                    ?>
                    {
                        y: '<?php echo $convert_mes_abr->convert_mes_abr($mes) ?>',
                        a: <?php echo $valor ?>
                    }, 
                    <?php
                } // fim do while
                ?>
            ],

            xkey: 'y',
            ykeys: ['a'],
            labels: ['Valor'],
            hideHover: 'auto',
            resize: true
        });

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