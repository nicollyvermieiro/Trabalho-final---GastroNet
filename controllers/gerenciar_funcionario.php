<?php

include 'C:\laragon\www\Trabalho-final---GastroNet\database\db_config.php';
include 'C:\laragon\www\Trabalho-final---GastroNet\classes\Funcionario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $acao = $_POST["acao"];
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    $nome = isset($_POST["nome"]) ? $_POST["nome"] : null;
    $cargo = isset($_POST["cargo"]) ? $_POST["cargo"] : null;
    $setor = isset($_POST["setor"]) ? $_POST["setor"] : null;
    $login = isset($_POST["login"]) ? $_POST["login"] : null;
    $senha = isset($_POST["senha"]) ? $_POST["senha"] : null;

    switch ($acao) {
        case 'salvar': 
            $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha);
            echo $funcionario->salvar($conn);
            break;

        case 'buscar':
            if ($id) {
                $funcionario = Funcionario::buscar($conn, $id);
                if ($funcionario) {
                    echo "<h3>Dados do Funcionário</h3>";
                    echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
                    echo "<tr><th>ID</th><th>Nome</th><th>Cargo</th><th>Setor</th><th>Login</th></tr>";
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($funcionario['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['cargo']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['setor']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['login']) . "</td>";
                    echo "</tr>";
                    echo "</table>";
                } else {       
                    echo "<p>Funcionário não encontrado.</p>";
                }
            } else {
                echo "<p>Por favor, forneça o ID do funcionário.</p>";
            }
            break;

        case 'alterar':
            if ($id) {
                $funcionario = new Funcionario($nome, $cargo, $setor, $login, $senha);
                echo $funcionario->alterar($conn, $id);
            } else {
                echo "Por favor, forneça o ID do funcionário para alterá-lo.";
            }
            break;

        case 'listar':
            $funcionarios = Funcionario::listar($conn);
            if ($funcionarios) {
                echo "<h3>Lista de Funcionários</h3>";
                echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
                echo "<tr><th>ID</th><th>Nome</th><th>Cargo</th><th>Setor</th><th>Login</th><th>Ações</th></tr>";
                foreach ($funcionarios as $funcionario) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($funcionario['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['nome']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['cargo']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['setor']) . "</td>";
                    echo "<td>" . htmlspecialchars($funcionario['login']) . "</td>";
                    echo "<td><button onclick='excluirFuncionario(" . htmlspecialchars($funcionario['id']) . ")'>Excluir</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Nenhum funcionário encontrado.";
            }
            break;

            case 'excluir':
                if ($id) {
                    $resultado = Funcionario::excluir($conn, $id);                    
                    echo $resultado;
                } else {
                    echo "Por favor, forneça o ID do funcionário para excluí-lo.";
                }
                break;
            
    }
}
?>
