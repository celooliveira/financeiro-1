<?php

session_name('financeiro');
session_start();

include "../includes/globais.php";

session_destroy();
header("Location:".$home);

?>