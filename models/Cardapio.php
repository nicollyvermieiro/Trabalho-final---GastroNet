<?php
include_once(__DIR__ . '/../config/db_config.php');

class Cardapio {
    private $id;
    private $nome;
    private $descricao;
    private $valor;

    public function __construct($nome, $descricao, $valor, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->valor = $valor;
    }

    // Método para cadastrar um cardápio no banco de dados
    public function cadastrar() {
        global $conn;
        $sql = "INSERT INTO cardapio (nome, descricao, valor) VALUES ('$this->nome', '$this->descricao', '$this->valor')";
        return $conn->query($sql);
    }

    // Método para alterar um cardápio no banco de dados
    public function alterar() {
        global $conn;
        $sql = "UPDATE cardapio SET nome='$this->nome', descricao='$this->descricao', valor='$this->valor' WHERE id=$this->id";
        return $conn->query($sql);
    }

    // Método para buscar um cardápio pelo ID
    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM cardapio WHERE id=$id";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // Método para listar todos os cardápios
    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM cardapio";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
