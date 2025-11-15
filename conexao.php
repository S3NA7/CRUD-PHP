<?php

$servidor = "localhost";
$usuario = "root";
$senha = "Se9823!";
$banco = "meu_banco";

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$conexao) {
    die("Conexão falhou: " . mysqli_connect_error());
}
?>