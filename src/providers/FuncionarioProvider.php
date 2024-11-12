<?php
include_once('Funcionario.php');

class FuncionarioProvider {

    // Método para cadastrar um funcionário
    public static function cadastrar($nome, $cargo, $setor, $login, $senha) {
        $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha);
        return $funcionario->cadastrar();
    }

    // Método para alterar um funcionário
    public static function alterar($id, $nome, $cargo, $setor, $login, $senha) {
        $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha, $id);
        return $funcionario->alterar();
    }

    // Método para buscar um funcionário pelo ID
    public static function buscar($id) {
        return Funcionario::buscar($id);
    }

    // Método para listar todos os funcionários
    public static function listar() {
        return Funcionario::listar();
    }
}
