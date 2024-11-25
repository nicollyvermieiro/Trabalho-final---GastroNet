document.addEventListener('DOMContentLoaded', function () {
    let clientesMap = {}; // Variável global para armazenar o mapeamento dos clientes

    // Função para carregar os clientes
    function carregarClientes() {
        fetch('../controllers/clienteController.php', {
            method: 'POST',
            body: new URLSearchParams({ acao: 'listar' })
        })
        .then(response => response.json())
        .then(data => {
            const clienteSelect = document.getElementById('listaCliente');
            clienteSelect.innerHTML = '<option value="" disabled selected>Selecione um cliente</option>'; // Limpa a lista

            if (data.message) {
                alert(data.message);
            } else {
                data.forEach(cliente => {
                    const option = document.createElement('option');
                    option.value = cliente.id;
                    option.textContent = cliente.nome;
                    clienteSelect.appendChild(option);

                    // Armazenar os clientes no mapa
                    clientesMap[cliente.id] = cliente.nome;
                });

                // Agora que os clientes foram carregados, podemos carregar os pedidos
                carregarPedidos();
            }
        })
        .catch(error => console.error('Erro ao carregar clientes:', error));
    }

  // Função para carregar os pedidos
function carregarPedidos() {
    fetch('../controllers/pedidoController.php', {
        method: 'POST',
        body: new URLSearchParams({ acao: 'listar' })
    })
    .then(response => response.json())
    .then(data => {
        const listaPedidos = document.getElementById('listaPedido');
        listaPedidos.innerHTML = '';

        if (data.message) {
            listaPedidos.innerHTML = `<p>${data.message}</p>`;
        } else {
            data.forEach(pedido => {
                const pedidoElement = document.createElement('li');
                pedidoElement.classList.add('pedido-item');
                
                const nomeCliente = clientesMap[pedido.cliente_id] || 'Cliente Desconhecido';

                pedidoElement.innerHTML = `
                    <strong>Pedido #${pedido.num_pedido}</strong><br>
                    <strong>Cliente:</strong> ${nomeCliente}<br>
                    <strong>Valor Total:</strong> R$ ${parseFloat(pedido.valor_total).toFixed(2)}<br>
                    <strong>Forma de Pagamento:</strong> ${pedido.forma_pag}<br>
                    <strong>Itens:</strong><br>
                `;

                const itensList = document.createElement('ul');
                if (pedido.itens && pedido.itens.length > 0) {
                    pedido.itens.forEach(item => {
                        const itemElement = document.createElement('li');
                        itemElement.innerHTML = `${item.nome} - Quantidade: ${item.quantidade}`;
                        itensList.appendChild(itemElement);
                    });
                } else {
                    const noItemsElement = document.createElement('li');
                    noItemsElement.innerHTML = 'Nenhum item encontrado.';
                    itensList.appendChild(noItemsElement);
                }

                pedidoElement.appendChild(itensList);

                // Botão para finalizar o pedido
                const finalizarButton = document.createElement('button');
                finalizarButton.id = `finalizarPedidoBtn-${pedido.num_pedido}`;
                finalizarButton.textContent = pedido.finalizado ? 'Finalizado' : 'Finalizar Pedido';

                if (pedido.finalizado) {
                    // Estilo de pedido finalizado
                    finalizarButton.style.backgroundColor = '#28a745';
                    finalizarButton.style.color = '#fff';
                    finalizarButton.disabled = true;
                } else {
                    // Estilo padrão
                    finalizarButton.addEventListener('click', function () {
                        finalizarPedido(pedido.num_pedido, finalizarButton);
                    });
                }

                pedidoElement.appendChild(finalizarButton);
                listaPedidos.appendChild(pedidoElement);
            });
        }
    })
    .catch(error => console.error('Erro ao carregar pedidos:', error));
}

function finalizarPedido(numPedido, button) {
    fetch('../controllers/pedidoController.php', {
        method: 'POST',
        body: new URLSearchParams({
            acao: 'finalizar',
            num_pedido: numPedido
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.message === 'Pedido finalizado com sucesso!') {
            // Atualiza a cor do botão para verde e o desabilita
            button.style.backgroundColor = '#28a745';
            button.style.color = '#fff';
            button.innerHTML = '<strong>Finalizado</strong>';
            button.disabled = true;
        }
    })
    .catch(error => console.error('Erro ao finalizar pedido:', error));
}

    

    // Função para carregar os itens do cardápio
    function carregarItensCardapio() {
        fetch('../controllers/cardapioController.php', {
            method: 'POST',
            body: new URLSearchParams({ acao: 'listar' })
        })
        .then(response => response.json())
        .then(data => {
            const itensDiv = document.getElementById('listaItens');
            itensDiv.innerHTML = ''; // Limpa a lista de itens
    
            if (data.message) {
                alert(data.message);
            } else {
                // Agrupar os itens por categoria
                const categorias = {};
    
                data.forEach(item => {
                    if (!categorias[item.categoria]) {
                        categorias[item.categoria] = [];
                    }
                    categorias[item.categoria].push(item);
                });
    
                // Exibir as categorias e itens
                for (const categoria in categorias) {
                    // Adicionar o título da categoria
                    const categoriaTitulo = document.createElement('h3');
                    categoriaTitulo.textContent = categoria;
                    itensDiv.appendChild(categoriaTitulo);
    
                    // Dentro da função carregarItensCardapio
                    categorias[categoria].forEach(item => {
                        const label = document.createElement('label');
                        label.classList.add('checkbox-item');

                        const inputCheckbox = document.createElement('input');
                        inputCheckbox.type = 'checkbox';
                        inputCheckbox.value = item.id;
                        inputCheckbox.setAttribute('data-nome', item.nome); 
                        inputCheckbox.setAttribute('data-preco', item.valor);
                        inputCheckbox.setAttribute('data-descricao', item.descricao); // Adiciona a descrição

                        const inputQuantidade = document.createElement('input');
                        inputQuantidade.type = 'number';
                        inputQuantidade.value = 1; // Valor inicial da quantidade
                        inputQuantidade.min = 1; // Definir o mínimo como 1
                        inputQuantidade.setAttribute('data-item-id', item.id); // Usar o ID do item para referenciar a quantidade

                        // Cria o texto do item (nome, valor e descrição)
                        const textoItem = document.createElement('span');
                        textoItem.textContent = `${item.nome} - R$ ${parseFloat(item.valor).toFixed(2)} - ${item.descricao}`;

                        // Adiciona os elementos ao label
                        label.appendChild(inputCheckbox);
                        label.appendChild(textoItem);
                        label.appendChild(inputQuantidade); // Adiciona o campo de quantidade ao lado do item

                        // Adiciona o label à lista
                        itensDiv.appendChild(label);
                    });

                }
    
                // Atualiza o valor total ao selecionar itens
                itensDiv.addEventListener('change', atualizarValorTotal);
            }
        })
        .catch(error => console.error('Erro ao carregar itens do cardápio:', error));
    }

    // Função para calcular e atualizar o valor total com base nas quantidades
    function atualizarValorTotal() {
        const checkboxes = document.querySelectorAll('#listaItens input[type="checkbox"]:checked');
        let total = 0;

        checkboxes.forEach(checkbox => {
            const preco = parseFloat(checkbox.getAttribute('data-preco')) || 0;
            const quantidadeInput = checkbox.closest('label').querySelector('input[type="number"]');
            const quantidade = parseInt(quantidadeInput.value) || 1; // Usar a quantidade especificada, com 1 como valor padrão
            total += preco * quantidade; // Atualiza o valor total com base na quantidade
        });

        const valorTotalElement = document.getElementById('valorTotal');
        valorTotalElement.textContent = total.toFixed(2);
    }


    // Função para cadastrar um novo pedido
    document.getElementById('cadastroPedido').addEventListener('submit', function (event) {
        event.preventDefault();
    
        const clienteId = document.getElementById('listaCliente').value;
        const checkboxes = document.querySelectorAll('#listaItens input[type="checkbox"]:checked');
        const formaPag = document.getElementById('formaPag').value;
    
        if (!clienteId || checkboxes.length === 0) {
            alert('Por favor, selecione um cliente e pelo menos um item.');
            return;
        }
    
        const itens = Array.from(checkboxes).map(checkbox => ({
            id: checkbox.value,
            nome: checkbox.getAttribute('data-nome'),  // Adiciona o nome do item
            descricao: checkbox.getAttribute('data-descricao'),  // Adiciona a descrição
            quantidade: parseInt(checkbox.closest('label').querySelector('input[type="number"]').value) || 1  // Quantidade
        }));
        
    
        const valorTotal = parseFloat(document.getElementById('valorTotal').textContent);
    
        fetch('../controllers/pedidoController.php', {
            method: 'POST',
            body: new URLSearchParams({
                acao: 'salvar',
                cliente_id: clienteId,
                itens: JSON.stringify(itens),
                valor_total: valorTotal.toFixed(2),
                forma_pag: formaPag
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.message === 'Pedido cadastrado com sucesso!') {
                carregarPedidos();
            }
        })
        .catch(error => console.error('Erro ao cadastrar pedido:', error));
    });

    // Carregar os dados ao carregar a página
    carregarClientes();
    carregarItensCardapio();
});
