<?php

// Seta o menu com active
$menu_active = explode("/", $_SERVER ['PHP_SELF']);

?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo $home ?>/home/">Controle Financeiro</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li <?php if($menu_active[2] == 'home.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/home/"><i class="fa fa-home fa-fw"></i> Home</a></li>
				<li <?php if($menu_active[2] == 'receitas.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/receitas/"><i class="fa fa-money fa-fw"></i> Receitas</a></li>
				<li <?php if($menu_active[2] == 'lancamentos.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/lancamentos/"><i class="fa fa-plus-circle fa-fw"></i> Lançamentos</a></li>
				<li <?php if($menu_active[2] == 'despesas.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/despesas/"><i class="fa fa-list-alt fa-fw"></i> Despesas</a></li>
				<li <?php if($menu_active[2] == 'cartoes.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/cartao-credito/"><i class="fa fa-credit-card fa-fw"></i> Cartões</a></li>
				<li <?php if($menu_active[2] == 'graficos.php'){ ?> class="active" <?php } ?> ><a href="<?php echo $home ?>/graficos/"><i class="fa fa-bar-chart fa-fw"></i> Gráficos</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Opções <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="<?php echo $home ?>/profile/"><i class="fa fa-user fa-fw"></i> Meu perfil</a></li>
						<li><a href="#"><i class="fa fa-pencil-square-o fa-fw"></i> Minhas preferências</a></li>
						<?php if($_SESSION['id'] == 1){ ?>
						<li class="divider"></li>
						<li><a href="<?php echo $home ?>/manager/users/"><i class="fa fa-users fa-fw"></i> Gerencias usuários</a></li>
						<?php } ?>
						<li class="divider"></li>
						<li><a href="<?php echo $home ?>/includes/logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
