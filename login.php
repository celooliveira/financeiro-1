<?php

include "includes/globais.php";

// Verifica se foi enviado parametro por GET
if(isset($_GET['error'])){

	$var = $_GET['error'];

	$msg2 = ' novamente';

	switch ($var) {
		case 0: $error = '<i class="fa fa-times fa-fw"></i> Usuário ou senha incorretos.'; break;
		case 1: $error = '<i class="fa fa-times fa-fw"></i> Seu usuário foi bloqueado pelo Administrador.'; break;
		case 2: $error = '<i class="fa fa-times fa-fw"></i> Sua sessão expirou.'; break;
		case 3: $error = '<i class="fa fa-times fa-fw"></i> Sua conta foi inativada pelo Administrador.'; break;
		case 4: $error = '<i class="fa fa-times fa-fw"></i> Você precisa estar logado para acessar o sistema.'; break;
		default: $error = ''; $msg2 = ''; break;
	}

} 


?>
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

	<div class="jumbotron">
		<div class="container">
			<div class="row">

				<div class="col-md-4 col-md-offset-4">

					<h3 class="page-header">Área de Login</h3>

					<?php if(isset($_GET['error'])){ ?>
						<h4 class="text-danger"><?php echo $error ?></h4>
					<?php } ?>

					<h4 class="text-muted">Entre com seu usuário e senha <?php echo $msg2 ?></h4>

					<form role="form" method="post" action="<?php echo $home ?>/login/autentica.php">
						<div class="form-group">
							<label>Login</label>
							<input type="text" class="form-control" name="login" required autofocus >
						</div>
						<div class="form-group">
							<label>Senha</label>
							<input type="password" class="form-control" name="senha" required >
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" name="lembrar_senha" value="1" > Lembrar senha | <a href="">Esqueceu sua senha?</a>
							</label>
						</div>
						<button type="submit" class="btn btn-success" name="btnLogar" value="1" ><i class="fa fa-sign-in fa-fw"></i> Entrar</button>
					</form>

				</div>

			</div>
		</div>
	</div>


	<?php include "includes/footer.php"; ?>

</body>
</html>