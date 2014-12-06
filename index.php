<?php include "includes/globais.php"; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<title>Controle Financeiro V 2.0</title>

	<!-- Arquivos CSS -->
	<link href="css/bootstrap.css" rel="stylesheet">

	<!-- Optional theme -->
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">

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
				<a href="#"><img src="img/logo-navbar.png" class="img-responsive" height="85" width="500" alt="Home" title="Home" ></a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<form class="navbar-form navbar-right" role="form" action="login/autentica.php" method="post" >
					<div class="form-group">
						<input type="text" name="login" placeholder="Login" class="form-control input-sm" required >
					</div>
					<div class="form-group">
						<input type="password" name="senha" placeholder="Senha" class="form-control input-sm" required >
					</div>
					<button type="submit" class="btn btn-success btn-sm" name="btnLogar" value="1" ><i class="fa fa-sign-in fa-fw"></i> Entrar</button>
					<div class="help-block">
						<input type="checkbox" name="lembrar_senha" value="1" > Lembrar senha | 
						<a href="#">Esqueceu sua senha?</a>
					</div>
				</form>
			</div><!-- /.navbar-collapse -->

		</div> <!-- /.container -->

	</nav> <!-- /.navbar -->

	<div class="jumbotron">
		<div class="container">
			<div class="row">

				<div class="col-md-6">
					<p><strong>Controle Financeiro ©</strong> foi desenvolvido para você ter o controle total de suas contas, gerenciando suas despesas pessoais e tendo uma visão ampla de seus gastos de uma maneira precisa e detalhada.</p>
					<p><a class="btn btn-primary btn-lg" role="button" href="profile/new/" >Criar conta &raquo;</a></p>
				</div>

				<div class="col-md-1"></div>

				<div class="col-md-5">
					<img src="./img/logo-index.jpg" class="img-responsive img-thumbnail" alt="Controle Financeiro" title="Controle Financeiro" height="300" width="460" >
				</div>

			</div>
		</div>
	</div>


	<?php include "includes/footer.php"; ?>

</body>
</html>