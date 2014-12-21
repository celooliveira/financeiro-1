<?php

// Busca dados do usuário
$sqlUsuarios = $conectar->prepare("
	SELECT nome, email, login, nivel, numero_acesso, ultimo_acesso, acesso_atual, bloqueado, status
	FROM usuarios ORDER BY nome ") or die (mysqli_error($conectar));
$sqlUsuarios->execute();
$sqlUsuarios->store_result();
$sqlUsuarios->bind_result($nome, $email, $login, $nivel, $numero_acesso, $ultimo_acesso, $acesso_atual, $bloqueado, $status);

// Busca tentativas de acesso ao sistema
$sqlTentativas = $conectar->prepare("
	SELECT login, data, hostname, ip
	FROM log_password ORDER BY data DESC ") or die (mysqli_error($conectar));
$sqlTentativas->execute();
$sqlTentativas->store_result();
$sqlTentativas->bind_result($login, $data, $hostname, $ip);

?>