<?php
// ClienteProvider.php
include_once('Cliente.php');

class ClienteProvider {

    // Método para cadastrar um cliente
    public static function cadastrar($nome, $telefone, $endereco, $email) {
        $cliente = new Cliente($nome, $telefone, $endereco, $email);
        return $cliente->cadastrar();
    }

    // Método para alterar um cliente
    public static function alterar($id, $nome, $telefone, $endereco, $email) {
        $cliente = new Cliente($nome, $telefone, $endereco, $email, $id);
        return $cliente->alterar();
    }

    // Método para buscar um cliente pelo ID
    public static function buscar($id) {
        return Cliente::buscar($id);
    }

    // Método para listar todos os clientes
    public static function listar() {
        return Cliente::listar();
    }
}

