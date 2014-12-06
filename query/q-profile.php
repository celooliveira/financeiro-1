<?php

$display = 'hide';

// Evento remover conta
if(isset($_POST['btnRemoverConta']) && $_POST['btnRemoverConta'] == 1){

	$senha_remove = addslashes($_POST['senha_remove']);

	// Confere se a senha digitada
	$sqlSenha = $conectar->prepare("SELECT senha FROM usuarios WHERE id = ? ") or die (mysqli_error($conectar));
	$sqlSenha->bind_param('i', $usuarioID);
	$sqlSenha->execute();
	$sqlSenha->store_result();
	$sqlSenha->bind_result($senha_banco);
	$sqlSenha->fetch();

	if(strcmp(sha1($senha_remove), $senha_banco) == 0){

		$sqlRemove = $conectar->prepare("DELETE FROM usuarios WHERE id = ? ");
		$sqlRemove->bind_param('i', $usuarioID);
		$sqlRemove->execute();

		header("Location:$home/includes/logout.php");

	} // fim do if

} // fim do if

// Evento alterar senha usuario
if(isset($_POST['btnAlterarSenha']) && $_POST['btnAlterarSenha'] == 1){

	$senha_atual = addslashes($_POST['senha_atual']);
	$senha1 = addslashes($_POST['senha1']);
	$senha2 = addslashes($_POST['senha2']);

	// Busca no banco a senha atual
	$sqlSenha = $conectar->prepare("SELECT senha FROM usuarios WHERE id = ? ") or die (mysqli_error($conectar));
	$sqlSenha->bind_param('i', $usuarioID);
	$sqlSenha->execute();
	$sqlSenha->store_result();
	$sqlSenha->bind_result($senha_banco);
	$sqlSenha->fetch();

	// Verifica se a senha digitada confere com a do banco de dados
	if(strcmp(sha1($senha_atual), $senha_banco) == 0){

		// Verifica se a string tem 8 ou mais caracteres
		if(strlen($senha1) >= 8){
		
			if(strcmp($senha1, $senha2) == 0){

				// Senha criptografada
				$senha1 = sha1($senha1);

				// Senha digitadas ok - atualiza o banco
				$sqlInsert = $conectar->prepare("UPDATE usuarios SET senha = ? WHERE id = ? ") or die (mysqli_error($conectar));
				$sqlInsert->bind_param('si', $senha1, $usuarioID);
				$sqlInsert->execute();

				if($sqlInsert == true){
					$msg_error = "<strong><i class='fa fa-check fa-fw'></i> Feito!</strong> Senha alterada com sucesso!";
					$alert = 'success';
				}

			} else{
				$msg_error = "<strong><i class='fa fa-exclamation-triangle fa-fw'></i> Atenção!</strong> Você digitou duas senhas diferentes!";
				$alert = 'danger';
			}

		} // fim do if
		else{
			$msg_error = "<strong><i class='fa fa-exclamation-triangle fa-fw'></i> Atenção!</strong> A senha deve conter no mínimo 8 caracteres!";
			$alert = 'danger';
		}

	} else{
		$msg_error = "<strong><i class='fa fa-exclamation-triangle fa-fw'></i> Atenção!</strong> A senha atual digitada não confere!";
		$alert = 'danger';
	}

	$collapsein = 'in';
	$display = 'show';

} // fim do if evento alterar senha

// Busca dados do usuário
$sqlUsuario = $conectar->prepare("
	SELECT u.nome, u.email, u.login, u.nivel, u.numero_acesso, u.ultimo_acesso, u.acesso_atual, u.data_cadastro, a.avatar
	FROM usuarios AS u
	LEFT JOIN avatar AS a
	ON u.id = a.usuarios_id
	WHERE u.id = ? LIMIT 1 ") or die (mysqli_error($conectar));
$sqlUsuario->bind_param('i', $usuarioID);
$sqlUsuario->execute();
$sqlUsuario->store_result();
$sqlUsuario->bind_result($nome, $email, $login, $nivel, $numero_acesso, $ultimo_acesso, $acesso_atual, $data_cadastro, $avatar);
$sqlUsuario->fetch();

// Acesso atual
$array = explode(" ", $acesso_atual);
$hora = substr($array[1], 0, 5);
$data = explode("-", $array[0]);
$acesso_atual = "$data[2]/$data[1]/$data[0]" . " às " . $hora;

// Data de cadastro
$array = explode(" ", $data_cadastro);
$hora = substr($array[1], 0, 5);
$data = explode("-", $array[0]);
$data_cadastro = "$data[2]/$data[1]/$data[0]";

// Nivel de acesso
switch ($nivel) {
	case 1: $nivel = 'Administrador'; break;
	case 2: $nivel = 'Usuário Padrão'; break;
}

// Busca acessos do usuario
$sqlAcessos = $conectar->prepare("
	SELECT id, data, hostname, ip, latitude, longitude 
	FROM log_acesso 
	WHERE usuarios_id = ?
	ORDER BY data DESC") or die (mysqli_error($conectar));
$sqlAcessos->bind_param('i', $usuarioID);
$sqlAcessos->execute();
$sqlAcessos->store_result();
$sqlAcessos->bind_result($id, $data_acesso, $hostname, $ip, $latitude, $longitude);


?>