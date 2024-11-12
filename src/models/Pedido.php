<?php
include_once('config/db_config.php');

class Pedido {
    private $id;
    private $num_pedido;
    private $cliente;
    private $itens;
    private $valor_total;
    private $forma_pag;
    private $data_pedido;

    public function __construct($num_pedido, $cliente, $itens, $valor_total, $forma_pag, $data_pedido, $id = null) {
        $this->id = $id;
        $this->num_pedido = $num_pedido;
        $this->cliente = $cliente;
        $this->itens = $itens;
        $this->valor_total = $valor_total;
        $this->forma_pag = $forma_pag;
        $this->data_pedido = $data_pedido;
    }

    public function cadastrar() {
        global $conn;
        $sql = "INSERT INTO pedidos (num_pedido, cliente_id, itens, valor_total, forma_pag, data_pedido) 
                VALUES ('$this->num_pedido', '$this->cliente', '$this->itens', '$this->valor_total', '$this->forma_pag', '$this->data_pedido')";
        return $conn->query($sql);
    }

    public function alterar() {
        global $conn;
        $sql = "UPDATE pedidos SET num_pedido = '$this->num_pedido', cliente_id = '$this->cliente', itens = '$this->itens', 
                valor_total = '$this->valor_total', forma_pag = '$this->forma_pag', data_pedido = '$this->data_pedido' WHERE id = '$this->id'";
        return $conn->query($sql);
    }

    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM pedidos WHERE id = '$id'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM pedidos";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
