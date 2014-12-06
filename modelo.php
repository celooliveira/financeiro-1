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
				<h1 class="page-header">Titulo aqui <small>- Resumo aqui</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo $home ?>/home/"><i class="fa fa-home fa-fw"></i> Home</a></li>
					<li class="active"><i class="fa fa-list-alt fa-fw"></i> Despesas</li>
				</ol>
			</div>
		</div> <!-- /.row -->
	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "includes/footer.php"; ?>

</body>
</html>