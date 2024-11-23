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
            $categoria = $_POST["categoria"];  // Pegando a categoria
        
            $cardapio = new Cardapio($nome, $descricao, $valor, $categoria);
            if ($cardapio->cadastrar()) {
                // Após cadastrar, buscar e retornar a lista de cardápios atualizada
                $cardapios = Cardapio::listar();
                echo json_encode(["message" => "Cardápio cadastrado com sucesso!", "cardapios" => $cardapios]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar cardápio!"]);
            }
            break;
        

            case 'alterar':
                // Verifica se os dados necessários foram recebidos
                if (isset($_POST["id"], $_POST["nome"], $_POST["descricao"], $_POST["valor"], $_POST["categoria"])) {
                    $id = $_POST["id"];
                    $nome = $_POST["nome"];
                    $descricao = $_POST["descricao"];
                    $valor = $_POST["valor"];
                    $categoria = $_POST["categoria"];
            
                    // Adicionando log para verificar os dados recebidos
                    error_log("Alterar Cardápio - ID: $id, Nome: $nome, Descrição: $descricao, Valor: $valor, Categoria: $categoria");
            
                    // Cria um objeto Cardapio com os dados recebidos
                    $cardapio = new Cardapio($nome, $descricao, $valor, $categoria, $id);  // Adicionando categoria ao construtor, se necessário
            
                    // Tenta alterar o cardápio
                    if ($cardapio->alterar()) {
                        // Após alterar, buscar e retornar a lista de cardápios atualizada
                        $cardapios = Cardapio::listar();
                        echo json_encode(["message" => "Cardápio alterado com sucesso!", "cardapios" => $cardapios]);
                    } else {
                        echo json_encode(["message" => "Erro ao alterar cardápio!"]);
                    }
                } else {
                    echo json_encode(["message" => "Dados incompletos para alteração."]);
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
