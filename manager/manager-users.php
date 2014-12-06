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

include "../query/q-manager-users.php";

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
				
				<h2 class="page-header text-success">Gerenciar usuários</h2>

				<div class="row">
					<div class="col-md-12">

						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li class="active"><a href="#tab1" role="tab" data-toggle="tab">Usuários Ativos</a></li>
							<li><a href="#tab2" role="tab" data-toggle="tab">Novo usuário</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							
							<!-- tab1 -->
							<div class="tab-pane fade in active" id="tab1">
								<div class="row">
									<div class="col-md-12">
										<h2></h2>

										<div class="table-responsive">

				                            <table class="table table-striped table-hover" id="dataTables-example">
												<thead>
													<tr>
														<th width="2">#</th>
														<th></th>
														<th>Nome</th>
														<th>Email</th>
														<th>Login</th>
														<th>Nivel</th>
														<th>Acesso</th>
														<th>Ultimo acesso</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$i=1;
													while($sqlUsuarios->fetch()){

														// Acesso atual
														$array = explode(" ", $acesso_atual);
														$hora = substr($array[1], 0, 5);
														$data = explode("-", $array[0]);
														$acesso_atual = "$data[2]/$data[1]/$data[0]" . " às " . $hora;

														// Data de cadastro
														$array = explode(" ", $data_cadastro);
														$hora = substr($array[1], 0, 5);
														$data = explode("-", $array[0]);
														$data_cadastro = "$data[2]/$data[1]/$data[0]";

														// Bloqueado
														if($bloqueado == 'S'){
															$bloqueado = "<i class='fa fa-ban fa-fw'></i>";
															$str = 'bg-danger';
														}
														else{
															$bloqueado = ' ';
															$str = ' ';
														}

														// Status
														if($status == 1)
															$status = "<i class='fa fa-check fa-fw'></i>";
														else
															$status = "<i class='fa fa-times fa-fw'></i>";

														?>
														<tr class="<?php echo $str ?>">
															<td><?php echo $i ?></td>
															<td class="text-right">
																<?php echo $bloqueado . " " . $status ?>
															</td>
															<td><?php echo $nome ?></td>											
															<td><?php echo $email ?></td>
															<td><?php echo $login ?></td>
															<td><?php echo $nivel ?></td>
															<td><?php echo $numero_acesso ?></td>
															<td><?php echo $acesso_atual ?></td>
														</tr>
														<?php
														$i++;
													} // fim do while
													?>
												</tbody>
											</table>

		                       			</div> <!-- /.table-responsive -->
	                       			</div> <!-- /.col-md-12 -->
								</div> <!-- /.row -->
							</div> <!-- /.tab1 -->

							<!-- tab2 -->
							<div class="tab-pane fade" id="tab2">
								<div class="row">
									<div class="col-md-12">

										

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

</body>
</html>