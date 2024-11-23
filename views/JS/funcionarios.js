$(document).ready(function() {
    // Função para cadastrar funcionário
    $('#cadastroFuncionario').submit(function(event) {
        event.preventDefault();
        var nome = $('#nomeFuncionario').val();
        var cargo = $('#cargo').val();
        var setor = $('#setor').val();
        var login = $('#login').val();
        var senha = $('#senha').val();

        $.ajax({
            url: '../controllers/funcionarioController.php',
            method: 'POST',
            data: {
                acao: 'salvar',
                nome: nome,
                cargo: cargo,
                setor: setor,
                login: login,
                senha: senha
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarFuncionarios(); // Recarrega a lista de funcionários
                } catch (e) {
                    alert("Erro na resposta do servidor.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

   // Função para listar funcionários
function carregarFuncionarios() {
    $.ajax({
        url: '../controllers/funcionarioController.php',
        method: 'POST',
        data: { acao: 'listar' },
        success: function(response) {
            try {
                var funcionarios = JSON.parse(response);
                // Define a estrutura inicial da tabela com cabeçalhos
                var tabela = `
                    <table border="1" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Cargo</th>
                                <th>Setor</th>
                            </tr>
                        </thead>
                        <tbody id="corpoTabelaFuncionarios"></tbody>
                    </table>
                `;

                // Adiciona a tabela ao elemento de lista
                $('#listaFuncionarios').html(tabela);

                // Preenche as linhas da tabela
                funcionarios.forEach(function(funcionario) {
                    $('#corpoTabelaFuncionarios').append(`
                        <tr>
                            <td>${funcionario.id}</td>
                            <td>${funcionario.nome}</td>
                            <td>${funcionario.cargo}</td>
                            <td>${funcionario.setor}</td>
                        </tr>
                    `);
                });
            } catch (e) {
                alert("Erro ao carregar funcionários.");
            }
        },
        error: function() {
            alert("Erro na comunicação com o servidor.");
        }
    });
}

    // Função para buscar funcionário
    $('#buscarFuncionarioForm').submit(function(event) {
        event.preventDefault();
        var id = $('#funcionarioId').val();
        $.ajax({
            url: '../controllers/funcionarioController.php',
            method: 'POST',
            data: { acao: 'buscar', id: id },
            success: function(response) {
                try {
                    var funcionario = JSON.parse(response);
                    if (funcionario.message) {
                        $('#funcionarioEncontrado').html(funcionario.message);
                    } else {
                        $('#funcionarioEncontrado').html('Nome: ' + funcionario.nome + '<br>Cargo: ' + funcionario.cargo + '<br>Setor: ' + funcionario.setor);
                    }
                } catch (e) {
                    alert("Erro ao buscar funcionário.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Função para alterar funcionário
    $('#alterarFuncionarioForm').submit(function(event) {
        event.preventDefault();
        var id = $('#alterarId').val();
        var nome = $('#alterarNome').val();
        var cargo = $('#alterarCargo').val();
        var setor = $('#alterarSetor').val();
        var login = $('#alterarLogin').val();
        var senha = $('#alterarSenha').val();

        $.ajax({
            url: '../controllers/funcionarioController.php',
            method: 'POST',
            data: {
                acao: 'alterar',
                id: id,
                nome: nome,
                cargo: cargo,
                setor: setor,
                login: login,
                senha: senha
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarFuncionarios(); // Recarrega a lista de funcionários
                } catch (e) {
                    alert("Erro ao alterar funcionário.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Carregar a lista de funcionários inicialmente
    carregarFuncionarios();
});