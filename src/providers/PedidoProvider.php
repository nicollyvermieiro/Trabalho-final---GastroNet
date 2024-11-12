<?php
// PedidoProvider.php
include_once('Pedido.php');

class PedidoProvider {

    // Método para cadastrar um pedido
    public static function cadastrar($num_pedido, $cliente, $itens, $valor_total, $forma_pag, $data_pedido) {
        $pedido = new Pedido($num_pedido, $cliente, $itens, $valor_total, $forma_pag, $data_pedido);
        return $pedido->cadastrar();
    }

    // Método para alterar um pedido
    public static function alterar($id, $num_pedido, $cliente, $itens, $valor_total, $forma_pag, $data_pedido) {
        $pedido = new Pedido($num_pedido, $cliente, $itens, $valor_total, $forma_pag, $data_pedido, $id);
        return $pedido->alterar();
    }

    // Método para buscar um pedido pelo ID
    public static function buscar($id) {
        return Pedido::buscar($id);
    }

    // Método para listar todos os pedidos
    public static function listar() {
        return Pedido::listar();
    }
}
