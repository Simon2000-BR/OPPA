<?php
// Verifica se a sessão ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servidor = "localhost";
$dbname = "oppa";
$usuario = "root";
$senha = "";

$conn = new mysqli($servidor, $usuario, $senha, $dbname);

// Verificando se houve erro na conexão com o banco de dados
if ($conn->connect_error) {
    die("Erro de conexão com o banco de dados: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
