<?php
include_once(__DIR__ . '/../config/db_config.php');

class Pedido {
    private $id;
    private $num_pedido;
    private $cliente;
    private $itens;  // Agora, será um array de itens com seus ids e quantidades
    private $valor_total;
    private $forma_pag;

    public function __construct($num_pedido, $cliente, $itens, $valor_total, $forma_pag, $id = null) {
        $this->id = $id;
        $this->num_pedido = $num_pedido;
        $this->cliente = $cliente;
        $this->itens = $itens; // Agora, é um array de itens do cardápio com id e quantidade
        $this->valor_total = $valor_total;
        $this->forma_pag = $forma_pag;
    }

    public function cadastrar() {
        global $conn;
    
        // Gera um número de pedido único
        if (!$this->num_pedido) {
            $this->num_pedido = "PED-" . rand(1000, 9999); // ou algum outro mecanismo para gerar números únicos
        }
    
        // Inserção do pedido na tabela pedido
        $sql = "INSERT INTO pedido (num_pedido, cliente_id, valor_total, forma_pag) 
            VALUES (?, ?, ?, ?)";
        
        // Prepara a consulta
        $stmt = $conn->prepare($sql);
    
        // Verifica se a consulta foi preparada corretamente
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
    
        // Vincula os parâmetros: num_pedido (string), cliente_id (int), valor_total (decimal), forma_pag (string)
        $stmt->bind_param("siss", $this->num_pedido, $this->cliente, $this->valor_total, $this->forma_pag);
    
        // Executa a consulta
        if ($stmt->execute()) {
            // Pega o ID do pedido inserido
            $pedido_id = $stmt->insert_id;
    
            // Agora, insere os itens do pedido na tabela itens_pedido
            foreach ($this->itens as $item) {
                $sql_item = "INSERT INTO itens_pedido (pedido_id, cardapio_id, item_nome, item_descricao, quantidade) 
                             VALUES (?, ?, ?, ?, ?)";
                $stmt_item = $conn->prepare($sql_item);
                $stmt_item->bind_param("isssi", $pedido_id, $item['cardapio_id'], $item['nome'], $item['descricao'], $item['quantidade']);
                $stmt_item->execute();
            }
            return true;
        }
    
        return false;
    }
    
    
    
    

    public function alterar() {
        global $conn;
        $sql = "UPDATE pedido SET num_pedido = ?, cliente_id = ?, valor_total = ?, forma_pag = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sissi", $this->num_pedido, $this->cliente, $this->valor_total, $this->forma_pag, $this->id);
        if ($stmt->execute()) {
            // Atualiza os itens na tabela itens_pedido (caso necessário)
            // Você pode implementar a lógica para remover ou atualizar os itens antigos
            return true;
        }
        return false;
    }

    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM pedido WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM pedido";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obter os itens do pedido, fazendo a junção com a tabela cardapio
    public static function obterItens($pedido_id) {
        global $conn;
        $sql = "SELECT c.nome, ip.quantidade, c.valor 
                FROM itens_pedido ip 
                JOIN cardapio c ON ip.cardapio_id = c.id 
                WHERE ip.pedido_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
