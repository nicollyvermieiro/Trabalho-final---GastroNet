<?php

class Cliente {
    private $nome;
    private $telefone;
    private $endereco;
    private $email;

    public function __construct($nome, $telefone, $endereco, $email) {
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->endereco = $endereco;
        $this->email = $email;
    }

    public function salvar($conn) {
        try {
            $sql = "INSERT INTO clientes (nome, telefone, endereco, email) 
                    VALUES (:nome, :telefone, :endereco, :email)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':endereco', $this->endereco);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            return "Cliente salvo com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao salvar cliente: " . $e->getMessage();
        }
    }

    public function alterar($conn, $id) {
        try {
            $sql = "UPDATE clientes SET nome = :nome, telefone = :telefone, endereco = :endereco, email = :email 
                    WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nome', $this->nome);
            $stmt->bindParam(':telefone', $this->telefone);
            $stmt->bindParam(':endereco', $this->endereco);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return "Dados do cliente alterados com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao alterar dados do cliente: " . $e->getMessage();
        }
    }

    public static function buscar($conn, $id) {
        try {
            $sql = "SELECT * FROM clientes WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erro ao buscar cliente: " . $e->getMessage();
        }
    }

    public static function listar($conn) {
        try {
            $sql = "SELECT * FROM clientes";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Erro ao listar clientes: " . $e->getMessage();
        }
    }

    public static function excluir($conn, $id) {
        try {
            $sql = "DELETE FROM clientes WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return "Cliente excluído com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao excluir cliente: " . $e->getMessage();
        }
    }
}
?>
