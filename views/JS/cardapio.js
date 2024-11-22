$(document).ready(function() {
    // Função para cadastrar Cardapio
    $('#cadastroCardapio').submit(function(event) {
        event.preventDefault();
        var nome = $('#nomeItem').val();
        var descricao = $('#Descricao').val();
        var valor = $('#Valor').val();

        $.ajax({
            url: '../controllers/cardapioController.php',
            method: 'POST',
            data: {
                acao: 'salvar',
                nome: nome,
                descricao: descricao,
                valor: valor
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    alert(data.message);
                    carregarCardapios(); // Recarrega a lista de Cardapios
                } catch (e) {
                    alert("Erro na resposta do servidor.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    });

    // Função para listar Cardapios
    function carregarCardapios() {
        $.ajax({
            url: '../controllers/cardapioController.php',
            method: 'POST',
            data: { acao: 'listar' },
            success: function(response) {
                try {
                    var cardapios = JSON.parse(response);
                    $('#listaCardapios').html('');
                    cardapios.forEach(function(cardapio) {
                        $('#listaCardapios').append('<li>' + cardapio.nome + ' - ' + cardapio.valor + '</li>');
                    });
                } catch (e) {
                    alert("Erro ao carregar cardapios.");
                }
            },
            error: function() {
                alert("Erro na comunicação com o servidor.");
            }
        });
    }

    carregarCardapios();
});


/*document.getElementById('cadastraCardapio').addEventListener('submit', function(event) {
    event.preventDefault();

    // Coleta os dados do formulário
    const nome = document.getElementById('nome').value;
    const descricao = document.getElementById('descricao').value;
    const valor = document.getElementById('valor').value;

    // Envia os dados para o backend via POST
    fetch('../controllers/CardapioController.php', {
        method: 'POST',
        body: new URLSearchParams({
            acao: 'salvar',
            nome: nome,
            descricao: descricao,
            valor: valor
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === "Cardápio cadastrado com sucesso!") {
            // Exibe os cardápios atualizados
            carregarCardapios();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Erro ao cadastrar cardápio:', error));
});

// Função para carregar todos os cardápios
function carregarCardapios() {
    fetch('../controllers/CardapioController.php', {
        method: 'POST',
        body: new URLSearchParams({
            acao: 'listar'
        })
    })
    .then(response => response.json())
    .then(data => {
        const cardapiosList = document.getElementById('cardapiosList');
        cardapiosList.innerHTML = '';  // Limpa a lista antes de recarregar

        if (data.message) {
            cardapiosList.innerHTML = `<p>${data.message}</p>`;
        } else {
            data.forEach(cardapio => {
                const cardapioElement = document.createElement('div');
                cardapioElement.classList.add('cardapio-item');
                cardapioElement.innerHTML = `
                    <h3>${cardapio.nome}</h3>
                    <p class="descricao"><strong>Descrição:</strong> ${cardapio.descricao}</p>
                    <p class="valor"><strong>Valor:</strong> R$ ${cardapio.valor}</p>
                `;
                cardapiosList.appendChild(cardapioElement);
            });
        }
    })
    .catch(error => console.error('Erro ao carregar cardápios:', error));
}


// Carregar os cardápios ao carregar a página
window.onload = carregarCardapios;*/