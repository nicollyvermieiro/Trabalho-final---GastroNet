<?php
include_once(__DIR__ . '/../config/db_config.php');
include_once(__DIR__ . '/../models/Funcionario.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];

    switch ($acao) {
        case 'salvar':
            // Cadastrar funcionário
            $nome = $_POST["nome"];
            $cargo = $_POST["cargo"];
            $setor = $_POST["setor"];
            $login = $_POST["login"];
            $senha = $_POST["senha"];
            $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha);
            if ($funcionario->cadastrar()) {
                echo json_encode(["message" => "Funcionário cadastrado com sucesso!"]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar funcionário!"]);
            }
            break;

        case 'alterar':
            // Alterar funcionário
            $id = $_POST["id"];
            $nome = $_POST["nome"];
            $cargo = $_POST["cargo"];
            $setor = $_POST["setor"];
            $login = $_POST["login"];
            $senha = $_POST["senha"];
            $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha, $id);
            if ($funcionario->alterar()) {
                echo json_encode(["message" => "Funcionário alterado com sucesso!"]);
            } else {
                echo json_encode(["message" => "Erro ao alterar funcionário!"]);
            }
            break;

        case 'buscar':
            // Buscar funcionário por ID
            $id = $_POST["id"];
            $funcionario = Funcionario::buscar($id);
            if ($funcionario) {
                echo json_encode($funcionario);  // Retorna o funcionário em formato JSON
            } else {
                echo json_encode(["message" => "Funcionário não encontrado."]);
            }
            break;

        case 'listar':
            // Listar todos os funcionários
            $funcionarios = Funcionario::listar();
            if ($funcionarios) {
                echo json_encode($funcionarios);  // Retorna a lista de funcionários em formato JSON
            } else {
                echo json_encode(["message" => "Nenhum funcionário cadastrado."]);
            }
            break;
    }
}
