<?php
// Caminho para o banco de dados SQLite
$dbname = "C:\laragon\www\Trabalho-final---GastroNet\database\gastronet.db";

try {
    // Conexão com o banco de dados SQLite
    $conn = new PDO("sqlite:$dbname");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificação de conexão
    if ($conn) {
        echo "Conexão com o banco de dados estabelecida!";
    }
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage();
}
?>
