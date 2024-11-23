<?php
// Inclui o arquivo de conexão
require_once './config/db_config.php'; // Substitua pelo caminho correto do arquivo de conexão

// Testa a conexão
if ($conn->connect_error) {
    echo "Falha na conexão: " . $conn->connect_error;
} else {
    echo "Conexão bem-sucedida!";
}

// Opcional: Finalizar a conexão
$conn->close();
?>
