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
							<li <?php if($tab == 3){ ?> class="active" <?php } ?>><a href="#tab3" role="tab" data-toggle="tab">Tipos de despesa</a></li>
							<li <?php if($tab == 4){ ?> class="active" <?php } ?>><a href="#tab4" role="tab" data-toggle="tab">Formas de pagamento</a></li>
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

									<div class="col-md-8">
										
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
														<th class="text-center">Código</th>
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
														if($codigo == 0)
															$prefixo = '000';
														else
															$prefixo = '';

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
																<a href="">
																	<button type="button" class="btn btn-default btn-xs">
																		<i class="fa fa-edit fa-fw"></i>
																	</button>
																</a>
															</td>
														</tr>
														<?php
														$i++;
													} // fim do while
													?>
												</tbody>
											</table>

											<p class="pull-right"><button type="submit" class="btn btn-success" name="btnAlteraCartaoPrincipal" value="1"><i class="fa fa-star fa-fw"></i> Alterar principal</button></p>
											<p class="pull-left"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_popup"><i class="fa fa-credit-card fa-fw"></i> Novo cartão</button></p>

											</form>

										</div> <!-- /.table-responsive -->

										<!-- Modal -->
										<div class="modal fade" id="modal_popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">

													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
														<h4 class="modal-title" id="myModalLabel">Adicionar novo cartão</h4>
													</div> <!-- /.modal-header -->

													<div class="modal-body">

														<p>Entre com as informações abaixo:</p>

														<form method="post" class="form-horizontal" role="form" action="<?php echo $home ?>/profile/index-preferences.php">
															<div class="form-group">
																<label class="col-md-3 control-label">Cartão</label>
																<div class="col-md-6"> 
																	<input type="text" name="cartao" class="form-control" placeholder="Ex: Caixa Federal, Santander" required >
																</div>
															</div>
															<div class="form-group">
																<label class="col-md-3 control-label">Número cartão</label>
																<div class="col-md-6">
																	<input type="text" name="codigo" id="codigo" class="form-control" maxlength="4" placeholder="Os 4 últimos digitos" required >
																</div>
															</div>
															<div class="form-group">
																<label class="col-md-3 control-label">Melhor data</label>
																<div class="col-md-6">
																	<input type="text" name="melhor_data" id="melhor_data" class="form-control" placeholder="Dia em que o cartão fecha" maxlength="2" required >
																</div>
															</div>
															<div class="form-group">
																<label class="col-md-3 control-label">Vencimento</label>
																<div class="col-md-6">
																	<input type="text" name="data_vencimento" id="data_vencimento" class="form-control" placeholder="Data do vencimento do cartão" maxlength="2" required >
																</div>
															</div>
															<div class="form-group">
																<label class="col-md-3 control-label">Limite</label>
																<div class="col-md-6">
																	<input type="text" name="limite" id="limite" class="form-control" placeholder="Limite disponível para o cartão" required >
																</div>
															</div>
														</form>

														<p class="text-info"><i class="fa fa-exclamation-circle fa-fw"></i> Essas informações serão utilizadas apenas como informativos e para cadastramentos de despesas realizados nos respectivos cartões.</p>

													</div> <!-- /.modal-body -->

													<div class="modal-footer">
														<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
														<button type="submit" name="btnAddNovoCartao" value="1" class="btn btn-success"><i class="fa fa-plus fa-fw"></i> Adicionar</button>
													</div> <!-- /.modal-footer -->

												</div> <!-- /.modal-content -->
											</div> <!-- /.modal-dialog -->
										</div> <!-- /.modal -->
									
									</div> <!-- /.col-md-8 -->
                       			
								</div> <!-- /.row -->
							</div> <!-- /.tab2 -->

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
    </script>

</body>
</html>