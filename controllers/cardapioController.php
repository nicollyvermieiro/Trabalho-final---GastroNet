<?php
include_once(__DIR__ . '/../config/db_config.php');
include_once(__DIR__ . '/../models/Cardapio.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];

    switch ($acao) {
        case 'salvar':
            // Cadastrar cardápio
            $nome = $_POST["nome"];
            $descricao = $_POST["descricao"];
            $valor = $_POST["valor"];
            $cardapio = new Cardapio($nome, $descricao, $valor);
            if ($cardapio->cadastrar()) {
                // Após cadastrar, buscar e retornar a lista de cardápios atualizada
                $cardapios = Cardapio::listar();
                echo json_encode(["message" => "Cardápio cadastrado com sucesso!", "cardapios" => $cardapios]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar cardápio!"]);
            }
            break;

        case 'alterar':
            // Alterar cardápio
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $descricao = $_POST["descricao"];
            $valor = $_POST["valor"];
            $cardapio = new Cardapio($nome, $descricao, $valor, $id);
            if ($cardapio->alterar()) {
                // Após alterar, buscar e retornar a lista de cardápios atualizada
                $cardapios = Cardapio::listar();
                echo json_encode(["message" => "Cardápio alterado com sucesso!", "cardapios" => $cardapios]);
            } else {
                echo json_encode(["message" => "Erro ao alterar cardápio!"]);
            }
            break;

        case 'buscar':
            // Buscar cardápio por ID
            $id = $_POST["id"];
            $cardapio = Cardapio::buscar($id);
            if ($cardapio) {
                echo json_encode($cardapio);  // Retorna o cardápio em formato JSON
            } else {
                echo json_encode(["message" => "Cardápio não encontrado."]);
            }
            break;

        case 'listar':
            // Listar todos os cardápios
            $cardapios = Cardapio::listar();
            if ($cardapios) {
                echo json_encode($cardapios);  // Retorna a lista de cardápios em formato JSON
            } else {
                echo json_encode(["message" => "Nenhum cardápio cadastrado."]);
            }
            break;
    }
}
