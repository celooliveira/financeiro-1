<?php

date_default_timezone_set('America/Sao_Paulo');

// Verifica se há usuario logado
if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])){
	header("Location:".$home."/includes/logout.php"); exit();
}

// Verifica a chave do usuario
$sql = $conectar->prepare("SELECT chave FROM usuarios WHERE id = ? LIMIT 1 ") or die (mysqli_error($conectar));
$sql->bind_param('i', $usuarioID);
$sql->execute();
$sql->store_result();
$sql->bind_result($chave);
$sql->fetch();

// Compara as chaves e caso diferente 'expulsa' o usuario
if(strcmp($chave, $_SESSION['chave']) != 0){
	header("Location:".$home."/includes/logout.php"); exit();
}

// Checa se a sessão do usuário expirou
if($_SESSION['lembrar_senha'] != 1){
	if(isset($_SESSION['sessiontime'])){ 
		// Sessao expirada
		if ($_SESSION['sessiontime'] < time()) {
			header("Location:".$home."/includes/logout.php"); exit();
		} else {
			// Seta mais tempo 5 minutos
			$_SESSION['sessiontime'] = time() + 300;
		}
	}
}

?>