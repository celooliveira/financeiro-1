<?php

ob_start();
include "../conn/DB.class.php";
include "../conn/Login.class.php";

// Evento logar
if(isset($_POST['btnLogar']) && $_POST['btnLogar'] == 1){
	
	// Dados do formulário
	$login = addslashes($_POST['login']);
	$senha = addslashes(sha1($_POST['senha']));

	if(empty($login) || empty($senha)){
		$msg = "Preencha todos os campos!";
	}else{
		//Executa a busca pelo usuário
		$logar = new Login;
		$logar = $logar->logar($login, $senha, $conectar);		

	} // fim do else

} // fim do if acao logar

?>