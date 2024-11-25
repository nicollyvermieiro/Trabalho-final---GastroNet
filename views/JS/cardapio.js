$(document).ready(function() {
    // Função para cadastrar Cardapio
    $('#cadastroCardapio').submit(function(event) {
        event.preventDefault();
        var nome = $('#nomeItem').val();
        var descricao = $('#Descricao').val();
        var valor = $('#Valor').val();
        var categoria = $('#Categoria').val();

        $.ajax({
            url: '../controllers/cardapioController.php',
            method: 'POST',
            data: {
                acao: 'salvar',
                nome: nome,
                descricao: descricao,
                valor: valor,
                categoria: categoria  
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarCardapios(); // Recarrega a lista de Cardapios
                } catch (e) {
                    Swal.fire({
                        title: "ERRO",
                        text: "Erro na resposta do servidor.",
                        icon: "error",
                        confirmButtonColor: '#5e0a0a'
                      });
                }
            },
            error: function() {
                Swal.fire({
                    title: "ERRO",
                    text: "Erro na comunicação com o servidor.",
                    icon: "error",
                    confirmButtonColor: '#5e0a0a'
                  });
            }
        });
    });

    // Função para listar Cardapios em formato de tabela
    function carregarCardapios() {
        $.ajax({
            url: '../controllers/cardapioController.php',
            method: 'POST',
            data: { acao: 'listar' },
            success: function(response) {
                try {
                    var cardapios = JSON.parse(response);
                    var tabelaBody = $('#listaCardapios tbody');
                    tabelaBody.html(''); // Limpa a tabela antes de adicionar os itens

                    // Adicionando os cardápios na tabela
                    cardapios.forEach(function(cardapio) {
                        // Formata o valor para o formato de moeda
                        var valorFormatado = parseFloat(cardapio.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });

                        tabelaBody.append(`
                            <tr>
                                <td>${cardapio.id}</td>
                                <td>${cardapio.categoria}</td>
                                <td>${cardapio.nome}</td>
                                <td>${cardapio.descricao}</td>
                                <td>${valorFormatado}</td>
                            </tr>
                            <tr><td colspan="5"><hr></td></tr> 
                        `);
                    });

                } catch (e) {
                    Swal.fire({
                        title: "ERRO",
                        text: "Erro ao carregar cardápios.",
                        icon: "error",
                        confirmButtonColor: '#5e0a0a'
                      });
                }
            },
            error: function() {
                Swal.fire({
                    title: "ERRO",
                    text: "Erro na comunicação com o servidor.",
                    icon: "error",
                    confirmButtonColor: '#5e0a0a'
                  });
            }
        });
    }

    
    // Função para alterar cardapio
    $('#alterarCardapioForm').submit(function(event) {
        event.preventDefault();
        var id = $('#alterarId').val();
        var nome = $('#alterarItem').val();
        var descricao = $('#alterarDescricao').val();
        var valor = $('#alteraValor').val();
        var categoria = $('#alteraCategoria').val();
    
        $.ajax({
            url: '../controllers/cardapioController.php',
            method: 'POST',
            data: {
                acao: 'alterar',
                id: id,
                nome: nome,
                descricao: descricao,
                valor: valor,
                categoria: categoria
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarCardapios(); 
                } catch (e) {
                    Swal.fire({
                        title: "ERRO",
                        text: "Erro ao alterar cardápio.",
                        icon: "error",
                        confirmButtonColor: '#5e0a0a'
                      });
                }
            },
            error: function() {
                Swal.fire({
                    title: "ERRO",
                    text: "Erro na comunicação com o servidor.",
                    icon: "error",
                    confirmButtonColor: '#5e0a0a'
                  });
            }
        });
    });
    

    // Carregar os cardápios ao carregar a página
    carregarCardapios();
});
