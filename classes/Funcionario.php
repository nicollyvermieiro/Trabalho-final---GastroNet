<?php

class Funcionario {
    private $nome;
    private $cargo;
    private $setor;
    private $login;
    private $senha;

    public function __construct($nome, $cargo, $setor, $login, $senha) {
        $this->nome = $nome;
        $this->cargo = $cargo;
        $this->setor = $setor;
        $this->login = $login;
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    public function cadastrar($conn) {
        try {
            $sql = "INSERT INTO funcionarios (nome, cargo, setor, login, senha) 
                    VALUES (:nome, :cargo, :setor, :login, :senha)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':cargo', $this->cargo);
            $stmt->bindParam(':setor', $this->setor);
            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->execute();
            return "Funcionário cadastrado com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao cadastrar funcionário: " . $e->getMessage();
        }
    }

    public function alterar($conn, $id) {
        try {
            $sql = "UPDATE funcionarios SET nome = :nome, cargo = :cargo, setor = :setor, login = :login, senha = :senha 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':cargo', $this->cargo);
            $stmt->bindParam(':setor', $this->setor);
            $stmt->bindParam(':login', $this->login);
            $stmt->bindParam(':senha', $this->senha);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Dados do funcionário alterados com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao alterar dados do funcionário: " . $e->getMessage();
        }
    }

    public static function buscar($conn, $id) {
        try {
            $sql = "SELECT * FROM funcionarios WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erro ao buscar funcionário: " . $e->getMessage();
        }
    }

    public static function listar($conn) {
        try {
            $sql = "SELECT * FROM funcionarios";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erro ao listar funcionários: " . $e->getMessage();
        }
    }
}
?>
