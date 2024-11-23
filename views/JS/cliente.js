$(document).ready(function() {
    // Aplicar a máscara no campo de telefone na criação e alteração
    $('#telefone, #alterarTelefone').mask('(00) 00000-0000');

    // Função para cadastrar cliente
    $('#cadastroCliente').submit(function(event) {
        event.preventDefault();

        var nome = $('#nomeCliente').val().trim();
        var telefone = $('#telefone').cleanVal(); // Remove a máscara
        var endereco = $('#endereco').val().trim();
        var email = $('#emailCliente').val().trim();

        // Verificar se todos os campos estão preenchidos
        if (!nome || !telefone || !endereco || !email) {
            alert("Todos os campos são obrigatórios!");
            return; // Interrompe o envio
        }

        // Se todos os campos estiverem preenchidos, faz o envio
        $.ajax({
            url: '../controllers/clienteController.php',
            method: 'POST',
            data: {
                acao: 'salvar',
                nome: nome,
                telefone: telefone, // Telefone sem máscara
                endereco: endereco,
                email: email
            },
            success: function(response) {
                console.log(response);
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarClientes(); // Recarrega a lista de clientes
                } catch (e) {
                    alert("Erro na resposta do servidor.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Função para listar clientes
    function carregarClientes() {
        $.ajax({
            url: '../controllers/clienteController.php',
            method: 'POST',
            data: { acao: 'listar' },
            success: function(response) {
                try {
                    var clientes = JSON.parse(response);

                    // Cabeçalho da tabela
                    var tabela = `
                        <table border="1" style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Endereço</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    // Adicionando os clientes na tabela
                    clientes.forEach(function(cliente) {
                        tabela += `
                            <tr>
                                <td>${cliente.id}</td>
                                <td>${cliente.nome}</td>
                                <td>${cliente.telefone}</td>
                                <td>${cliente.endereco}</td>
                            </tr>
                        `;
                    });

                    tabela += `
                            </tbody>
                        </table>
                    `;

                    // Insere a tabela no elemento com ID 'listaClientes'
                    $('#listaClientes').html(tabela);
                } catch (e) {
                    alert("Erro ao carregar clientes.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    }
    

    // Função para buscar cliente
    $('#buscarClienteForm').submit(function(event) {
        event.preventDefault();
        var id = $('#clienteId').val();
        $.ajax({
            url: '../controllers/clienteController.php',
            method: 'POST',
            data: { acao: 'buscar', id: id },
            success: function(response) {
                try {
                    var cliente = JSON.parse(response);
                    if (cliente.message) {
                        $('#clienteEncontrado').html(cliente.message);
                    } else {
                        $('#clienteEncontrado').html('Nome: ' + cliente.nome + '<br>Telefone: ' + cliente.telefone + '<br>Endereço: ' + cliente.endereco);
                    }
                } catch (e) {
                    alert("Erro ao buscar cliente.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Função para alterar cliente
    $('#alterarClienteForm').submit(function(event) {
        event.preventDefault();
        
        var id = $('#alterarId').val();
        var nome = $('#alterarNome').val().trim();
        var telefone = $('#alterarTelefone').cleanVal(); // Remove a máscara
        var endereco = $('#alterarEndereco').val().trim();
        var email = $('#alterarEmail').val().trim();

        // Verificar se todos os campos estão preenchidos
        if (!id || !nome || !telefone || !endereco || !email) {
            alert("Todos os campos são obrigatórios!");
            return; // Interrompe o envio
        }

        // Enviar os dados via AJAX
        $.ajax({
            url: '../controllers/clienteController.php',
            method: 'POST',
            data: {
                acao: 'alterar',
                id: id,
                nome: nome,
                telefone: telefone,
                endereco: endereco,
                email: email
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarClientes(); // Recarrega a lista de clientes
                } catch (e) {
                    alert("Erro ao alterar cliente.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Carregar a lista de clientes inicialmente
    carregarClientes();
});
