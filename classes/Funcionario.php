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

    public function salvar($conn) {
        try {
            $sql = "INSERT INTO funcionarios (nome, cargo, setor, login, senha) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $this->nome, $this->cargo, $this->setor, $this->login, $this->senha);
            $stmt->execute();
            return "Funcionário salvo com sucesso!";
        } catch (Exception $e) {
            return "Erro ao salvar funcionário: " . $e->getMessage();
        }
    }

    public function alterar($conn, $id) {
        try {
            $sql = "UPDATE funcionarios SET nome = ?, cargo = ?, setor = ?, login = ?, senha = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $this->nome, $this->cargo, $this->setor, $this->login, $this->senha, $id);
            $stmt->execute();
            return "Dados do funcionário alterados com sucesso!";
        } catch (Exception $e) {
            return "Erro ao alterar dados do funcionário: " . $e->getMessage();
        }
    }

    public static function buscar($conn, $id) {
        try {
            $sql = "SELECT * FROM funcionarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            return "Erro ao buscar funcionário: " . $e->getMessage();
        }
    }

    public static function listar($conn) {
        try {
            $sql = "SELECT * FROM funcionarios";
            $result = $conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            return "Erro ao listar funcionários: " . $e->getMessage();
        }
    }

    public static function excluir($conn, $id) {
        try {
            $sql = "DELETE FROM funcionarios WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return "Funcionário excluído com sucesso!";
        } catch (Exception $e) {
            return "Erro ao excluir funcionário: " . $e->getMessage();
        }
    }
}
