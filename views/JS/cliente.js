$(document).ready(function() {
    // Função para cadastrar cliente
    $('#cadastroCliente').submit(function(event) {
        event.preventDefault();
        var nome = $('#nomeCliente').val();
        var telefone = $('#telefone').val();
        var endereco = $('#endereco').val();
        var email = $('#emailCliente').val();
        
        $.ajax({
            url: '../controllers/clienteController.php',
            method: 'POST',
            data: {
                acao: 'salvar',
                nome: nome,
                telefone: telefone,
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
                    $('#listaClientes').html('');
                    clientes.forEach(function(cliente) {
                        $('#listaClientes').append('<li>' + cliente.nome + ' - ' + cliente.telefone + '</li>');
                    });
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
                        $('#clienteEncontrado').html('Nome: ' + cliente.nome + '<br>Telefone: ' + cliente.telefone);
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
        var nome = $('#alterarNome').val();
        var telefone = $('#alterarTelefone').val();
        var endereco = $('#alterarEndereco').val();
        var email = $('#alterarEmail').val();
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
