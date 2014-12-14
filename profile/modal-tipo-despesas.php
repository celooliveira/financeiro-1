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

// Busca tipo de despesa
$sqlTipo = $conectar->prepare("SELECT descricao FROM tipo_despesa WHERE id = ? ");
$sqlTipo->bind_param('i', $id);
$sqlTipo->execute();
$sqlTipo->store_result();
$sqlTipo->bind_result($descricao);
$sqlTipo->fetch();

?>


<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title" id="myModalLabel">Editar tipo de despesa cadastrada</h4>
</div> <!-- /.modal-header -->

<form method="post" class="form-horizontal" role="form" action="">

	<div class="modal-body">

		<p>Descrição:</p>

		<div class="form-group">
			<div class="col-md-6">
				<input type="text" name="descricao" value="<?php echo $descricao ?>" class="form-control">
				<input type="hidden" name="tipo_despesa_id" value="<?php echo $id ?>" >
			</div>
		</div>
					
	</div> <!-- /.modal-body -->

	<div class="modal-footer">
		<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
		<button type="submit" name="btnEditarTipo" value="1" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Salvar</button>
	</div> <!-- /.modal-footer -->

</form>