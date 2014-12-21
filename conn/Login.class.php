<?php

date_default_timezone_set('America/Sao_Paulo');

class Login{
	public function logar($login_user, $senha_user, $conectar){

		// Data atual
		$data_atual = date("Y-m-d H:i:s");

		// Pega hostname e IP da maquina
		$hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$ip = $_SERVER["REMOTE_ADDR"];

		// Prepara uma consulta SQL
		$sql = $conectar->prepare("
				SELECT id, nome, login, bloqueado, nivel, status, ultimo_acesso, acesso_atual, numero_acesso
				FROM usuarios WHERE (login = ? OR email = ?) AND senha = ? LIMIT 1") or die (mysqli_error($conectar));
		$sql->bind_param('sss', $login_user, $login_user, $senha_user);
		$sql->execute();
		$sql->store_result();
		$sql->bind_result($id, $nome, $login, $bloqueado, $nivel, $status, $ultimo_acesso, $acesso_atual, $numero_acesso);
		$sql->fetch();

		// Usuario ou senha incorretos
		if($sql->num_rows() == 0){

			// Insere na tabela de senhas
			$sql = $conectar->prepare("INSERT INTO log_password VALUES ('',?,?,?,?)") or die (mysqli_error($conectar));
			$sql->bind_param('ssss', $login_user, $data_atual, $hostname, $ip);
			$sql->execute();

			// Senha / usuario incorretos
			header("Location:../login/0"); exit;

		} else{
			// Usuario bloqueado
			if($bloqueado == "S"){
				header("Location:../login/1"); exit;
			} else{

				// Usuario ativo no sistema
				if($status == 1){

					session_name('financeiro');
					session_start();
					$_SESSION['id'] = $id;
					$_SESSION['nome'] = $nome;
					$_SESSION['login'] = $login;
					$_SESSION['nivel'] = $nivel;
					$_SESSION['ultimo_acesso'] = $ultimo_acesso;
					$_SESSION['acesso_atual'] = $acesso_atual;
					$_SESSION['numero_acesso'] = $numero_acesso;

					// Salva na sessão para uso de segurança
					$_SESSION["chave"] = sha1(mt_rand());
					
					// Regenara o ID a cada login
					session_regenerate_id();

					// Salva na sessão o HEADER
					$_SESSION['dono_sessao'] = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

					// Opção lembrar senha
					$_SESSION['lembrar_senha'] = $_POST['lembrar_senha'];
					if($_SESSION['lembrar_senha'] != 1){ // Não setou para lembrar senha
						// Seta o tempo de sessao para 5 minutos
						$_SESSION['sessiontime'] = time() + 300;
					}

					// Atualiza os dados do usuario com o acesso atual
					$sql = $conectar->prepare("
						UPDATE usuarios
						SET ultimo_acesso = acesso_atual,
						acesso_atual = ?,
						numero_acesso = numero_acesso + 1,
						chave = ?
						WHERE id = ? ") or die (mysqli_error($conectar));     
					$sql->bind_param('ssi', $data_atual, $_SESSION['chave'], $id);
					$sql->execute();

					// Atualiza o acesso do usuario
					$sql = $conectar->prepare("INSERT INTO log_acesso VALUES ('',?,?,?,?,?,?) ") or die (mysqli_error($conectar));     
					$sql->bind_param('isssss', $id, $data_atual, $hostname, $ip, $latitude, $longitude);
					$sql->execute();

					// Você foi logado com sucesso
					header("Location:../home/"); exit;

				} // fim do if status
				else{
					// Usuario inativo no sistema
					header("Location:../login/3"); exit;
				}


			} // fim do else bloqueado
		} // fim do else usuario/senha incorretos

	} // fim da function logar
} // fim do class Login

?>