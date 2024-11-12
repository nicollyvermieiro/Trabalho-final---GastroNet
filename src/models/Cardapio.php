<?php
include_once('config/db_config.php');  // Inclui a configuração de banco de dados

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
        $sql = "INSERT INTO cardapios (nome, descricao, valor) VALUES ('$this->nome', '$this->descricao', '$this->valor')";
        return $conn->query($sql);
    }

    // Método para alterar um cardápio no banco de dados
    public function alterar() {
        global $conn;
        $sql = "UPDATE cardapios SET nome='$this->nome', descricao='$this->descricao', valor='$this->valor' WHERE id=$this->id";
        return $conn->query($sql);
    }

    // Método para buscar um cardápio pelo ID
    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM cardapios WHERE id=$id";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    // Método para listar todos os cardápios
    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM cardapios";
        $result = $conn->query($sql);
        $cardapios = [];
        while ($row = $result->fetch_assoc()) {
            $cardapios[] = $row;
        }
        return $cardapios;
    }
}
?>
