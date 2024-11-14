<?php
include_once(__DIR__ . '/../config/db_config.php');
include_once(__DIR__ . '/../models/Cliente.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];

    switch ($acao) {
        case 'salvar':
            // Cadastrar cliente
            $nome = $_POST["nome"];
            $telefone = $_POST["telefone"];
            $endereco = $_POST["endereco"];
            $email = $_POST["email"];
            $cliente = new Cliente($nome, $telefone, $endereco, $email);
            if ($cliente->cadastrar()) {
                echo json_encode(["message" => "Cliente cadastrado com sucesso!"]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar cliente!"]);
            }
            break;

        case 'alterar':
            // Alterar cliente
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $telefone = $_POST["telefone"];
            $endereco = $_POST["endereco"];
            $email = $_POST["email"];
            $cliente = new Cliente($nome, $telefone, $endereco, $email, $id);
            if ($cliente->alterar()) {
                echo json_encode(["message" => "Cliente alterado com sucesso!"]);
            } else {
                echo json_encode(["message" => "Erro ao alterar cliente!"]);
            }
            break;

        case 'buscar':
            // Buscar cliente por ID
            $id = $_POST["id"];
            $cliente = Cliente::buscar($id);
            if ($cliente) {
                echo json_encode($cliente);  // Retorna o cliente em formato JSON
            } else {
                echo json_encode(["message" => "Cliente não encontrado."]);
            }
            break;

        case 'listar':
            // Listar todos os clientes
            $clientes = Cliente::listar();
            if ($clientes) {
                echo json_encode($clientes);  // Retorna a lista de clientes em formato JSON
            } else {
                echo json_encode(["message" => "Nenhum cliente cadastrado."]);
            }
            break;
    }
}
