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

include "../query/q-preferences.php";

?>

	<!-- Head -->
	<?php include "../includes/head.php"; ?>

	<!-- Page-Level Plugin CSS - Tables -->
    <link href="<?php echo $home ?>/css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

</head>
	
<body>
	
	<!-- Navbar -->
	<?php include "../includes/navbar.php"; ?>

	<!-- Mensagem ao usuário -->
	<?php include "../includes/bem-vindo.php"; ?>

	<!-- Container corpo -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				
				<h2 class="page-header text-success">Minhas preferências</h2>

				<div class="row">
					<div class="col-md-12">

						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li <?php if($tab == 1){ ?> class="active" <?php } ?>><a href="#tab1" role="tab" data-toggle="tab">Preferências de uso</a></li>
							<li <?php if($tab == 2){ ?> class="active" <?php } ?>><a href="#tab2" role="tab" data-toggle="tab">Cartão de crédito</a></li>
							<li <?php if($tab == 3){ ?> class="active" <?php } ?>><a href="#tab3" role="tab" data-toggle="tab">Tipos de despesas</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							
							<!-- tab1 -->
							<div <?php if($tab == 1){ ?> class="tab-pane fade in active" <?php }else{ ?> class="tab-pane fade" <?php } ?> id="tab1">
								<div class="row">

									<h2></h2>

									<div class="col-md-8">
						        		
										<div class="alert alert-<?php echo $alert1 ?> alert-dismissible <?php echo $display1 ?>" role="alert">
											<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<?php echo $msg1 ?>
										</div>
									
						        		<form method="post" action="" >
						        			<h4>Envio de email</h4>
						        			<div class="alert alert-info" role="alert">
												<i class="fa fa-info-circle fa-fw"></i>
												Certifique-se que sua caixa de entrada não bloqueará o email <strong>suporte@atitech.com.br</strong>
											</div>
											<ul class="list-group">
												<li class="list-group-item"><input type="checkbox" name="aviso_vencimento" value="S" <?php if($aviso_vencimento == 'S'){ ?> checked <?php } ?> > Deseja receber email com aviso de contas com vencimento próximo?</li>
												<li class="list-group-item"><input type="checkbox" name="relatorio_mensal" value="S" <?php if($relatorio_mensal == 'S'){ ?> checked <?php } ?> > Deseja receber por email um relatório de suas despesas pagas no mês?</li>
											</ul>

											<h4>Personalizar</h4>

											<ul class="list-group">
												<li class="list-group-item">
													A partir de quantos dias as despesas próximas deverão ser exibidas na página Home?
													<select name="dias_vencimento" class="pull-right">
														<option value="5" <?php if($dias_vencimento == 5){ ?> selected <?php } ?>>5 dias</option>
														<option value="10" <?php if($dias_vencimento == 10){ ?> selected <?php } ?>>10 dias</option>
														<option value="15" <?php if($dias_vencimento == 15){ ?> selected <?php } ?>>15 dias</option>
														<option value="20" <?php if($dias_vencimento == 20){ ?> selected <?php } ?>>20 dias</option>
													</select>
												</li>
											</ul>


											<div class="form-group">
												<button type="submit" class="btn btn-primary" name="btnSavePreferences" value="1"><span class="glyphicon glyphicon-save"></span> Salvar preferências</button>
											</div>
											
										</form>
						        	</div> <!-- /.col-md-12 -->

								</div> <!-- /.row -->
							</div> <!-- /.tab1 -->

							<!-- tab2 -->
							<div <?php if($tab == 2){ ?> class="tab-pane fade in active" <?php }else{ ?> class="tab-pane fade" <?php } ?> id="tab2">
								<div class="row">
									
									<h2></h2>

									<div class="col-md-9">
										
										<div class="alert alert-<?php echo $alert2 ?> alert-dismissible <?php echo $display2 ?>" role="alert">
											<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<?php echo $msg2 ?>
										</div>
										

										<div class="alert alert-info" role="alert">
											<i class="fa fa-info-circle fa-fw"></i>
											Para alterar seu cartão principal selecione-o na coluna <strong>Principal</strong> e clique no botão <strong>Alterar principal.</strong>
										</div>

										<div class="table-responsive">
											<form method="post" action="" >

											<table class="table table-striped table-hover" >
												<thead>
													<tr>
														<th>#</th>
														<th>Cartão</th>
														<th class="text-center">Número do cartão</th>
														<th class="text-center">Melhor data</th>
														<th class="text-center">Vencimento</th>
														<th class="text-center">Limite</th>
														<th class="text-center">Status</th>
														<th class="text-center">Principal</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i=1;
													while($sqlCartoes->fetch()){

														// Limite
														$limite = number_format($limite, 2, ',', '.');

														// Ativo
														if($ativo == 1)
															$ativo = 'Ativo';
														else
															$ativo = 'Inativo';

														// Codigo
														$prefixo = 'XXXX-XXXX-XXXX-';

														?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $descricao ?></td>
															<td class="text-center"><?php echo $prefixo . $codigo ?></td>
															<td class="text-center"><?php echo $melhor_data ?></td>
															<td class="text-center"><?php echo $data_vencimento ?></td>
															<td class="text-center"><?php echo $limite ?></td>
															<td class="text-center"><?php echo $ativo ?></td>
															<td class="text-center"><input type="radio" name="cartao_principal" value="<?php echo $id ?>" <?php if($ativo == 'Inativo'){ ?> disabled <?php } ?> <?php if($cartao_principal == $id){ ?> checked <?php } ?> ></td>
															<td>
																<button type="button" class="btn btn-default btn-xs" id="modal_edit_cartao" data-id="<?php echo $id ?>" data-toggle="modal" data-target="#modal_popup">
																	<i class="fa fa-edit fa-fw"></i>
																</button>
															</td>
														</tr>
														<?php
														$i++;
													} // fim do while
													?>
												</tbody>
											</table>

											<p class="pull-right"><button type="submit" class="btn btn-success" name="btnAlteraCartaoPrincipal" value="1"><i class="fa fa-star fa-fw"></i> Alterar principal</button></p>
											<p class="pull-left"><button type="button" class="btn btn-primary" id="modal_novo_cartao" data-toggle="modal" data-target="#modal_popup"><i class="fa fa-credit-card fa-fw"></i> Novo cartão</button></p>

											</form>

										</div> <!-- /.table-responsive -->

										<!-- Modal -->
										<div class="modal fade" id="modal_popup_ct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content" id="modal_content_ct">												
												</div> <!-- /.modal-content -->
											</div> <!-- /.modal-dialog -->
										</div> <!-- /.modal -->

										<!-- Modal -->
										<div class="modal fade" id="modal_popup_ct_e" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content" id="modal_content_ct_e">												
												</div> <!-- /.modal-content -->
											</div> <!-- /.modal-dialog -->
										</div> <!-- /.modal -->
									
									</div> <!-- /.col-md-9 -->
                       			
								</div> <!-- /.row -->
							</div> <!-- /.tab2 -->

							<!-- tab3 -->
							<div <?php if($tab == 3){ ?> class="tab-pane fade in active" <?php }else{ ?> class="tab-pane fade" <?php } ?> id="tab3">
								<div class="row">
									
									<h2></h2>

									<div class="col-md-4">

										<h4>Tipos de despesas cadastradas</h4>

										<div class="table-responsive">
											<table class="table table-striped table-hover ">
												<thead>
													<tr>
														<th>#</th>
														<th>Descrição</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i = 1;
													while($sqlTipo->fetch()){
														?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $descricao ?></td>
															<td><a class="btn btn-default btn-xs" id="modal_edit_despesa" data-id="<?php echo $tipo_despesa_id ?>" title="Editar registro" alt="Editar registro"><i class="fa fa-edit fa-fw"></i></a></td>
														</tr>
														<?php
														$i++;
													} // fim do while
													?>
												</tbody>
											</table>
										</div>

										<!-- Modal -->
										<div class="modal fade" id="modal_tipo" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content" id="modal_corpo">
												</div>
											</div>
										</div>

									</div> <!-- /.col-md-4 -->

									<div class="col-md-6 col-md-offset-1">
										<h4>Cadastrar tipo de despesa</h4>

										<p class="text-primary">Entre com a descrição desejada no campo abaixo:</p>

										<form class="form-horizontal" role="form" method="post" action="">
											<div class="form-group">
												<div class="col-md-6">
													<input type="text" name="descricao" class="form-control" required>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-6">
													<button type="submit" name="btnAddTipoDespesa" value="1" class="btn btn-primary"><i class="fa fa-save fa-fw"></i> Cadastrar</button>
												</div>
											</div>
										</form>

										<div class="alert alert-<?php echo $alert3 ?> alert-dismissible <?php echo $display3 ?>" role="alert">
											<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<?php echo $msg3 ?>
										</div>

									</div> <!-- /.col-md-6 -->
                       			
								</div> <!-- /.row -->
							</div> <!-- /.tab3 -->

						</div> <!--/.tab-contents -->

					</div> <!-- /.col-md-4 -->				

				</div> <!-- /.row -->

			</div>
		</div> <!-- /.row -->
	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "../includes/footer.php"; ?>

	<!-- Page-Level Plugin Scripts - Tables -->
    <script src="<?php echo $home ?>/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo $home ?>/js/dataTables/dataTables.bootstrap.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
	    $(document).ready(function() {
	        $('#dataTables-example').dataTable();
	    });

		// jquery para chamar a modal - novo cartao
		$("p").on('click',"#modal_novo_cartao", function(){
			$.post('<?php echo $home ?>/profile/modal-novo-cartao.php', function(retorno){
				$("#modal_popup_ct").modal({ backdrop: 'static' });
				$("#modal_content_ct").html(retorno);
			});
		});

		// jquery para chamar a modal - editar tipo de despesa
		$("table").on('click',"#modal_edit_despesa", function(){
			var id = $(this).attr('data-id'); // pega o id do botão
			$.post('<?php echo $home ?>/profile/modal-tipo-despesas.php', {id: id}, function(retorno){
				$("#modal_tipo").modal({ backdrop: 'static' });
				$("#modal_corpo").html(retorno);
			});
		});

		// jquery para chamar a modal - editar tipo de despesa
		$("table").on('click',"#modal_edit_cartao", function(){
			var id = $(this).attr('data-id'); // pega o id do botão
			$.post('<?php echo $home ?>/profile/modal-edit-cartao.php', {id: id}, function(retorno){
				$("#modal_popup_ct_e").modal({ backdrop: 'static' });
				$("#modal_content_ct_e").html(retorno);
			});
		});

    </script>

</body>
</html>