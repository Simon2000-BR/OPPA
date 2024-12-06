<?php
$servidor = "localhost";
$dbname = "oppa";
$usuario = "root";
$senha = "";

$conn = new mysqli($servidor, $usuario, $senha, $dbname);

if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>

