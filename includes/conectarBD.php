<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "controle_financeiro";

//criando a conexão
$conn = new mysqli($hostname, $username, $password, $database);

// verificando a conexão

if ($conn->connect_error) {
    die("Erro ao conectar com o banco de dados! " . $conn->connect_error);
}
