<?php
include_once('Cardapio.php');

class CardapioProvider {

    // Método para cadastrar um cardápio
    public static function cadastrar($nome, $descricao, $valor) {
        $cardapio = new Cardapio($nome, $descricao, $valor);
        return $cardapio->cadastrar();
    }

    // Método para alterar um cardápio
    public static function alterar($id, $nome, $descricao, $valor) {
        $cardapio = new Cardapio($nome, $descricao, $valor, $id);
        return $cardapio->alterar();
    }

    // Método para buscar um cardápio pelo ID
    public static function buscar($id) {
        return Cardapio::buscar($id);
    }

    // Método para listar todos os cardápios
    public static function listar() {
        return Cardapio::listar();
    }
}
?>
