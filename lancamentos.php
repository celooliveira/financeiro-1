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

include "query/q-lancamentos.php";

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
			<div class="col-md-8">

				<h3 class="page-header text-success">Cadastrar despesas</h3>

				<div class="alert alert-<?php echo $alert ?> alert-dismissable <?php echo $display ?> ">
					<i class="fa fa-<?php echo $icon ?> fa-fw"></i>
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<?php echo $msg ?>
				</div>
					
				<form class="form-horizontal" role="form" method="post" action="">
					<div class="form-group">
						<label class="col-md-3 control-label">Tipo de Despesa</label>
						<div class="col-md-4">
							<select class="form-control input-sm" name="tipo_despesa_id" >
								<?php while($sqlTipoDespesa->fetch()){ ?>
									<option value="<?php echo $id ?>"><?php echo $descricao ?></option>
								<?php }	?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Data Despesa</label>
						<div class="col-md-4">
							<input type="text" class="form-control input-sm" name="data_despesa" autocomplete="off" id="data_despesa" >
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">* Forma de Pagamento</label>
						<div class="col-md-4">
							<select class="form-control input-sm" name="forma_pagamento_id" id="selectCartaoCredito" required>
								<option value="">Selecione</option>
								<?php while($sqlPagamentos->fetch()){ ?>
									<option value="<?php echo $id ?>"><?php echo $descricao ?></option>
								<?php }	?>
							</select>
						</div>
					</div>

					<div id="ShowCartaoCredito"></div>
			
					<!--<div class="form-group">
						<label class="col-md-3 control-label">* Vencimento</label>
						<div class="col-md-4">
							<input type="text" class="form-control input-sm" name="data_vencimento" autocomplete="off" id="data_vencimento" required >
						</div>
					</div>-->

					<div class="form-group">
						<label class="col-md-3 control-label">Parcelado</label>
						<div class="col-md-4">
							<select class="form-control input-sm" name="parcelado" id="parcelado" onchange="habTotalParcela(this.value)">
								<option value="N">Não</option>
								<option value="S">Sim</option>
							</select>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control input-sm numeros" name="total_parcela" id="total_parcela" maxlength="3" disabled placeholder="Num parcelas" >
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">* Descrição</label>
						<div class="col-md-4">
							<input type="email" class="form-control input-sm" name="descricao" required >
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">* Valor</label>
						<div class="col-md-4">
							<input type="text" class="form-control input-sm" name="valor" id="valor" autocomplete="off" required >
						</div> 
						<div class="col-md-5">
							<p class="help-block">Caso valor seja parcelado, lançar o valor que será pago mensal</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Conta Fixa</label>
						<div class="col-md-4">
							<select class="form-control input-sm" name="conta_fixa">
								<option value="N">Não</option>
								<option value="S">Sim</option>
							</select>
						</div>
						<div class="col-md-5">
							<p class="help-block">Será cadastrada para os próximos 12 meses</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Conta Paga</label>
						<div class="col-md-4">
							<select class="form-control input-sm" name="conta_paga" id="pago" onchange="habDataPagamento(this.value)">
								<option value="N">Não</option>
								<option value="S">Sim</option>
							</select>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control input-sm" autocomplete="off" name="data_pagamento" id="data_pagamento" placeholder="Data pagamento" disabled >
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 control-label">Observações</label>
						<div class="col-md-7">
							<input type="text" class="form-control input-sm" name="obs" >
						</div>
					</div>
					
					<div class="form-group text-center">
						<button type="submit" class="btn btn-success" name="btncadastrar" value="1"><i class="fa fa-save fa-fw"></i> Cadastrar</button>
						<button type="reset" class="btn btn-success"><i class="fa fa-reply fa-fw"></i> Limpar campos</button>
					</div>

				</form> <!-- fim do form -->		

			</div> <!-- /.col-md-8 -->

			<div class="col-md-4">
				<h3 class="page-header text-success">Suas últimas despesas</h3>

				<ul class="list-group">
					<?php 
					while($sqlUltimas->fetch()){

						// Data de vencimento
						$array = explode("-", $data_vencimento);
						$data_vencimento = "$array[2]/$array[1]/$array[0]";

						// Converte para real o valor
						$valor = ' R$ ' . number_format($valor, 2, ',', '.'); 

						if($desc_cartao == ''){
							$desc_cartao = ' ';
						} else{
							$desc_cartao = ' - ' . $desc_cartao;
						}

						?>
						
						<li class="list-group-item">
							<h4 class="list-group-item-heading"><?php echo $descricao ?></h4>
							<p class="list-group-item-text">Valor: <?php echo $valor ?></p>
							<p class="list-group-item-text">Vencimento: <?php echo $data_vencimento ?></p>
							<p class="list-group-item-text">Forma Pagamento: <?php echo $desc_forma_pagamento . $desc_cartao ?></p>
						</li>
						
						<?php
					} // fim do while
					?>
				</ul>

			</div>

		</div> <!-- /.row -->

	</div> <!-- /.container -->

	<!-- Footer -->
	<?php include "includes/footer.php"; ?>

	<!-- Datepicker -->
	<script src="<?php echo $home ?>/js/bootstrap-datepicker.js" type="text/javascript"></script>

	<!-- Arquivos JS Maskaras -->
    <script src="<?php echo $home ?>/js/jquery.maskMoney.js"></script>
    <!--<script src="<?php echo $home ?>/js/numeros.js"></script>-->
    <script src="<?php echo $home ?>/js/jqBootstrapValidation.js"></script>

	<script type="text/javascript">
	
		// Validação Forms - bootstrap
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );

		// Datas - Datepicker
        $(document).ready(function () {
            
            $('#data_despesa').datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            });

            $('#data_pagamento').datepicker({
                format: "dd/mm/yyyy",
                autoclose: true
            }); 

            // Campos seleciona os cartoes de creditos
            $('#selectCartaoCredito').change(function(){
				$('#ShowCartaoCredito').load('<?php echo $home ?>/query/q-select_cartao.php?id='+$('#selectCartaoCredito').val() );
			});			
        
        });

		// Maskaras
		$(function() {
			$("#valor").maskMoney({symbol:'R$ ', showSymbol:false, thousands:'.', decimal:',', symbolStay: true});
            $("#total_parcela").mask("?999");
        });

		// Ativa/Desativa campos
	   	function habTotalParcela(elemento){  
		    if(elemento == 'S'){  
		        $('#total_parcela').prop('disabled', false); // Desabilita o input
		    } else{  
		        $('#total_parcela').prop('disabled', true); // Habilita o input
		    }  
		}

		function habDataPagamento(elemento){  
		    if(elemento == 'S'){  
		        $('#data_pagamento').prop('disabled', false); // Desabilita o input
		    } else{  
		        $('#data_pagamento').prop('disabled', true); // Habilita o input
		    }  
		}

	</script>

</body>
</html>