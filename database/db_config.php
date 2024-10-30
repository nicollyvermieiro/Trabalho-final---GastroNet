<?php
// Caminho para o banco de dados SQLite
$dbname = "C:\\laragon\\www\\teste01\\app\\database\\teste01.db";

try {
    // Conexão com o banco de dados SQLite
    $conn = new PDO("sqlite:$dbname");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
