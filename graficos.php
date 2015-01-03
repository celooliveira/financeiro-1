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

include "query/q-graficos.php";
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
				
				<h3 class="page-header text-success">Gráficos</h3>

			</div>
		</div> <!-- /.row -->

		<div class="row">
			<div class="col-md-6">
				
				<h4>Cartão de crédito - Caixa federal <?php echo $ano ?></h4>
				<div id="morris-bar-chart1"></div>

			</div>

			<div class="col-md-6">
				
				<h4>Despesa Mensal - <?php echo $ano ?></h4>

				<div id="morris-bar-chart2"></div>

			</div>
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

    </script>

</body>
</html>