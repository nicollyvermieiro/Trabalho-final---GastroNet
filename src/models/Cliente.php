<?php
include_once('config/db_config.php');

class Cliente {
    private $id;
    private $nome;
    private $telefone;
    private $endereco;
    private $email;

    public function __construct($nome, $telefone, $endereco, $email, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->endereco = $endereco;
        $this->email = $email;
    }

    public function cadastrar() {
        global $conn;
        $sql = "INSERT INTO clientes (nome, telefone, endereco, email) VALUES ('$this->nome', '$this->telefone', '$this->endereco', '$this->email')";
        return $conn->query($sql);
    }

    public function alterar() {
        global $conn;
        $sql = "UPDATE clientes SET nome = '$this->nome', telefone = '$this->telefone', endereco = '$this->endereco', email = '$this->email' WHERE id = '$this->id'";
        return $conn->query($sql);
    }

    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM clientes WHERE id = '$id'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM clientes";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}