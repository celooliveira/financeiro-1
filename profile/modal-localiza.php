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

$array = explode("&", $_POST['id']);
$latitude = $array[0];
$longitude = $array[1];

$img_url = "http://maps.googleapis.com/maps/api/staticmap?center=$latitude,$longitude&zoom=15&size=500x300&sensor=false";

?>

<div class="modal-header">
	<h4 class="modal-title text-success" id="myModalLabel">Localização do Usuário</h4>
</div>

<div class="modal-body">
	<p>Essa é a sua localização mais próxima do seu último acesso ao sistema.</p>
	<img src='<?php echo $img_url ?>'>
</div> <!-- /.modal-body -->

<div class="modal-footer">
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-fw"></i> Fechar</button>
</div>