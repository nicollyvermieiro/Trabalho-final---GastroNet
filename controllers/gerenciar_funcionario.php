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
            if ($id) { 
                echo $funcionario->alterar($conn, $id);
            } else {
                echo $funcionario->salvar($conn);
            }
            break;

            case 'buscar':
                if ($id) {
                    $funcionario = Funcionario::buscar($conn, $id);
                    if ($funcionario) {
                        // Montar uma tabela HTML para exibir os dados
                        echo "<h3>Detalhes do Funcionário</h3>";
                        echo "<table border='1' style='border-collapse: collapse; width: 50%;'>";
                        echo "<tr><th>ID</th><td>" . htmlspecialchars($funcionario['id']) . "</td></tr>";
                        echo "<tr><th>Nome</th><td>" . htmlspecialchars($funcionario['nome']) . "</td></tr>";
                        echo "<tr><th>Cargo</th><td>" . htmlspecialchars($funcionario['cargo']) . "</td></tr>";
                        echo "<tr><th>Setor</th><td>" . htmlspecialchars($funcionario['setor']) . "</td></tr>";
                        echo "<tr><th>Login</th><td>" . htmlspecialchars($funcionario['login']) . "</td></tr>";
                        echo "</table>";
                        // Adicionar um botão para editar
                        echo "<button onclick='editarFuncionario(" . htmlspecialchars($funcionario['id']) . ")'>Alterar</button>";
                    } else {
                        echo "<p>Funcionário não encontrado.</p>";
                    }
                } else {
                    echo "<p>ID do funcionário não fornecido.</p>";
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
                    echo "<table border='1' style='border-collapse: collapse; width: 40%;'>";
                    echo "<tr><th>ID</th><th>Nome</th><th>Cargo</th><th>Setor</th><th>Login</th><th>Ações</th></tr>";
                    foreach ($funcionarios as $funcionario) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($funcionario['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($funcionario['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($funcionario['cargo']) . "</td>";
                        echo "<td>" . htmlspecialchars($funcionario['setor']) . "</td>";
                        echo "<td>" . htmlspecialchars($funcionario['login']) . "</td>";
                        echo "<td class='text-center'>
                                <button onclick='excluirFuncionario(" . htmlspecialchars($funcionario['id']) . ")'>Excluir</button>
                                <button onclick='editarFuncionario(" . htmlspecialchars($funcionario['id']) . ")'>Alterar</button>
                              </td>";
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
