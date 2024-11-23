<?php
include_once(__DIR__ . '/../config/db_config.php');
include_once(__DIR__ . '/../models/Pedido.php');
include_once(__DIR__ . '/../models/Cliente.php');
include_once(__DIR__ . '/../models/Cardapio.php');

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];

    switch ($acao) {
        case 'salvar':
            // Cadastrar pedido
            $num_pedido = $_POST["num_pedido"];
            $cliente_id = $_POST["cliente_id"];
            $itens = json_decode($_POST["itens"], true); // Decodifica os itens JSON
            $valor_total = $_POST["valor_total"];
            $forma_pag = $_POST["forma_pag"];
            $pedido = new Pedido($num_pedido, $cliente_id, $itens, $valor_total, $forma_pag);
            if ($pedido->cadastrar()) {
                echo json_encode(["message" => "Pedido cadastrado com sucesso!"]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar o pedido!"]);
            }
            break;

        case 'listar':
            $pedidos = Pedido::listar();
            if ($pedidos) {
                echo json_encode($pedidos);  // Retorna a lista de pedidos em JSON
            } else {
                echo json_encode(["message" => "Nenhum pedido cadastrado."]);
            }
            break;

        case 'buscar_clientes':
            // Retornar todos os clientes
            $clientes = Cliente::listar();
            if ($clientes) {
                echo json_encode($clientes);
            } else {
                echo json_encode(["message" => "Nenhum cliente encontrado."]);
            }
            break;

        case 'buscar_itens_cardapio':
            // Retornar todos os itens do cardápio
            $itens = Cardapio::listar();
            if ($itens) {
                echo json_encode($itens);
            } else {
                echo json_encode(["message" => "Nenhum item no cardápio."]);
            }
            break;
    }
}
?>
