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

include "../query/q-profile.php";

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
				
				<h2 class="page-header text-success">Meu perfil</h2>

				<div class="row">
					<div class="col-md-12">

						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li class="active"><a href="#tab1" role="tab" data-toggle="tab">Perfil</a></li>
							<li><a href="#tab2" role="tab" data-toggle="tab">Meus acessos</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							
							<!-- tab1 -->
							<div class="tab-pane fade in active" id="tab1">
								<div class="row">

									<h2></h2>

									<div class="col-md-3">
										<img src="<?php echo $home ?>/img/avatar/<?php echo $avatar ?>" class="img-thumbnail img-responsive" title="<?php echo $nome ?>" alt="<?php echo $nome ?>" width="250" >
										<h4 class="text-muted"><?php echo $nome ?></h4>
										<button type="button" class="btn btn-success"><i class="fa fa-edit fa-fw"></i>Editar cadastro</button>
									</div> <!-- /.col-md-4 -->

									<div class="col-md-4">
										<p><label>Nome:</label> <?php echo $nome ?></p>
										<p><label>Email:</label> <?php echo $email ?></p>
										<p><label>Login de acesso:</label> <?php echo $login ?></p>
										<p><label>Perfil:</label> <?php echo $nivel ?></p>
										<p><label>Número de acessos:</label> <?php echo $numero_acesso ?></p>
										<p><label>Acesso atual:</label> <?php echo $acesso_atual ?></p>
										<p><label>Data de cadastro:</label> <?php echo $data_cadastro ?></p>
									</div> <!-- /.col-md-4 -->

									<div class="col-md-5">

										<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
											<!-- Painel #1 -->
											<div class="panel panel-default">
												<div class="panel-heading" role="tab" id="heading1">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
															<i class="fa fa-refresh fa-fw"></i> Alterar Senha
														</a>
													</h4>
												</div>
												<div id="collapse1" class="panel-collapse collapse <?php echo $collapsein ?>" role="tabpanel" aria-labelledby="heading1">
													<div class="panel-body">

														<div class="alert alert-<?php echo $alert ?> alert-dismissible <?php echo $display ?>" role="alert">
															<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
															<?php echo $msg_error ?>
														</div>

														<form class="form-horizontal" role="form" method="post" action="">
															<div class="form-group">
																<label class="col-md-4 control-label">Senha atual</label>
																<div class="col-md-8">
																	<input type="password" name="senha_atual" class="form-control" required>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label">Nova senha</label>
																<div class="col-md-8">
																	<input type="password" name="senha1" class="form-control" required>
																</div>
															</div>

															<div class="form-group">
																<label class="col-md-4 control-label">Repita a senha</label>
																<div class="col-md-8">
																	<input type="password" name="senha2" class="form-control" required>
																</div>
															</div>

															<div class="form-group">
																<div class="col-md-offset-4 col-md-8">
																	<button type="submit" class="btn btn-success" name="btnAlterarSenha" value="1"><i class="fa fa-save fa-fw"></i> Alterar</button>
																</div>
															</div>
														</form>

													</div> <!-- /.panel-body -->
												</div> <!-- /.collapse1 -->
											</div> <!-- /.painel #1 -->

											<!-- Painel #2 -->
											<div class="panel panel-default">
												<div class="panel-heading" role="tab" id="heading2">
													<h4 class="panel-title">
														<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
															<i class="fa fa-trash fa-fw"></i> Remover minha conta
														</a>
													</h4>
												</div>
												<div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
													<div class="panel-body">

													<p class="text-danger">Atenção. Essa opção irá remover sua conta e apagar todas suas informações salvas. Essa ação não pode ser desfeita.</p>
													<p><strong>Tem certeza que deseja remover sua conta e apagar todas as suas informações?</strong></p>

													<form class="form-horizontal" role="form" method="post" action="">
														<div class="form-group">
															<div class="col-md-6">
																<input type="password" name="senha_remove" class="form-control" placeholder="Entre com sua senha" required>
															</div>
														</div>
														<div class="form-group">
															<div class="col-md-8">
																<button type="submit" class="btn btn-danger" name="btnRemoverConta" value="1"><i class="fa fa-trash fa-fw"></i> Remover</button>
															</div>
														</div>
													</form>


													</div> <!-- /.panel-body -->
												</div> <!-- /.collapse1 -->
											</div> <!-- /.painel #2 -->

										</div> <!-- /.panel-group -->
									</div> <!-- /.col-md-4 -->

								</div> <!-- /.row -->
							</div> <!-- /.tab1 -->

							<!-- tab2 -->
							<div class="tab-pane fade" id="tab2">
								<div class="row">
									<div class="col-md-12">

										<h2></h2>

										<div class="table-responsive">

				                            <table class="table table-striped table-hover" id="dataTables-example">
												<thead>
													<tr>
														<th width="2">#</th>
														<th>Data de acesso</th>
														<th>Máquina</th>
														<th>IP</th>
														<th class="text-right">Localização</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i=1;
													while($sqlAcessos->fetch()){

														// Acesso atual
														$array = explode(" ", $data_acesso);
														$hora = substr($array[1], 0, 5);
														$data = explode("-", $array[0]);
														$data_acesso = "$data[2]/$data[1]/$data[0]" . " às " . $hora;

														$latlon = $latitude . "," . $longitude;

														?>
														<tr>
															<td><?php echo $i ?></td>
															<td><?php echo $data_acesso ?></td>											
															<td><?php echo $hostname ?></td>
															<td><?php echo $ip ?></td>
															<td class="text-right">
																<a class="btn btn-success btn-xs" id="modal_popup" data-id="<?php echo $latitude."&".$longitude ?>" title="Visualizar localização" alt="Visualizar localização"><i class="fa fa-globe fa-fw"></i> Onde eu estava?</a>
															</td>
														</tr>
														<?php
														$i++;
													} // fim do while
													?>
												</tbody>
											</table>

		                       			</div> <!-- /.table-responsive -->

		                       			<!-- Modal -->
										<div class="modal fade" id="modal_localiza" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content" id="modal_corpo">
												</div>
											</div>
										</div>

                       				</div> <!-- /.col-md-12 -->
								</div> <!-- /.row -->
							</div> <!-- /.tab2 -->

						</div>

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

    <script type="text/javascript">
		// jquery para chamar a modal
		$("table").on('click',"#modal_popup", function(){
			var id = $(this).attr('data-id'); // pega o id do botão
			$.post('<?php echo $home ?>/profile/modal-localiza.php', {id: id}, function(retorno){
				$("#modal_localiza").modal({ backdrop: 'static' });
				$("#modal_corpo").html(retorno);
			});
		});
    </script>

</body>
</html>