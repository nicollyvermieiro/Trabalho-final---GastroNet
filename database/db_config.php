<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "gastronet";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar a conexão
if ($conn->connect_error) {
  die("Falha na conexão: " . $conn->connect_error);
}
?>