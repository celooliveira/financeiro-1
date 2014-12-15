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

$id = (int)$_POST['id'];

// Busca cartao
$sqlCartoes = $conectar->prepare("
	SELECT id, descricao, codigo, melhor_data, data_vencimento, limite, ativo
	FROM cartoes
	WHERE usuarios_id = ?
	AND id = ? ") or die (mysqli_error($conectar));
$sqlCartoes->bind_param('ii', $usuarioID, $id);
$sqlCartoes->execute();
$sqlCartoes->store_result();
$sqlCartoes->bind_result($id, $descricao, $codigo, $melhor_data, $data_vencimento, $limite, $ativo);
$sqlCartoes->fetch();

// Limite
$limite = number_format($limite, 2, ',', '.');

?>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Editar cartão cadastrado</h4>
</div> <!-- /.modal-header -->

<form method="post" class="form-horizontal" role="form" action="">

	<div class="modal-body">

		<p>Editar informações:</p>

			<div class="form-group">
				<label class="col-md-3 control-label">Cartão</label>
				<div class="col-md-6"> 
					<input type="text" name="cartao" class="form-control" value="<?php echo $descricao ?>" required >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Número cartão</label>
				<div class="col-md-6">
					<input type="text" name="codigo" class="form-control numeros" maxlength="4" value="<?php echo $codigo ?>" required >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Melhor data</label>
				<div class="col-md-6">
					<input type="text" name="melhor_data" class="form-control numeros" maxlength="2" value="<?php echo $melhor_data ?>" required >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Vencimento</label>
				<div class="col-md-6">
					<input type="text" name="data_vencimento" class="form-control numeros" maxlength="2" value="<?php echo $data_vencimento ?>" required >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Limite</label>
				<div class="col-md-6">
					<input type="text" name="limite" id="limite" class="form-control" value="<?php echo $limite ?>" required >
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">Status</label>
				<div class="col-md-6">
					<select name="ativo" class="form-control">
						<option value="1" <?php if($ativo == 1){ ?> selected <?php } ?> >Ativo</option>
						<option value="0" <?php if($ativo == 0){ ?> selected <?php } ?> >Inativo</option>
					</select>
				</div>
			</div>

		<p class="text-info"><i class="fa fa-exclamation-circle fa-fw"></i> Essas informações serão utilizadas apenas como informativos e para cadastramentos de despesas realizados nos respectivos cartões.</p>

	</div> <!-- /.modal-body -->

	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
		<button type="submit" name="btnEditCartao" value="1" class="btn btn-success"><i class="fa fa-refresh fa-fw"></i> Alterar Informações</button>
		<input type="hidden" name="cartoes_id" value="<?php echo $id ?>" >
	</div> <!-- /.modal-footer -->

</form>

<!-- Arquivos JS Maskaras -->
<script src="<?php echo $home ?>/js/jquery.maskMoney.js"></script>
<script src="<?php echo $home ?>/js/numeros.js"></script>

<script>
	// Maskaras
	$(function() {
		$("#limite").maskMoney({symbol:'R$ ', showSymbol:false, thousands:'.', decimal:',', symbolStay: true});
    });
</script>