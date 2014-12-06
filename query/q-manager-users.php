<?php

// Busca dados do usuário
$sqlUsuarios = $conectar->prepare("
	SELECT nome, email, login, nivel, numero_acesso, ultimo_acesso, acesso_atual, bloqueado, status
	FROM usuarios ORDER BY nome ") or die (mysqli_error($conectar));
$sqlUsuarios->execute();
$sqlUsuarios->store_result();
$sqlUsuarios->bind_result($nome, $email, $login, $nivel, $numero_acesso, $ultimo_acesso, $acesso_atual, $bloqueado, $status);


?>