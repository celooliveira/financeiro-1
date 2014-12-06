<?php include "../includes/globais.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title>Controle Financeiro V 2.0</title>

	<!-- Arquivos CSS -->
	<link href="<?php echo $home ?>/css/bootstrap.css" rel="stylesheet">

	<!-- Optional theme -->
	<link href="<?php echo $home ?>/css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="<?php echo $home ?>/css/font-awesome.min.css" rel="stylesheet">

</head>
	
<body>
	
	<!-- NavBar -->
	<nav class="navbar navbar-default navbar-static-top" role="navigation">
		<div class="container">

			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="<?php echo $home ?>"><img src="<?php echo $home ?>/img/logo-navbar.png" class="img-responsive" height="85" width="500" alt="Home" title="Home" ></a>
			</div>

		</div> <!-- /.container -->

	</nav> <!-- /.navbar -->


	<?php include "../includes/footer.php"; ?>

</body>
</html>