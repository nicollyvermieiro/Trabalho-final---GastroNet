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
        
        $stmt = $conn->prepare($sql);
    
        if ($stmt === false) {
            die('Erro na preparação da consulta: ' . $conn->error);
        }
    
        // Vincula os parâmetros: num_pedido (string), cliente_id (int), valor_total (decimal), forma_pag (string)
        $stmt->bind_param("siss", $this->num_pedido, $this->cliente, $this->valor_total, $this->forma_pag);
    
        if ($stmt->execute()) {
            // Pega o ID do pedido inserido
            $pedido_id = $stmt->insert_id;
    
            // Agora, insere os itens do pedido na tabela itens_pedido
            foreach ($this->itens as $item) {
                $sql_item = "INSERT INTO itens_pedido (pedido_id, cardapio_id, quantidade, item_nome, item_descricao) 
                             VALUES (?, ?, ?, ?, ?)";
                $stmt_item = $conn->prepare($sql_item);
            
                // Certifique-se de passar todos os dados corretamente
                $stmt_item->bind_param("iiiss", $pedido_id, $item['id'], $item['quantidade'], $item['nome'], $item['descricao']);
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
    
        $pedidos = $result->fetch_all(MYSQLI_ASSOC);
        
        // Para cada pedido, obtenha os itens associados
        foreach ($pedidos as &$pedido) {
            $pedido['itens'] = self::obterItens($pedido['id']); // Adiciona os itens ao pedido
        }
        
        return $pedidos;
    }
    

    // Método para obter os itens do pedido, fazendo a junção com a tabela cardapio
    public static function obterItens($pedido_id) {
        global $conn;
        $sql = "SELECT c.nome, ip.quantidade, c.valor, ip.item_descricao 
                FROM itens_pedido ip 
                JOIN cardapio c ON ip.cardapio_id = c.id 
                WHERE ip.pedido_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $pedido_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public static function finalizar($num_pedido) {
        global $conn;
        
        // Atualiza o status do pedido para "finalizado"
        $sql = "UPDATE pedido SET status = 'finalizado' WHERE num_pedido = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $num_pedido);
    
        if ($stmt->execute()) {
            return ['message' => 'Pedido finalizado com sucesso!'];
        } else {
            return ['message' => 'Erro ao finalizar o pedido.'];
        }
    }

    
    

    
}
?>
