<?php
include_once(__DIR__ . '/../config/db_config.php');

class Funcionario {
    private $id;
    private $nome;
    private $cargo;
    private $setor;
    private $login;
    private $senha;

    public function __construct($nome, $cargo, $setor, $login, $senha, $id = null) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cargo = $cargo;
        $this->setor = $setor;
        $this->login = $login;
        $this->senha = $senha;
    }

    public function cadastrar() {
        global $conn;
        $sql = "INSERT INTO funcionarios (nome, cargo, setor, login, senha) VALUES ('$this->nome', '$this->cargo', '$this->setor', '$this->login', '$this->senha')";
        return $conn->query($sql);
    }

    public function alterar() {
        global $conn;
        $sql = "UPDATE funcionarios SET nome = '$this->nome', cargo = '$this->cargo', setor = '$this->setor', login = '$this->login', senha = '$this->senha' WHERE id = '$this->id'";
        return $conn->query($sql);
    }

    public static function buscar($id) {
        global $conn;
        $sql = "SELECT * FROM funcionarios WHERE id = '$id'";
        $result = $conn->query($sql);
        return $result->fetch_assoc();
    }

    public static function listar() {
        global $conn;
        $sql = "SELECT * FROM funcionarios";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
}