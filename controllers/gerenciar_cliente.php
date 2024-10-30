<?php

include 'C:\laragon\www\Trabalho-final---GastroNet\database\db_config.php';
include 'C:\laragon\www\Trabalho-final---GastroNet\classes\Cliente.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : null;
    $telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : null;
    $endereco = isset($_POST["endereco"]) ? $_POST["endereco"] : null;
    $email = isset($_POST["email"]) ? $_POST["email"] : null;

    switch ($acao) {
        case 'salvar': 
            $cliente = new Cliente($nome, $telefone, $endereco, $email);
            echo $cliente->salvar($conn);
            break;

        case 'buscar':
            if ($id) {
                $cliente = Cliente::buscar($conn, $id);
                if ($cliente) {
                    echo "<h3>Dados do Cliente</h3>";
                    echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
                    echo "<tr><th>ID</th><th>Nome</th><th>Telefone</th><th>Endereço</th><th>Email</th></tr>";
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($cliente['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['telefone']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['endereco']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['email']) . "</td>";
                    echo "</tr>";
                    echo "</table>";
                } else {       
                    echo "<p>Cliente não encontrado.</p>";
                }
            } else {
                echo "<p>Por favor, forneça o ID do cliente.</p>";
            }
            break;

        case 'alterar':
            if ($id) {
                $cliente = new Cliente($nome, $telefone, $endereco, $email);
                echo $cliente->alterar($conn, $id);
            } else {
                echo "Por favor, forneça o ID do cliente para alterá-lo.";
            }
            break;

        case 'listar':
            $clientes = Cliente::listar($conn);
            if ($clientes) {
                echo "<h3>Lista de Clientes</h3>";
                echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
                echo "<tr><th>ID</th><th>Nome</th><th>Telefone</th><th>Endereço</th><th>Email</th><th>Ações</th></tr>";
                foreach ($clientes as $cliente) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($cliente['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['telefone']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['endereco']) . "</td>";
                    echo "<td>" . htmlspecialchars($cliente['email']) . "</td>";
                    echo "<td><button onclick='excluirCliente(" . htmlspecialchars($cliente['id']) . ")'>Excluir</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Nenhum cliente encontrado.";
            }
            break;

        case 'excluir':
            if ($id) {
                $resultado = Cliente::excluir($conn, $id);                    
                echo $resultado;
            } else {
                echo "Por favor, forneça o ID do cliente para excluí-lo.";
            }
            break;
    }
}
?>
