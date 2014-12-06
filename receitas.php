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

include "query/q-receitas.php";
include "includes/meses.php";
$convert_mes_abr = new MesesAbr;
$convert_mes = new Meses;

?>

	<!-- Head -->
	<?php include "includes/head.php"; ?>

	<!-- Datepicker -->
	<link rel="stylesheet" href="<?php echo $home ?>/css/datepicker.css">

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
				<h3 class="page-header text-success">Receitas</h3>
			</div>
		</div> <!-- /.row -->

		<div class="row">
			<div class="col-md-4">
				<form class="form-horizontal" role="form" method="post" action="">
					<div class="form-group">
						<label class="col-md-4 control-label">Mês</label>
						<div class="col-md-8">
							<select name="mes" class="form-control" >
								<option value="<?php echo date("m") ?>"><?php echo $convert_mes->convert_mes(date("m")) ?></option>
								<option value="01">Janeiro</option>
								<option value="02">Fevereiro</option>
								<option value="03">Março</option>
								<option value="04">Abril</option>
								<option value="05">Maio</option>
								<option value="06">Junho</option>
								<option value="07">Julho</option>
								<option value="08">Agosto</option>
								<option value="09">Setembro</option>
								<option value="10">Outubro</option>
								<option value="11">Novembro</option>
								<option value="12">Dezembro</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Ano</label>
						<div class="col-md-8">
							<input name="ano" id="ano" value="<?php echo date('Y') ?>" class="form-control" maxlength="4">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Data Receita</label>
						<div class="col-md-8">
							<input name="data_receita" id="data_receita" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Valor</label>
						<div class="col-md-8">
							<input name="valor" id="valor" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Tipo Receita</label>
						<div class="col-md-8">
							<select name="tipo_receita" class="form-control">
								<option value="Salário">Salário</option>
								<option value="Outros">Outros</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-4 control-label">Observações</label>
						<div class="col-md-8">
							<input name="obs" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-4 col-md-8">
							<button type="submit" name="btnCadastrar" value="1" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Cadastrar</button>
						</div>
					</div>
				</form>
			</div> <!-- /.col-md-4 -->

			<div class="col-md-8">

				<!-- Nav tabs -->
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#tab1" role="tab" data-toggle="tab"><i class="fa fa-bar-chart fa-fw"></i> Gráfico</a></li>
					<li role="presentation"><a href="#tab2" role="tab" data-toggle="tab"><i class="fa fa-list-ul fa-fw"></i>  Detalhes</a></li>
				</ul>

				<!-- Tab panes -->
				<div class="tab-content">
					<!-- TAB 1 -->
					<div role="tabpanel" class="tab-pane active" id="tab1">

						<h4 class="text-center text-muted">Últimas receitas</h4>
						<div id="morris-bar-chart1"></div>

					</div> <!-- /.tab1 -->

					<!-- TAB 2 -->
					<div role="tabpanel" class="tab-pane" id="tab2">

						<h4 class="text-muted">Visualizando detalhes das últimas receitas</h4>

						<div class="table-responsive">

							<table class="table table-striped table-condensed table-hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Mês</th>
										<th>Ano</th>
										<th>Receita</th>
										<th class="text-center">Opções</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// Inicia o contador
									$i = 1;
									while($sqlDetalhes->fetch()){

										// Converte para real o valor
										$valor_exibe = ' R$ ' . number_format($valor, 2, ',', '.');

										?>

										<tr>
											<td><?php echo $i ?></td>
											<td><?php echo $convert_mes->convert_mes($mes) ?></td>
											<td><?php echo $ano ?></td>
											<td><?php echo $valor_exibe ?></td>
											<td class="text-center">
												<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'1' ?>" title="Editar registro" alt="Editar registro"><i class="fa fa-pencil fa-fw"></i></a>
												<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'2' ?>" title="Visualizar registro" alt="Visualizar registro"><i class="fa fa-list-alt fa-fw"></i></a>
												<a class="btn btn-default btn-xs" id="modal_popup" data-id="<?php echo $parcelas_id."-".'3' ?>" title="Remover registro" alt="Remover registro"><i class="fa fa-trash fa-fw"></i></a>
											</td>
										</tr>

										<?php
										// Incrementa o contador
										$i++;
									} // fim do while
									?>
								</tbody>
							</table>

						</div> <!-- /.table-responsive -->

					</div> <!-- /.tab2 -->
				</div> <!-- /.tab-content -->

			</div> <!-- /.col-md-8 -->

		</div> <!-- /.row -->

	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "includes/footer.php"; ?>

	<!-- Datepicker -->
	<script src="<?php echo $home ?>/js/bootstrap-datepicker.js" type="text/javascript"></script>

	<!-- Arquivos JS Maskaras -->
    <script src="<?php echo $home ?>/js/jquery.maskMoney.js"></script>

	<link rel="stylesheet" href="<?php echo $home ?>/css/graph/morris-0.4.3.min.css">
	<script src="<?php echo $home ?>/js/graph/raphael-min.js"></script>
	<script src="<?php echo $home ?>/js/graph/morris-0.4.3.min.js"></script>

	<script type="text/javascript">

		// Datas - Datepicker
        $(document).ready(function () {
            $('#data_receita').datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            });
        });

        // Maskaras
        $(function(){
            $("#valor").maskMoney({symbol:'R$ ', 
            showSymbol:false, thousands:'.', decimal:',', symbolStay: true});
            $("#ano").mask("9999");
        })

		Morris.Bar({
            element: 'morris-bar-chart1',
            data: [

                <?php
                while($sqlGraReceita->fetch()){
                    ?>
                    {
                        y: '<?php echo $convert_mes_abr->convert_mes_abr($mes) ?>, <?php echo $ano ?>',
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