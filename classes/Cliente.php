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
        $sql = "INSERT INTO clientes (nome, telefone, endereco, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $this->nome, $this->telefone, $this->endereco, $this->email);
        if ($stmt->execute()) {
            return "Cliente salvo com sucesso!";
        } else {
            return "Erro ao salvar cliente: " . $conn->error;
        }
    }

    public function alterar($conn, $id) {
        $sql = "UPDATE clientes SET nome = ?, telefone = ?, endereco = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $this->nome, $this->telefone, $this->endereco, $this->email, $id);
        if ($stmt->execute()) {
            return "Dados do cliente alterados com sucesso!";
        } else {
            return "Erro ao alterar dados do cliente: " . $conn->error;
        }
    }

    public static function buscar($conn, $id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function listar($conn) {
        $sql = "SELECT * FROM clientes";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            return [];
        }
    }

    public static function excluir($conn, $id) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return "Cliente excluído com sucesso!";
        } else {
            return "Erro ao excluir cliente: " . $conn->error;
        }
    }
}
?>
